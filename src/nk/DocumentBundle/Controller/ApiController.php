<?php

namespace nk\DocumentBundle\Controller;

use nk\DocumentBundle\Entity\Document;
use nk\DocumentBundle\Entity\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;

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
        $document->setViewed($document->getViewed() + 1);
        $this->em->persist($document);
        $this->em->flush();

        return array(
            'document' => $document
        );
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function renameAction(File $file)
    {
        if($this->getUser() != $file->getDocument()->getAuthor())
            return $this->getResponse(array(
                'success' => 0,
                'error' => "Ce fichier ne vous appartiens pas",
            ));

        $name = $this->request->query->get('name', null);

        if($name === null || trim($name) == "")
            return $this->getResponse(array(
                'success' => 0,
                'error' => "Vous devez spécifier un nom",
            ));

        $file->setName($name);
        $this->em->persist($file);
        $this->em->flush();

        return $this->getResponse(array('success' => 1));
    }

    /**
     * @Template
     * @Secure(roles="ROLE_USER")
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
