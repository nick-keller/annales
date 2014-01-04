<?php

namespace nk\DocumentBundle\Controller;

use nk\DocumentBundle\Entity\Document;
use nk\DocumentBundle\Entity\File;
use nk\DocumentBundle\Form\FileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

class FileController extends Controller
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
     * @var UploadableManager
     */
    private $uploadableManager;

    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     */
    public function uploadAction(Document $document)
    {
        $file = new File($document);
        $form = $this->createForm(new FileType, $file);

        if($this->request->isMethod('POST')){
            $form->handleRequest($this->request);

            $response = array(
                'success' => 0,
            );

            if($form->isValid()){
                $this->em->persist($file);
                if($file->getFile()) $this->get('stof_doctrine_extensions.uploadable.manager')->markEntityToUpload($file, $file->getFile());
                $this->em->flush();

                $response['success'] = 1;
                $response['id'] = $file->getId();
                $response['template'] = $this->generateUrl('nk_document_file', array('id' => $file->getId()), UrlGenerator::ABSOLUTE_URL);
            }

            $response = new Response(json_encode($response));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        return array(
            'document' => $document,
            'form' => $form->createView(),
        );
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function renameAction(File $file)
    {
        $response = array('success' => 1);

        $file->setName($this->request->query->get('name'));
        $this->em->persist($file);
        $this->em->flush();

        $response = new Response(json_encode( $response ));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Template
     */
    public function showAction(File $file)
    {
        return array(
            'file' => $file,
        );
    }
}
