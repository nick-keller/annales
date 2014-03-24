<?php

namespace nk\DocumentBundle\Controller;

use nk\DocumentBundle\Entity\Standardize;
use nk\DocumentBundle\Form\StandardizeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class AdminController extends Controller
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
    public function indexAction()
    {
        $field = $this->request->query->get('f', 'field');
        $standardize = new Standardize($field);
        $form = $this->createForm(
            new StandardizeType(
                $this->get('nk.metadata_finder')->findField($field=='class'?'classes':$field.'s')
            ),
            $standardize
        );

        if($this->request->isMethod('POST') && in_array($field, array('class', 'field', 'unit', 'teacher'))){
            $form->handleRequest($this->request);

            if($form->isValid()){
                $this->em->getRepository('nkDocumentBundle:Document')->standardize($standardize);
                return $this->redirect($this->generateUrl('nk_document_admin', array('f'=>$field)));
            }
        }

        return array(
            'form' => $form->createView(),
            'suggestions' => $this->get('nk.metadata_finder')->getStandardizeSuggestions(),
        );
    }

    /**
     * @Template
     * @Secure(roles="ROLE_ADMIN")
     */
    public function allAction()
    {
        return array(
            'documents' => $this->get('knp_paginator')->paginate(
                $this->em->getRepository('nkDocumentBundle:Document')->queryAll(),
                $this->get('request')->query->get('page', 1),
                25
            ),
        );
    }
}
