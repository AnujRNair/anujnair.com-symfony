<?php

namespace AnujRNair\AnujNairBundle\Controller;

use AnujRNair\AnujNairBundle\Entity\Blog;
use AnujRNair\AnujNairBundle\Entity\Portfolio;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class BaseController
 * @package AnujRNair\AnujNairBundle\Controller
 */
class BaseController extends Controller
{
    /**
     * Get an array of user ids from an array of posts
     * @param Blog[]|Portfolio[] $objs
     * @return Integer[]
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function getUsersForObj($objs)
    {
        $em = $this->getDoctrine()->getManager();

        $userIds = array_unique(array_map(function ($obj) {
            return $obj->getUser()->getId();
        }, $objs));

        return $em
            ->getRepository('AnujNairBundle:User')
            ->getUsersByIds($userIds);
    }

    /**
     * Get an array of tag ids from an array of posts
     * @param Blog[]|Portfolio[] $objs
     * @return Integer[]
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function getTagsForObj($objs)
    {
        $em = $this->getDoctrine()->getManager();

        $tagIds = [];
        $multiTagIds = array_map(function ($obj) {
            return $obj->getTagIds();
        }, $objs);
        array_walk_recursive($multiTagIds, function ($v) use (&$tagIds) {
            $tagIds[] = $v;
        });

        return $em
            ->getRepository('AnujNairBundle:Tag')
            ->getTagsByIds($tagIds);
    }
}
