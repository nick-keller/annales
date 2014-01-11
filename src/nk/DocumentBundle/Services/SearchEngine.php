<?php

namespace nk\DocumentBundle\Services;

use Doctrine\ORM\EntityManager;
use nk\DocumentBundle\Search\SearchQuery;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;

class SearchEngine
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var MetadataFinder
     */
    private $mf;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @var Request
     */
    private $request;

    private $limitPerPage;

    function __construct(EntityManager $em, MetadataFinder $mf, Paginator $paginator, Request $request, $limitPerPage)
    {
        $this->em = $em;
        $this->mf = $mf;
        $this->paginator = $paginator;
        $this->request = $request;
        $this->limitPerPage = $limitPerPage;
    }

    public function search($query)
    {
        $searchQuery = new SearchQuery(
            $query,
            $this->mf->findAll(),
            $this->em,
            $this->paginator
        );

        $searchQuery->getResult($this->request->query->get('page', 1), $this->limitPerPage);

        return $searchQuery;
    }
}