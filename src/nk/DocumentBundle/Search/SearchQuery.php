<?php

namespace nk\DocumentBundle\Search;


use Doctrine\ORM\EntityManager;
use nk\DocumentBundle\Entity\DocumentRepository;
use Knp\Component\Pager\Paginator;

class SearchQuery
{
    /**
     * @var string
     * The exact entire query
     */
    private $query;

    /**
     * @var array
     * An array containing each KeyWord
     */
    private $keyWords = array();

    /**
     * @var array
     */
    private $metadata;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @var array
     */
    private $mappedQuery = array();

    private $result = null;

    function __construct($query, array $metadata, EntityManager $em, Paginator $paginator)
    {
        $this->query     = preg_replace('# +#', ' ', trim($query));
        $this->metadata  = $metadata;
        $this->em        = $em;
        $this->paginator = $paginator;

        foreach(explode(' ', $this->query) as $word)
            $this->keyWords[] = new KeyWord($word, $metadata);

        $this->mapQuery();
    }

    public function __toString()
    {
        return $this->query;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    public function hasSuggestion()
    {
        foreach($this->keyWords as $word)
            if($word->getSuggestion() !== null)
                return true;
        return false;
    }

    public function getSuggestion($html = false)
    {
        $query = array();

        foreach($this->keyWords as $word){

            if($word->getSuggestion() !== null)
                $query[] = ($html?'<b>':'') . $word->getSuggestion() . ($html?'</b>':'');
            else
                $query[] = $word->getWord();
        }

        return implode(' ', $query);
    }

    private function mapQuery()
    {
        $mapping = array(
            KeyWord::KEYWORD_TYPE => 'type',
            KeyWord::KEYWORD_CLASS => 'class',
            KeyWord::KEYWORD_FIELD => 'field',
            KeyWord::KEYWORD_UNIT => 'unit',
            KeyWord::KEYWORD_TEACHER => 'teacher',
            KeyWord::KEYWORD_YEAR => 'yeas',
            KeyWord::KEYWORD_SUBJECT => 'subject',
        );

        foreach($this->keyWords as $word){
            if(!isset($this->mappedQuery[$mapping[$word->getType()]]))
                $this->mappedQuery[$mapping[$word->getType()]] = array();
            $this->mappedQuery[$mapping[$word->getType()]][] = $word->getWord();
        }
    }

    public function getResult($page = 1, $limitPerPage = 1)
    {
        if($this->result === null){
            /**
             * @var DocumentRepository
             */
            $repo = $this->em->getRepository('nkDocumentBundle:Document');
            $query = $repo->searchQuery($this->mappedQuery);

            $this->result = $this->paginator->paginate($query, $page, $limitPerPage);
        }

        return $this->result;
    }
}