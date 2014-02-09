<?php

namespace nk\FolderBundle\Controller;

use nk\FolderBundle\Entity\Folder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;

class FolderController extends Controller
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
     * @Secure(roles="ROLE_USER")
     */
    public function myFoldersAction()
    {
        return array();
    }

    /**
     * @Template
     */
    public function showAction(Folder $folder)
    {
        return array(
            'folder' => $folder,
            'suggestions' => $this->em->getRepository('nkDocumentBundle:Document')->findSuggestionsFromFolder($folder),
        );
    }
}
