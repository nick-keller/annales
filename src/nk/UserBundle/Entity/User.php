<?php

namespace nk\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
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
     * @var Resource
     * @ORM\ManyToOne(targetEntity="\nk\ExamBundle\Entity\Resource", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $resource;


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
}
