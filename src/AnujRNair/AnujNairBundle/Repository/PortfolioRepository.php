<?php

namespace AnujRNair\AnujNairBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class PortfolioRepository
 * @package AnujRNair\AnujNairBundle\Repository
 */
class PortfolioRepository extends EntityRepository
{

    /**
     * Get a paginated list of portfolio entries
     * @param int $page
     * @param int $numberPerPage
     * @return Paginator
     */
    public function getPortfolioList($page, $numberPerPage)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                select p
                from AnujNairBundle:Portfolio as p
                where p.deleted = 0
                order by p.dateCreated desc
            ')
            ->setFirstResult(($page - 1) * $numberPerPage)
            ->setMaxResults($numberPerPage);

        return new Paginator($query, false);
    }

    /**
     * Get a paginated list of portfolio entries filtered by a specific tag
     * @param int $tagId
     * @param int $page
     * @param int $numberPerPage
     * @return Paginator
     */
    public function getPortfolioListByTagId($tagId, $page, $numberPerPage)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                select p
                from AnujNairBundle:Portfolio as p
                inner join p.tagMap as tm
                inner join tm.tag as t
                where t.deleted = 0
                and p.deleted = 0
                and t.id = :tagId
                order by p.dateCreated desc
            ')
            ->setParameters(['tagId' => $tagId])
            ->setFirstResult(($page - 1) * $numberPerPage)
            ->setMaxResults($numberPerPage);

        return new Paginator($query, false);
    }

}
