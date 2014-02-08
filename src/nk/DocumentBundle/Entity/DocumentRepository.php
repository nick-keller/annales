<?php

namespace nk\DocumentBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use nk\UserBundle\Entity\User;

/**
 * DocumentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DocumentRepository extends EntityRepository
{
    public function findDistinct($field)
    {
        $result = $this->createQueryBuilder('d')
            ->select("DISTINCT d.$field AS val")
            ->orderBy('val')
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);

        return array_map(function($line){ return $line['val']; }, $result);
    }

    public function searchQuery(array $mappedQuery)
    {
        $qb = $this->createQueryBuilder('d');

        foreach($mappedQuery as $field => $data){
            if($field == 'subject'){
                foreach($data as $id => $keyword)
                    $qb->andWhere("d.$field LIKE :keyword$id")
                        ->setParameter("keyword$id", "%$keyword%");

            }else{
                $qb->andWhere("d.$field IN (:$field)")
                    ->setParameter($field, $data);
            }
        }

        return $qb;
    }

    public function search(array $mappedQuery)
    {
        return $this->searchQuery($mappedQuery)->getQuery()->getResult();
    }

    public function findFieldsFromClass($class)
    {
        return $this->createQueryBuilder('d')
            ->select("d.field name, COUNT(d.id) total")
            ->orderBy('d.field')
            ->groupBy('d.field')
            ->where('d.class = :class')
            ->setParameter('class', $class)
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
    }

    public function getNumberOfDocByUnit()
    {
        $units = array();

        $result = $this->createQueryBuilder('d')
            ->select("d.unit, COUNT(d.id) total")
            ->groupBy('d.unit')
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);

        foreach($result as $unit)
            $units[$unit['unit']] = $unit['total'];

        return$units;
    }

    public function standardize(Standardize $s)
    {
        $this->createQueryBuilder('d')
            ->update()
            ->set("d.".$s->getField(), ':to')
            ->where("d.".$s->getField().' = :from')
            ->setParameter('from', $s->getFrom())
            ->setParameter('to', $s->getTo())
            ->getQuery()
            ->execute();
    }

    public function queryLatestOfUser(User $user)
    {
        return $this->createQueryBuilder('d')
            ->where('d.author = :user')
            ->setParameter('user', $user)
            ->orderBy('d.createdAt', 'DESC');
    }
}
