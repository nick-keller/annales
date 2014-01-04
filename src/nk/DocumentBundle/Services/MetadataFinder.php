<?php

namespace nk\DocumentBundle\Services;

use Doctrine\ORM\EntityManager;
use nk\DocumentBundle\Entity\DocumentRepository;

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
}