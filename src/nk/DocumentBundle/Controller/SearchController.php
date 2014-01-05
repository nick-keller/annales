<?php

namespace nk\DocumentBundle\Controller;

use nk\DocumentBundle\Entity\Document;
use nk\DocumentBundle\Search\SearchQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class SearchController extends Controller
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
    public function searchAction()
    {
        $searchQuery = new SearchQuery(
            $this->request->query->get('s'),
            $this->get('nk.metadata_finder')->findAll(),
            $this->em
        );

        $searchQuery->getResult();

        return array(
            'searchQuery' => $searchQuery,
        );
    }
}
