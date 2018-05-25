<?php

namespace AnujRNair\AnujNairBundle\Controller;

use AnujRNair\AnujNairBundle\Entity\Blog;
use AnujRNair\AnujNairBundle\Entity\Tag;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BlogController
 * @package AnujRNair\AnujNairBundle\Controller
 * @Route("/blog")
 */
class BlogController extends BaseController
{

    /**
     * @Route("/", name="_an_blog_index", defaults={"webpack" = "blog-index"})
     * @Template("AnujNairBundle:Blog:index.html.twig")
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $page = $request->get('page', 1);
        $noPerPage = min($request->get('noPerPage', 10), 100);

        $em = $this->getDoctrine()->getManager();
        $posts = $em
            ->getRepository('AnujNairBundle:Blog')
            ->getBlogPosts($page, $noPerPage);
        $archive = $em
            ->getRepository('AnujNairBundle:Blog')
            ->getBlogPostsByYearMonth(1, 20);
        $tagSummary = $em
            ->getRepository('AnujNairBundle:Tag')
            ->getBlogTagSummary();

        return [
            'json' => json_encode([
                'page' => $page,
                'noPerPage' => $noPerPage,
                'posts' => $posts,
                'users' => $this->getUsersForObj($posts),
                'tags' => $this->getTagsForObj($posts),
                'archive' => $archive,
                'tagSummary' => $tagSummary
            ])
        ];
    }

    /**
     * @Route("/{id}", requirements={"id" : "[\d]+"}, defaults={"webpack" = "blog-post"})
     * @Route("/{id}-", requirements={"id" : "[\d]+"}, defaults={"webpack" = "blog-post"})
     * @Route("/{id}-{title}", name="_an_blog_article", requirements={"id" : "[\d]+"}, defaults={"webpack" = "blog-post"})
     * @Template("AnujNairBundle:Blog:post.html.twig")
     * @param Request $request
     * @param int $id
     * @param string $title
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postAction(Request $request, $id, $title = null)
    {
        $blog = null;
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        try {
            /** @var Blog $blog */
            $blog = $em
                ->getRepository('AnujNairBundle:Blog')
                ->getBlogById($id);

            // Make sure URL points to correct place for SEO purposes
            if ($title !== $blog->getUrlSafeTitle()) {
                return $this->redirect($this->generateUrl('_an_blog_article', [
                    'id' => $blog->getId(),
                    'title' => $blog->getUrlSafeTitle()
                ]), 301);
            }

            $blog->setWantLong(true);
        } catch (NoResultException $e) {
            throw $this->createNotFoundException('The blog post doesn\'t exist.');
        }

        $similar = $em
            ->getRepository('AnujNairBundle:Blog')
            ->getSimilarBlogPosts($id, 1, 20);

        return [
            'json' => json_encode([
                'blog' => $blog,
                'users' => $this->getUsersForObj([$blog]),
                'tags' => $this->getTagsForObj([$blog]),
                'similar' => $similar
            ]),
            'blog' => $blog
        ];
    }

    /**
     * @Route("/t/{tagId}", requirements={"tagId" : "[\d]+"}, defaults={"webpack" = "blog-tag"})
     * @Route("/t/{tagId}-", requirements={"tagId" : "[\d]+"}, defaults={"webpack" = "blog-tag"})
     * @Route("/t/{tagId}-{name}", name="_an_blog_tag", requirements={"tagId" : "[\d]+"}, defaults={"webpack" = "blog-tag"})
     * @Template("AnujNairBundle:Blog:index.html.twig")
     * @param Request $request
     * @param int $tagId
     * @param string $name
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function tagAction(Request $request, $tagId, $name = null)
    {
        $page = $request->get('page', 1);
        $noPerPage = min($request->get('noPerPage', 10), 100);

        $em = $this->getDoctrine()->getManager();

        try {
            /** @var Tag $tag */
            $tag = $em
                ->getRepository('AnujNairBundle:Tag')
                ->getTagById($tagId);

            // Make sure URL points to correct place for SEO purposes
            if ($name !== $tag->getUrlSafeName()) {
                return $this->redirect($this->generateUrl('_an_blog_tag', [
                    'tagId' => $tag->getId(),
                    'name' => $tag->getUrlSafeName()
                ]), 301);
            }

        } catch (NoResultException $e) {
            throw $this->createNotFoundException('I couldn\'t find that tag!');
        }

        $posts = $em
            ->getRepository('AnujNairBundle:Blog')
            ->getBlogPostsByTagId($tag->getId(), $page, $noPerPage);
        $archive = $em
            ->getRepository('AnujNairBundle:Blog')
            ->getBlogPostsByYearMonth(1, 20);
        $tagSummary = $em
            ->getRepository('AnujNairBundle:Tag')
            ->getBlogTagSummary();

        return [
            'json' => json_encode([
                'page' => $page,
                'noPerPage' => $noPerPage,
                'posts' => $posts,
                'users' => $this->getUsersForObj($posts),
                'tags' => $this->getTagsForObj($posts),
                'archive' => $archive,
                'tagSummary' => $tagSummary,
                'tagId' => (int)$tagId
            ])
        ];
    }
}
