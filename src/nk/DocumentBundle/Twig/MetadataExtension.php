<?php

namespace nk\DocumentBundle\Twig;


use nk\DocumentBundle\Services\MetadataFinder;

class MetadataExtension extends \Twig_Extension
{
    /**
     * @var MetadataFinder
     */
    private $mf;

    function __construct(MetadataFinder $mf)
    {
        $this->mf = $mf;
    }

    public function getFunctions()
    {
        return array(
            'metadata' => new \Twig_Function_Method($this, 'metadata'),
        );
    }

    public function metadata()
    {
        $data = array();

        foreach($this->mf->findAll() as $field)
            $data = array_merge($data, $field);

        return implode(',', $data);
    }

    public function getName()
    {
        return 'metadata_extension';
    }
}