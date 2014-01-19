<?php

namespace nk\ExamBundle\Entity;


class Exam
{
    /**
     * @var \DateTime
     */
    private $startAt;

    /**
     * @var \DateTime
     */
    private $endAt;

    private $unit;

    private $location;

    private $groups;

    private $documents = array();

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
                    preg_match('#^[A-Z]{2,3}-[0-9]{4}:#', $this->unit) ?
                        substr($this->unit, 0, strpos($this->unit, ':')):
                        $this->unit
                )
            )
        );

        $groups = preg_replace('#, \(Exported.+#', '', $groups);

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
}