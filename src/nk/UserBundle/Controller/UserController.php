<?php

namespace nk\UserBundle\Controller;

use nk\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;

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
     * @Secure(roles="ROLE_ADMIN")
     */
    public function adminAction()
    {
        return array(
            'users' => $this->paginator->paginate(
                $this->em->getRepository('nkUserBundle:User')->queryAll(),
                $this->get('request')->query->get('page', 1),
                25
            ),
            'admins' => $this->em->getRepository('nkUserBundle:User')->findAdmins(),
            'userList' => implode(',', $this->em->getRepository('nkUserBundle:User')->findAllUsernames()),
        );
    }

    /**
     * @Template
     */
    public function showAction(User $user)
    {
        return array(
            'user' => $user,
            'stat' => $this->em->getRepository('nkDocumentBundle:Document')->getStatFromUser($user)[0],
        );
    }

    /**
     * @Template
     */
    public function documentsAction(User $user)
    {
        $documents = $this->get('knp_paginator')->paginate(
            $this->em->getRepository('nkDocumentBundle:Document')->queryLatestOfUser($this->getUser()),
            $this->request->query->get('page', 1),
            20
        );

        return array(
            'user' => $user,
            'documents' => $documents,
        );
    }

    /**
     * @Template
     */
    public function foldersAction(User $user)
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

    /**
     * @Secure(roles="ROLE_ADMIN")
     */
    public function setAdminAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($this->request->request->get('username'));
        $roles = $user->getRoles();
        $roles[] = 'ROLE_ADMIN';
        $user->setRoles($roles);

        $this->em->persist($user);
        $this->em->flush();

        return $this->redirect($this->generateUrl('nk_user_admin'));
    }

    /**
     * @Secure(roles="ROLE_ADMIN")
     */
    public function unsetAdminAction($username)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);
        $roles = $user->getRoles();
        unset($roles[array_search('ROLE_TEST', $roles)]);
        $user->setRoles($roles);

        $this->em->persist($user);
        $this->em->flush();

        return new Response();
    }
}
