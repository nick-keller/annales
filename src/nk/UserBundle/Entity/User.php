<?php

namespace nk\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use nk\DocumentBundle\Entity\Document;
use nk\ExamBundle\Entity\IgnoredExam;
use nk\FolderBundle\Entity\Folder;
use Symfony\Component\Validator\Constraints as Assert;
use nk\ExamBundle\Entity\Resource as Resource;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="nk_user")
 * @ORM\Entity(repositoryClass="nk\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Regex(
     *      pattern="/^[a-z\.-]+@edu\.esiee\.fr$/",
     *      message="Vous devez avoir un mail @edu.esiee.fr"
     *      )
     */
    protected $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="week_before_exam", type="integer")
     * @Assert\NotBlank()
     */
    private $weekBeforeExam = 1;

    /**
     * @var Resource
     * @ORM\ManyToOne(targetEntity="\nk\ExamBundle\Entity\Resource", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $resource;

    /**
     * @ORM\OneToMany(targetEntity="nk\ExamBundle\Entity\IgnoredExam", mappedBy="user")
     */
    protected $ignoredExams;

    /**
     * @ORM\OneToMany(targetEntity="nk\DocumentBundle\Entity\Document", mappedBy="author")
     */
    protected $documents;

    /**
     * @ORM\OneToMany(targetEntity="nk\FolderBundle\Entity\Folder", mappedBy="author")
     */
    protected $folders;

    /**
     * @ORM\ManyToMany(targetEntity="nk\FolderBundle\Entity\Folder", mappedBy="users")
     **/
    private $folderCollection;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->ignoredExams = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->folders = new ArrayCollection();
        $this->folderCollection = new ArrayCollection();
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
     * @param $resource
     */
    public function setResource(Resource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param int $weekBeforeExam
     */
    public function setWeekBeforeExam($weekBeforeExam)
    {
        $this->weekBeforeExam = $weekBeforeExam;
    }

    /**
     * @return int
     */
    public function getWeekBeforeExam()
    {
        return $this->weekBeforeExam;
    }

    /**
     * Add ignoredExams
     *
     * @param IgnoredExam $ignoredExams
     * @return User
     */
    public function addIgnoredExam(IgnoredExam $ignoredExams)
    {
        $this->ignoredExams[] = $ignoredExams;

        return $this;
    }

    /**
     * Remove ignoredExams
     *
     * @param IgnoredExam $ignoredExams
     */
    public function removeIgnoredExam(IgnoredExam $ignoredExams)
    {
        $this->ignoredExams->removeElement($ignoredExams);
    }

    /**
     * Get ignoredExams
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIgnoredExams()
    {
        return $this->ignoredExams;
    }

    public function getIgnoredUnits()
    {
        $units = array();
        foreach($this->ignoredExams as $exam)
            $units[] = $exam->getUnit();
        return $units;
    }

    /**
     * Add documents
     *
     * @param Document $documents
     * @return User
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
     * Add folderCollection
     *
     * @param Folder $folderCollection
     * @return User
     */
    public function addFolderCollection(Folder $folderCollection)
    {
        $this->folderCollection[] = $folderCollection;

        return $this;
    }

    /**
     * Remove folderCollection
     *
     * @param Folder $folderCollection
     */
    public function removeFolderCollection(Folder $folderCollection)
    {
        $this->folderCollection->removeElement($folderCollection);
    }

    /**
     * Get folderCollection
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFolderCollection()
    {
        return $this->folderCollection;
    }

    /**
     * Add folders
     *
     * @param Folder $folders
     * @return User
     */
    public function addFolder(Folder $folders)
    {
        $this->folders[] = $folders;

        return $this;
    }

    /**
     * Remove folders
     *
     * @param Folder $folders
     */
    public function removeFolder(Folder $folders)
    {
        $this->folders->removeElement($folders);
    }

    /**
     * Get folders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFolders()
    {
        return $this->folders;
    }
}
