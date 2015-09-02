<?php

namespace AnujRNair\AnujNairBundle\Repository;

use AnujRNair\AnujNairBundle\Entity\Blog;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class BlogRepository
 * @package AnujRNair\AnujNairBundle\Repository
 */
class BlogRepository extends EntityRepository
{

    /**
     * Get a single undeleted blog
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getBlogById($id)
    {
        return $this->getEntityManager()
            ->createQuery('
                select b
                from AnujNairBundle:Blog as b
                where b.deleted = 0
                and b.id = :id
            ')
            ->setParameters(['id' => $id])
            ->getSingleResult();
    }

    /**
     * Get a paginated list of undeleted blogs
     * @param $page
     * @param $numberPerPage
     * @return Paginator
     */
    public function getBlogPosts($page, $numberPerPage)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                select b
                from AnujNairBundle:Blog as b
                where b.deleted = 0
                order by b.datePublished desc
            ')
            ->setFirstResult(($page - 1) * $numberPerPage)
            ->setMaxResults($numberPerPage);

        return new Paginator($query, false);
    }

    /**
     * Return a multi dimensional array of blog posts by year and month
     * @param $page
     * @param $numberPerPage
     * @return array
     */
    public function getBlogPostsByYearMonth($page, $numberPerPage)
    {
        $blogPosts = $this->getEntityManager()
            ->createQuery('
                select b
                from AnujNairBundle:Blog as b
                where b.deleted = 0
                order by b.datePublished desc
            ')
            ->setFirstResult(($page - 1) * $numberPerPage)
            ->setMaxResults($numberPerPage)
            ->getResult();

        $results = [];
        if (count($blogPosts) > 0) {
            foreach ($blogPosts as $post) {
                /** @var Blog $post */
                $results[$post->getDatePublished()->format('Y')][$post->getDatePublished()->format('F')][] = $post;
            }
        }

        return $results;
    }

    /**
     * Get undeleted blog posts by a tag id
     * @param $tagId
     * @param $page
     * @param $numberPerPage
     * @return Paginator
     */
    public function getBlogPostsByTagId($tagId, $page, $numberPerPage)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                select b
                from AnujNairBundle:Blog as b
                inner join b.tagMap as tm
                inner join tm.tag as t
                where t.deleted = 0
                and b.deleted = 0
                and t.id = :tagId
                order by b.datePublished desc
            ')
            ->setParameters(['tagId' => $tagId])
            ->setFirstResult(($page - 1) * $numberPerPage)
            ->setMaxResults($numberPerPage);

        return new Paginator($query, false);
    }

}