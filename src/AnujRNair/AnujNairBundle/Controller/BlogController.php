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
     * @throws NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function indexAction(Request $request)
    {
        $page = $request->get('page', 1);
        $noPerPage = min($request->get('noPerPage', 10), 100);

        $em = $this->getDoctrine()->getManager();
        $posts = $em
            ->getRepository('AnujNairBundle:Blog')
            ->getBlogPosts($page, $noPerPage);
        $number = $em
            ->getRepository('AnujNairBundle:Blog')
            ->countBlogPosts();
        $archive = $em
            ->getRepository('AnujNairBundle:Blog')
            ->getBlogPostsByYearMonth(1, 20);
        $tagSummary = $em
            ->getRepository('AnujNairBundle:Tag')
            ->getBlogTagSummary();

        return [
            'json' => json_encode([
                'count' => (int)$number,
                'page' => (int)$page,
                'noPerPage' => (int)$noPerPage,
                'posts' => $posts,
                'users' => $this->getUsersForObj($posts),
                'tags' => $this->getTagsForObj($posts),
                'archive' => $archive,
                'tagSummary' => $tagSummary
            ])
        ];
    }

    /**
     * @Route("/{id}", requirements={"id" : "[\d]+"}, defaults={"webpack" = "blog-article"})
     * @Route("/{id}-", requirements={"id" : "[\d]+"}, defaults={"webpack" = "blog-article"})
     * @Route("/{id}-{title}", name="_an_blog_article", requirements={"id" : "[\d]+"}, defaults={"webpack" = "blog-article"})
     * @Template("AnujNairBundle:Blog:article.html.twig")
     * @param Request $request
     * @param int $id
     * @param string $title
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     */
    public function articleAction(Request $request, $id, $title = null)
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
     * @throws \Doctrine\ORM\Query\QueryException
     * @throws NoResultException
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
        $count = $em
            ->getRepository('AnujNairBundle:Blog')
            ->countBlogPostsByTagId($tag->getId());
        $archive = $em
            ->getRepository('AnujNairBundle:Blog')
            ->getBlogPostsByYearMonth(1, 20);
        $tagSummary = $em
            ->getRepository('AnujNairBundle:Tag')
            ->getBlogTagSummary();

        return [
            'json' => json_encode([
                'count' => (int)$count,
                'page' => (int)$page,
                'noPerPage' => (int)$noPerPage,
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
