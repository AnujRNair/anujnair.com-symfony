<?php

namespace AnujRNair\AnujNairBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class TagRepository
 * @package AnujRNair\AnujNairBundle\Repository
 */
class TagRepository extends EntityRepository
{

    /**
     * Get a tag by it's id
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTagById($id)
    {
        return $this->getEntityManager()
            ->createQuery('
                select
                    t
                from AnujNairBundle:Tag as t
                where t.id = :id
            ')
            ->setParameters(['id' => $id])
            ->getSingleResult();
    }

    /**
     * Get a tags summary
     * @return array
     */
    public function getTagSummary()
    {
        return $this->getEntityManager()
            ->createQuery('
                select
                    t.id,
                    t.name,
                    count(t.id) as tagCount
                from AnujNairBundle:Tag as t
                inner join t.tagMap as tm
                inner join tm.blog as b
                where t.deleted = 0
                and b.deleted = 0
                group by
                    t.id,
                    t.name
                order by t.name asc
            ')
            ->getResult();
    }

}
