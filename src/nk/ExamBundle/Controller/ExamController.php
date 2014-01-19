<?php

namespace nk\ExamBundle\Controller;

use nk\ExamBundle\Entity\Resource;
use nk\ExamBundle\Form\ResourceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class ExamController extends Controller
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
    public function nextAction()
    {
        return array(
            'exams' => $this->get('nk_exam.ade.explorer')->find(),
        );
    }
}
