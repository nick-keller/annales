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

        return $this->handleForm($document);
    }

    /**
     * @Template
     */
    public function showAction(Document $document, $slug)
    {
        if($slug != $document->getSlug()){
            return $this->redirect($this->generateUrl('nk_document_show', array(
                'id' => $document->getId(),
                'slug' => $document->getSlug(),
            )), 301);
        }

        return array(
            'document' => $document,
        );
    }

    private function handleForm(Document $document)
    {
        $form = $this->createForm(new DocumentType, $document);

        if($this->request->isMethod('POST')){
            $form->handleRequest($this->request);

            if($form->isValid()){
                $this->em->persist($document);
                $this->em->flush();
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
