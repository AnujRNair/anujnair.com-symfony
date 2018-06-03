<?php

namespace AnujRNair\AnujNairBundle\Repository;

use AnujRNair\AnujNairBundle\Entity\Tag;
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
     * @return Tag
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
     * Get tags by their ids
     * @param $ids[]
     * @return Tag[]
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTagsByIds($ids)
    {
        return $this->getEntityManager()
            ->createQuery('
                select
                    t
                from AnujNairBundle:Tag as t
                where t.id in (:ids)
            ')
            ->setParameters(['ids' => $ids])
            ->getResult();
    }

    /**
     * Get a summary of tags for blog posts
     * @return array
     */
    public function getBlogTagSummary()
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

    /**
     * Get a summary of tags for portfolio sites
     * @param int $limit
     * @return array
     */
    public function getPortfolioTagSummary($limit)
    {
        return $this->getEntityManager()
            ->createQuery('
                select
                    t.id,
                    t.name,
                    count(t.id) as tagCount
                from AnujNairBundle:Tag as t
                inner join t.tagMap as tm
                inner join tm.portfolio as p
                where t.deleted = 0
                and p.deleted = 0
                group by
                    t.id,
                    t.name
                order by tagCount desc
            ')
            ->setMaxResults($limit)
            ->getResult();
    }

}
