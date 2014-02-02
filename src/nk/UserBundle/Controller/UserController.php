<?php

namespace nk\UserBundle\Controller;

use nk\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class UserController extends Controller
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
     * @var Paginator
     */
    private $paginator;

    /**
     * @Template
     */
    public function showAction(User $user)
    {
        return array(
            'user' => $user,
        );
    }

    /**
     * @Template
     */
    public function myDocumentsAction()
    {
        $documents = $this->get('knp_paginator')->paginate(
            $this->em->getRepository('nkDocumentBundle:Document')->queryLatestOfUser($this->getUser()),
            $this->request->query->get('page', 1),
            20
        );

        return array(
            'documents' => $documents,
        );
    }
}
