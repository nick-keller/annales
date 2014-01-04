<?php

namespace nk\DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * File
 *
 * @ORM\Table(name="nk_file")
 * @ORM\Entity(repositoryClass="nk\DocumentBundle\Entity\FileRepository")
 * @Gedmo\Uploadable(allowOverwrite = true, filenameGenerator = "SHA1")
 */
class File
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Document
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="files")
     * @ORM\JoinColumn(nullable=false)
     */
    private $document;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="size", type="string", length=10)
     * @Assert\NotBlank()
     */
    private $size;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     * @Gedmo\UploadableFilePath
     */
    private $path;

    /**
     * @Assert\File(
     *     maxSize="64M",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Ce fichier n'est pas un pdf",
     *     maxSizeMessage = "Fichier trop gros (64Mo max)"
     * )
     */
    private $file;

    public function __construct(Document $document = null)
    {
        if($document !== null)
            $this->document = $document;
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        if(substr($name, -4) !== '.pdf') $name .= '.pdf';

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return File
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set document
     *
     * @param Document $document
     * @return File
     */
    public function setDocument(Document $document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return Document
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Get web path
     *
     * @return string
     */
    public function getWebPath()
    {
        return preg_replace('#^.+\.\./www/(.+)$#', '$1', $this->getPath());
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }
}
