<?php

namespace nk\DocumentBundle\Services;

use Doctrine\ORM\EntityManager;
use nk\DocumentBundle\Entity\DocumentRepository;
use nk\DocumentBundle\Search\KeyWord;

class MetadataFinder
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var DocumentRepository
     */
    private $docRepo;

    private $cachedData;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->cachedData = null;
        $this->docRepo = $this->em->getRepository('nkDocumentBundle:Document');
    }

    public function findAll()
    {
        if($this->cachedData === null)
            $this->cachedData = array(
                'types' => array('Annale', 'Cour', 'TD', 'TP'),
                'classes' => $this->docRepo->findDistinct('class'),
                'fields' => $this->docRepo->findDistinct('field'),
                'units' => $this->docRepo->findDistinct('unit'),
                'teachers' => $this->docRepo->findDistinct('teacher'),
            );

        return $this->cachedData;
    }

    public function findField($field)
    {
        $data = $this->findAll();

        return isset($data[$field]) ? $data[$field] : array();
    }

    public function getStandardizeSuggestions()
    {
        $suggestions = array();

        foreach($this->findAll() as $field => $data){
            $suggestion = array();

            foreach($data as $str){
                $metaphone = KeyWord::metaphone($str);

                if(isset($suggestion[$metaphone]))
                    $suggestion[$metaphone][] = $str;
                else
                    $suggestion[$metaphone] = array($str);
            }

            foreach($suggestion as $s){
                if(count($s) > 1){
                    if(isset($suggestions[$field]))
                        $suggestions[$field][] = implode(', ', $s);
                    else
                        $suggestions[$field] = array(implode(', ', $s));
                }
            }
        }

        return $suggestions;
    }
}