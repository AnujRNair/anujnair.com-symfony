<?php

namespace AnujRNair\AnujNairBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * GuestRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GuestRepository extends EntityRepository
{

    public function getGuestByNameIpUserAgent($name, $ip, $userAgent)
    {
        return $this->getEntityManager()
            ->createQuery('
                select g
                from AnujNairBundle:Guest as g
                where g.name = :name
                and (
                    g.ipCreated = :ip
                    or g.ipLastVisited = :ip
                )
                and g.userAgent = :userAgent
            ')
            ->setParameters([
                'name'      => $name,
                'ip'        => ip2long($ip),
                'userAgent' => $userAgent,
            ])
            ->getOneOrNullResult();
    }

}
