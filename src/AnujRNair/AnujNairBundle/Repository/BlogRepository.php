<?php

namespace AnujRNair\AnujNairBundle\Repository;

use AnujRNair\AnujNairBundle\Entity\Blog;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BlogRepository extends EntityRepository
{

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