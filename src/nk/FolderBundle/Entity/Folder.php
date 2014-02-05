<?php

namespace nk\FolderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use nk\DocumentBundle\Entity\Document;
use Symfony\Component\Validator\Constraints as Assert;
use nk\UserBundle\Entity\User as User;

/**
 * Folder
 *
 * @ORM\Table(name="nk_folder")
 * @ORM\Entity(repositoryClass="nk\FolderBundle\Entity\FolderRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Folder
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
     * @ORM\Column(name="name", type="string", length=200)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=204)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=10, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="class", type="string", length=10, nullable=true)
     */
    private $class;

    /**
     * @var string
     *
     * @ORM\Column(name="field", type="string", length=30, nullable=true)
     */
    private $field;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=10, nullable=true)
     */
    private $unit;

    /**
     * @var string
     *
     * @ORM\Column(name="year", type="string", length=10, nullable=true)
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="teacher", type="string", length=100, nullable=true)
     */
    private $teacher;

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
     * @ORM\ManyToOne(targetEntity="nk\UserBundle\Entity\User", inversedBy="folders")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     * @Gedmo\Blameable(on="create")
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity="nk\UserBundle\Entity\User", inversedBy="folderCollection", cascade={"persist"})
     * @ORM\JoinTable(name="nk_user_folder")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="nk\DocumentBundle\Entity\Document", inversedBy="folders", cascade={"persist"})
     * @ORM\JoinTable(name="nk_document_folder")
     */
    private $documents;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->name = "Nouvelle compil";
    }

    public function __toString()
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     * @return Folder
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param User $author
     */
    public function setAuthor($author)
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
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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
     * Add users
     *
     * @param User $users
     * @return Folder
     */
    public function addUser(User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param User $users
     */
    public function removeUser(User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add documents
     *
     * @param Document $documents
     * @return Folder
     */
    public function addDocument(Document $documents)
    {
        $this->documents[] = $documents;

        return $this;
    }

    /**
     * Remove documents
     *
     * @param Document $documents
     */
    public function removeDocument(Document $documents)
    {
        $this->documents->removeElement($documents);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param string $teacher
     */
    public function setTeacher($teacher)
    {
        $this->teacher = $teacher;
    }

    /**
     * @return string
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param string $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function checkCommonData()
    {
        $fields = array('type', 'class', 'field', 'unit', 'year', 'teacher');

        foreach($fields as $field){
            $data = null;

            foreach($this->documents as $document){
                $value = $document->{'get'.ucfirst($field)}();

                if($data === null || $data === $value)
                    $data = $value;
                else{
                    $data = null;
                    break;
                }
            }

            $this->$field = $data;
        }
    }
}
