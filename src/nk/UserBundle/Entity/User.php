<?php

namespace nk\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use nk\ExamBundle\Entity\IgnoredExam;
use Symfony\Component\Validator\Constraints as Assert;
use nk\ExamBundle\Entity\Resource as Resource;

/**
 * User
 *
 * @ORM\Table(name="nk_user")
 * @ORM\Entity()
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
     * Constructor
     */
    public function __construct()
    {
        $this->ignoredExams = new \Doctrine\Common\Collections\ArrayCollection();
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
}
