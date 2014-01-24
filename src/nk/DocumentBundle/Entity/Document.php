<?php

namespace nk\DocumentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use nk\UserBundle\Entity\User as User;

/**
 * Document
 *
 * @ORM\Table(name="nk_document")
 * @ORM\Entity(repositoryClass="nk\DocumentBundle\Entity\DocumentRepository")
 */
class Document
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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=10)
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"Annale", "Cour", "TD", "TP"})
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="class", type="string", length=10)
     * @Assert\NotBlank()
     */
    private $class;

    /**
     * @var string
     *
     * @ORM\Column(name="field", type="string", length=30)
     * @Assert\NotBlank()
     */
    private $field;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=10)
     * @Assert\NotBlank()
     * @Assert\Regex("/^[A-Z]{2,4}-[0-9]{4}$/")
     */
    private $unit;

    /**
     * @var string
     *
     * @ORM\Column(name="year", type="string", length=10)
     * @Assert\NotBlank()
     * @Assert\Regex("/^20[0-9]{2}([ -]20[0-9]{2})?$/")
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="teacher", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $teacher;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=200)
     * @Assert\NotBlank()
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=204)
     * @Gedmo\Slug(fields={"subject"})
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="nk\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     * @Gedmo\Blameable(on="create")
     */
    private $author;

    /**
     * @var integer
     *
     * @ORM\Column(name="downloaded", type="integer")
     */
    private $downloaded = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="viewed", type="integer")
     */
    private $viewed = 0;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="File", mappedBy="document", cascade={"remove"})
     */
    protected $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getSubject();
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
     * Set type
     *
     * @param string $type
     * @return Document
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set promo
     *
     * @param string $class
     * @return Document
     */
    public function setClass($class)
    {
        $this->class = strtoupper($this->strip_accents($class));

        return $this;
    }

    /**
     * Get promo
     *
     * @return string 
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set field
     *
     * @param string $field
     * @return Document
     */
    public function setField($field)
    {
        $this->field = ucfirst(strtolower($this->strip_accents($field)));

        return $this;
    }

    /**
     * Get field
     *
     * @return string 
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param string $unit
     */
    public function setUnit($unit)
    {
        $this->unit = strtoupper($this->strip_accents($unit));
    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set year
     *
     * @param string $year
     * @return Document
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set teacher
     *
     * @param string $teacher
     * @return Document
     */
    public function setTeacher($teacher)
    {
        $this->teacher = ucfirst(strtolower($this->strip_accents($teacher)));

        return $this;
    }

    /**
     * Get teacher
     *
     * @return string 
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Document
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Document
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set downloaded
     *
     * @param integer $downloaded
     * @return Document
     */
    public function setDownloaded($downloaded)
    {
        $this->downloaded = $downloaded;

        return $this;
    }

    /**
     * Get downloaded
     *
     * @return integer 
     */
    public function getDownloaded()
    {
        return $this->downloaded;
    }

    /**
     * Set viewed
     *
     * @param integer $viewed
     * @return Document
     */
    public function setViewed($viewed)
    {
        $this->viewed = $viewed;

        return $this;
    }

    /**
     * Get viewed
     *
     * @return integer 
     */
    public function getViewed()
    {
        return $this->viewed;
    }

    /**
     * Add files
     *
     * @param File $files
     * @return Document
     */
    public function addFile(File $files)
    {
        $this->files[] = $files;

        return $this;
    }

    /**
     * Remove files
     *
     * @param File $files
     */
    public function removeFile(File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFiles()
    {
        return $this->files;
    }

    function strip_accents($string)
    {
        return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
            'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    }
}
