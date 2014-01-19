<?php

namespace nk\ExamBundle\Services;


use nk\ExamBundle\Entity\Exam;
use nk\UserBundle\Entity\User;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManager;
use nk\DocumentBundle\Entity\DocumentRepository;

class AdeExplorer
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var DocumentRepository
     */
    private $docRepo;

    private $AdeUrl;

    /**
     * @var User
     */
    private $user;

    function __construct($AdeUrl, SecurityContext $context, EntityManager $em)
    {
        $this->user = $context->getToken()->getUser();
        $this->em = $em;
        $this->docRepo = $this->em->getRepository('nkDocumentBundle:Document');
        $this->AdeUrl = str_replace('{{resources}}', $this->user->getResource()->getCode(), $AdeUrl);
    }

    public function find()
    {
        $data = $this->getCalendar();

        return $this->parseCalendar($data);
    }

    private function getCalendar()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->AdeUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $data = curl_exec($ch);
        curl_close($ch);

        $data = preg_replace('#((\r?\n)|(\r\n?)) #', '', $data);

        return $data;
    }

    private function parseCalendar($cal)
    {
        $events = array();
        $numberOfDocByUnit = $this->docRepo->getNumberOfDocByUnit();

        $separator = "\r\n";
        strtok($cal, $separator);

        while (($line = strtok($separator)) !== false) {
            $line = trim($line);

            if($line == 'BEGIN:VEVENT'){
                $event = new Exam;

                while(($line = strtok($separator)) !== false && $line !== 'END:VEVENT'){
                    $line = trim($line);

                    if($this->isData($line, 'DTSTART')){
                        $event->setStartAt($this->toDate($line));
                    }
                    else if($this->isData($line, 'DTEND')){
                        $event->setEndAt($this->toDate($line));
                    }
                    else if($this->isData($line, 'SUMMARY')){
                        $event->setUnit($this->getData($line));

                        if(isset($numberOfDocByUnit[$event->getUnit()])){
                            $event->setDocuments($this->docRepo->findByUnit(
                                preg_match('#^[A-Z]{2,3}-[0-9]{4}:#', $event->getUnit()) ?
                                    substr($event->getUnit(), 0, strpos($event->getUnit(), ':')):
                                    $event->getUnit()
                            ));
                        }
                    }
                    else if($this->isData($line, 'LOCATION')){
                        $event->setLocation($this->getData($line));
                    }
                    else if($this->isData($line, 'DESCRIPTION')){
                        $event->setGroups($this->getData($line));
                    }
                }

                $events[] = $event;
            }
        }

        usort($events, function($a, $b){
            return ($a->getStartAt() < $b->getStartAt()) ? -1 : 1;
        });

        return $events;
    }

    private function isData($line, $id){
        return strpos($line, "$id:") === 0;
    }

    private function toDate($line)
    {
        $line = preg_replace('#^.+:([0-9]{4})([0-9]{2})([0-9]{2})T([0-9]{2})([0-9]{2})([0-9]{2})Z$#', '$1 $2 $3 $4 $5 $6', $line);
        $date = \DateTime::createFromFormat('Y m d G i s', $line);
        $date->setTime($date->format('H')+1, $date->format('i'));
        return $date;
    }

    private function getData($line)
    {
        return substr($line, strpos($line, ':') +1);
    }
}