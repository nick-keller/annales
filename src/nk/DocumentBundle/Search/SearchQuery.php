<?php

namespace nk\DocumentBundle\Search;


use Doctrine\ORM\EntityManager;

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

    function __construct($query, array $metadata, EntityManager $em)
    {
        $this->query    = preg_replace('# +#', ' ', trim($query));
        $this->metadata = $metadata;
        $this->em       = $em;

        foreach(explode(' ', $this->query) as $word)
            $this->keyWords[] = new KeyWord($word, $metadata);
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
}