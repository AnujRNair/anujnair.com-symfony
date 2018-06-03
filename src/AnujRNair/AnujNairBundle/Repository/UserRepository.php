<?php

namespace AnujRNair\AnujNairBundle\Repository;

use AnujRNair\AnujNairBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @package AnujRNair\AnujNairBundle\Repository
 */
class UserRepository extends EntityRepository
{
    /**
     * Get a single user
     * @param int $id
     * @return User
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUserById($id)
    {
        return $this->getEntityManager()
            ->createQuery('
                select u
                from AnujNairBundle:User as u
                where u.id = :id
            ')
            ->setParameters(['id' => $id])
            ->getSingleResult();
    }

    /**
     * Get a list of users
     * @param int[] $ids
     * @return User[]
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUsersByIds($ids)
    {
        return $this->getEntityManager()
            ->createQuery('
                select u
                from AnujNairBundle:User as u
                where u.id in (:ids)
            ')
            ->setParameters(['ids' => $ids])
            ->getResult();
    }
}
