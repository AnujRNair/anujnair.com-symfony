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

}
