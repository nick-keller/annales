<?php

namespace nk\DocumentBundle\Controller;

use nk\DocumentBundle\Entity\Document;
use nk\DocumentBundle\Entity\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class ApiController extends Controller
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
     * @Template
     */
    public function previewAction(Document $document)
    {
        return array(
            'document' => $document
        );
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function renameAction(File $file)
    {
        $file->setName($this->request->query->get('name'));
        $this->em->persist($file);
        $this->em->flush();

        return $this->getResponse(array('success' => 1));
    }

    /**
     * @Template
     */
    public function foldersAction(Document $document)
    {
        return array(
            'document' => $document
        );
    }


    private function getResponse(array $tab)
    {
        $response = new Response(json_encode( $tab ));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
