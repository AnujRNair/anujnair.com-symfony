<?php

namespace AnujRNair\AnujNairBundle\Repository;

use AnujRNair\AnujNairBundle\Entity\Portfolio;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class PortfolioRepository
 * @package AnujRNair\AnujNairBundle\Repository
 */
class PortfolioRepository extends EntityRepository
{

    /**
     * Get a portfolio article by ID
     * @param int $id
     * @return Portfolio
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getPortfolioById($id)
    {
        return $this->getEntityManager()
            ->createQuery('
                select p
                from AnujNairBundle:Portfolio as p
                where p.id = :id
                and p.deleted = 0
            ')
            ->setParameters(['id' => $id])
            ->getSingleResult();
    }

    /**
     * Get a paginated list of portfolio entries
     * @param int $page
     * @param int $numberPerPage
     * @return Paginator|Portfolio[]
     */
    public function getPortfolioList($page, $numberPerPage)
    {
        return $this->getEntityManager()
            ->createQuery('
                select p
                from AnujNairBundle:Portfolio as p
                where p . deleted = 0
                order by p . dateCreated desc
            ')
            ->setFirstResult(($page - 1) * $numberPerPage)
            ->setMaxResults($numberPerPage)
            ->getResult();
    }

    /**
     * Get a paginated list of portfolio entries filtered by a specific tag
     * @param int $tagId
     * @param int $page
     * @param int $numberPerPage
     * @return Paginator|Portfolio[]
     */
    public function getPortfolioListByTagId($tagId, $page, $numberPerPage)
    {
        return $this->getEntityManager()
            ->createQuery('
                select p
                from AnujNairBundle:Portfolio as p
                inner join p . tagMap as tm
                inner join tm . tag as t
                where t . deleted = 0
                and p . deleted = 0
                and t . id = :tagId
                order by p . dateCreated desc
            ')
            ->setParameters(['tagId' => $tagId])
            ->setFirstResult(($page - 1) * $numberPerPage)
            ->setMaxResults($numberPerPage)
            ->getResult();
    }

    /**
     * @param int $portfolioId
     * @param int $page
     * @param int $numberPerPage
     * @return array
     */
    public function getSimilarPortfolioArticles($portfolioId, $page, $numberPerPage)
    {
        $articles = $this->getEntityManager()
            ->createQuery('
                select
                    partial p.{id, name, image},
                    count(p.id) as tagCount
                from AnujNairBundle:Portfolio as p
                left join p.tagMap as tm
                left join tm.tag as t
                where t.id in (
                    select tInner.id
                    from AnujNairBundle:Tag as tInner
                    inner join tInner.tagMap as tmInner
                    inner join tmInner.portfolio as pInner
                    where pInner.id = :portfolioId
                    and tInner.deleted = 0
                    and pInner.deleted = 0
                )
                and p.id <> :portfolioId
                and p.deleted = 0
                and t.deleted = 0
                group by p
                order by
                    tagCount desc,
                    p.name asc
            ')
            ->setParameters(['portfolioId' => $portfolioId])
            ->setFirstResult(($page - 1) * $numberPerPage)
            ->setMaxResults($numberPerPage)
            ->getResult();

        $results = [];
        if (count($articles) > 0) {
            $maxTagCount = $articles[0]['tagCount'];
            foreach ($articles as $articleArray) {
                if ($articleArray['tagCount'] === $maxTagCount) {
                    $results['Similar Work'][] = $articleArray[0];
                } else {
                    $results['Other Work Of Interest'][] = $articleArray[0];
                }
            }
        }

        return $results;
    }

}
