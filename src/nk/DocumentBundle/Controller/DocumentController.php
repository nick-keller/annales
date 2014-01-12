<?php

namespace nk\DocumentBundle\Controller;

use nk\DocumentBundle\Entity\Document;
use nk\DocumentBundle\Form\DocumentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class DocumentController extends Controller
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Request
     */
    private $request;

    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function newAction()
    {
        $document = new Document;

        return $this->handleForm($document, 'nk_document_upload');
    }

    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function editAction(Document $document)
    {
        return $this->handleForm($document, 'nk_document_show');
    }

    /**
     * @Template
     */
    public function showAction($class, $field, Document $document, $slug)
    {
        if($slug != $document->getSlug() || $class != $document->getClass() || $field != $document->getField()){
            return $this->redirect($this->generateUrl('nk_document_show', array(
                'id' => $document->getId(),
                'slug' => $document->getSlug(),
                'class' => $document->getClass(),
                'field' => $document->getField(),
            )), 301);
        }

        return array(
            'document' => $document,
        );
    }

    public function allAction($class, $field)
    {
        if($class === null){
            return $this->render('nkDocumentBundle:Document:list_classes.html.twig', array(
                'classes' => $this->get('nk.metadata_finder')->findAll()['classes'],
            ));
        }
        else if($field === null){
            return $this->render('nkDocumentBundle:Document:list_fields.html.twig', array(
                'class' => $class,
                'fields' => $this->em->getRepository('nkDocumentBundle:Document')->findFieldsFromClass($class),
            ));
        }
        else{
            return $this->render('nkDocumentBundle:Document:list_documents.html.twig', array(
                'class' => $class,
                'field' => $field,
                'documents' => $this->em->getRepository('nkDocumentBundle:Document')->findBy(array(
                    'class' => $class,
                    'field' => $field
                )),
            ));
        }
    }

    private function handleForm(Document $document, $route)
    {
        $form = $this->createForm(new DocumentType($this->get('nk.metadata_finder')->findAll()), $document);

        if($this->request->isMethod('POST')){
            $form->handleRequest($this->request);

            if($form->isValid()){
                $this->em->persist($document);
                $this->em->flush();

                return $this->redirect($this->generateUrl($route, array(
                    'id' => $document->getId(),
                    'slug' => $document->getSlug(),
                    'class' => $document->getClass(),
                    'field' => $document->getField(),
                )));
            }
        }

        return array(
            'form' => $form->createView(),
            'document' => $document,
        );
    }
}
