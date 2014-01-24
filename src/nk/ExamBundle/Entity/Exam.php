<?php

namespace nk\ExamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Exam
 *
 * @ORM\Table(name="nk_exam")
 * @ORM\Entity(repositoryClass="nk\ExamBundle\Entity\ExamRepository")
 */
class Exam
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
     * @var \DateTime
     *
     * @ORM\Column(name="start_at", type="datetime")
     */
    private $startAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_at", type="datetime")
     */
    private $endAt;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=20)
     */
    private $unit;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=20)
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="groups", type="string", length=255)
     */
    private $groups;

    /**
     * @var Resource
     * @ORM\ManyToOne(targetEntity="Resource", inversedBy="exams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $resource;

    private $documents = array();

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DateTime $endAt
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;
    }

    /**
     * @return \DateTime
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * @param mixed $groups
     */
    public function setGroups($groups)
    {
        $groups = str_replace(
            '\n',
            ', ',
            substr(
                $groups,
                2,
                -4 + strpos(
                    $groups,
                    preg_match('#^[A-Z]{2,4}-[0-9]{4}:#', $this->unit) ?
                        substr($this->unit, 0, strpos($this->unit, ':')):
                        $this->unit
                )
            )
        );

        $groups = preg_replace('#, \(Exported.+#', '', $groups);
        $groups = preg_replace('#, AURION.+#', '', $groups);

        $this->groups = $groups;
    }

    /**
     * @return mixed
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $location = str_replace('\,', ', ', $location);
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param \DateTime $startAt
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;
    }

    /**
     * @return \DateTime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @param mixed $unit
     */
    public function setUnit($unit)
    {
        $unit = substr(
            $unit,
            0,
            strpos($unit, ':CTR') === false ?
                100:
                strpos($unit, ':CTR')
        );

        $this->unit = $unit;
    }

    /**
     * @return mixed
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param array $documents
     */
    public function setDocuments($documents)
    {
        $this->documents = $documents;
    }

    /**
     * @return array
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    public function getLength()
    {
        $diff = $this->startAt->diff($this->endAt);

        return ($diff->h ? $diff->h.'h ' : '').($diff->i ? $diff->i.'m' : '');
    }

    /**
     * @param Resource $resource
     */
    public function setResource($resource)
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