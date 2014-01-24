<?php

namespace nk\ExamBundle\Controller;

use nk\ExamBundle\Entity\IgnoredExam;
use nk\ExamBundle\Entity\Resource;
use nk\ExamBundle\Form\ResourceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;

class IgnoredExamController extends Controller
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
     */
    public function ignoreAction($unit)
    {
        $this->em->getRepository('nkExamBundle:IgnoredExam')->unignore($unit, $this->getUser());
        $ignoredExam = new IgnoredExam($unit);
        $ignoredExam->setUser($this->getUser());

        $this->em->persist(($ignoredExam));
        $this->em->flush();

        return new Response('Success', 200);
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function unignoreAction($unit)
    {
        $this->em->getRepository('nkExamBundle:IgnoredExam')->unignore($unit, $this->getUser());

        return new Response('Success', 200);
    }
}
