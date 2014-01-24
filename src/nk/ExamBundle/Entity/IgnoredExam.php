<?php

namespace nk\ExamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use nk\UserBundle\Entity\User;

/**
 * IgnoredExam
 *
 * @ORM\Table(name="nk_ignored_exam")
 * @ORM\Entity(repositoryClass="nk\ExamBundle\Entity\IgnoredExamRepository")
 */
class IgnoredExam
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
     * @ORM\Column(name="unit", type="string", length=20)
     */
    private $unit;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="\nk\UserBundle\Entity\User", inversedBy="ignoredExams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct($unit = null)
    {
        $this->unit = $unit;
    }

    public function __toString()
    {
        return $this->unit;
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
     * Set unit
     *
     * @param string $unit
     * @return IgnoredExam
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return string 
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
