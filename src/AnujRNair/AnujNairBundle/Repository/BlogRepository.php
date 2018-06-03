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
     * @param int $id
     * @return Blog
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
     * @param int $page
     * @param int $numberPerPage
     * @return Blog[]
     */
    public function getBlogPosts($page, $numberPerPage)
    {
        return $this->getEntityManager()
            ->createQuery('
                select b
                from AnujNairBundle:Blog as b
                where b.deleted = 0
                order by b.datePublished desc
            ')
            ->setFirstResult(($page - 1) * $numberPerPage)
            ->setMaxResults($numberPerPage)
            ->getResult();
    }

    /**
     * Counts the number of blog posts available
     * @return string
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function countBlogPosts()
    {
        return $this->getEntityManager()
            ->createQuery('
                select count(b)
                from AnujNairBundle:Blog as b
                where b.deleted = 0
                order by b.datePublished desc
            ')
            ->getSingleScalarResult();
    }

    /**
     * Return a multi dimensional array of blog posts by year and month
     * @param int $page
     * @param int $numberPerPage
     * @return array
     */
    public function getBlogPostsByYearMonth($page, $numberPerPage)
    {
        $blogPosts = $this->getEntityManager()
            ->createQuery('
                select 
                  partial b.{id, title, datePublished}
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
                $results[$post->getDatePublished('Y')][$post->getDatePublished('F')][] = $post;
            }
        }

        return $results;
    }

    /**
     * Get undeleted blog posts by a tag id
     * @param int $tagId
     * @param int $page
     * @param int $numberPerPage
     * @return Paginator|Blog[]
     */
    public function getBlogPostsByTagId($tagId, $page, $numberPerPage)
    {
        return $this->getEntityManager()
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
            ->setMaxResults($numberPerPage)
            ->getResult();
    }

    /**
     * Count undeleted blog posts by a tag id
     * @param int $tagId
     * @return string
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function countBlogPostsByTagId($tagId)
    {
        return $this->getEntityManager()
            ->createQuery('
                select count(b)
                from AnujNairBundle:Blog as b
                inner join b.tagMap as tm
                inner join tm.tag as t
                where t.deleted = 0
                and b.deleted = 0
                and t.id = :tagId
                order by b.datePublished desc
            ')
            ->setParameters(['tagId' => $tagId])
            ->getSingleScalarResult();
    }

    /**
     * @param int $blogId
     * @param int $page
     * @param int $numberPerPage
     * @return array
     */
    public function getSimilarBlogPosts($blogId, $page, $numberPerPage)
    {
        $blogPosts = $this->getEntityManager()
            ->createQuery('
                select
                    partial b.{id, title, datePublished},
                    count(b.id) as tagCount
                from AnujNairBundle:Blog as b
                left join b.tagMap as tm
                left join tm.tag as t
                where t.id in (
                    select tInner.id
                    from AnujNairBundle:Tag as tInner
                    inner join tInner.tagMap as tmInner
                    inner join tmInner.blog as bInner
                    where bInner.id = :blogId
                    and tInner.deleted = 0
                    and bInner.deleted = 0
                )
                and b.id <> :blogId
                and b.deleted = 0
                and t.deleted = 0
                group by b
                order by
                    tagCount desc,
                    b.title asc
            ')
            ->setParameters(['blogId' => $blogId])
            ->setFirstResult(($page - 1) * $numberPerPage)
            ->setMaxResults($numberPerPage)
            ->getResult();

        $results = [];
        if (count($blogPosts) > 0) {
            $maxTagCount = $blogPosts[0]['tagCount'];
            foreach ($blogPosts as $postArray) {
                if ($postArray['tagCount'] === $maxTagCount) {
                    $results['Similar Blog Posts'][] = $postArray[0];
                } else {
                    $results['Extra Reading'][] = $postArray[0];
                }
            }
        }

        return $results;
    }

}
