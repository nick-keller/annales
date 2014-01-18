<?php

namespace nk\ExamBundle\Controller;

use nk\ExamBundle\Entity\Resource;
use nk\ExamBundle\Form\ResourceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class ResourceController extends Controller
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
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     */
    public function adminAction()
    {
        return array(
            'resources' => $this->em->getRepository('nkExamBundle:Resource')->findAll(),
        );
    }

    /**
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     */
    public function newAction()
    {
        $resource = new Resource;

        return $this->handleForm($resource);
    }

    /**
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     */
    public function editAction(Resource $resource)
    {
        return $this->handleForm($resource);
    }

    private function handleForm(Resource $resource)
    {
        $form = $this->createForm(new ResourceType, $resource);

        if($this->request->isMethod('POST')){
            $form->handleRequest($this->request);

            if($form->isValid()){
                $this->em->persist($resource);
                $this->em->flush();

                return $this->redirect($this->generateUrl('nk_exam_admin'));
            }
        }

        return array(
            'form' => $form->createView(),
            'resource' => $resource,
        );
    }
}
