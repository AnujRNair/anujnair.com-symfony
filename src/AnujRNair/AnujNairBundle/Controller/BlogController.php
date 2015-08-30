<?php

namespace AnujRNair\AnujNairBundle\Controller;

use AnujRNair\AnujNairBundle\Entity\Blog;
use AnujRNair\AnujNairBundle\Entity\Comment;
use AnujRNair\AnujNairBundle\Entity\Tag;
use AnujRNair\AnujNairBundle\Forms\Blog\CommentType;
use Doctrine\ORM\NoResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BlogController
 * @package AnujRNair\AnujNairBundle\Controller
 * @Route("/blog")
 */
class BlogController extends Controller
{

    /**
     * @Route("/", name="_an_blog_index")
     * @Template("AnujNairBundle:Blog:index.html.twig")
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $page = $request->get('page', 1);
        $noPerPage = min($request->get('noPerPage', 10), 100);

        $em = $this->getDoctrine()->getManager();
        $blogPosts = $em->getRepository('AnujNairBundle:Blog')
            ->getBlogPosts($page, $noPerPage);
        $archive = $em->getRepository('AnujNairBundle:Blog')
            ->getBlogPostsByYearMonth(1, 20);
        $tagSummary = $em->getRepository('AnujNairBundle:Tag')
            ->getTagSummary();

        return [
            'page'       => $page,
            'noPerPage'  => $noPerPage,
            'blogPosts'  => $blogPosts,
            'archive'    => $archive,
            'tagSummary' => $tagSummary
        ];
    }

    /**
     * @Route("/{id}", requirements={"id" : "[\d]+"})
     * @Route("/{id}-", requirements={"id" : "[\d]+"})
     * @Route("/{id}-{title}", name="_an_blog_article", requirements={"id" : "[\d]+", "title" : "[\w\-]+"})
     * @Template("AnujNairBundle:Blog:post.html.twig")
     * @param Request $request
     * @param int $id
     * @param string $title
     * @return array
     */
    public function postAction(Request $request, $id, $title = null)
    {
        $blog = null;
        try {
            $em = $this->getDoctrine()->getManager();
            /** @var Blog $blog */
            $blog = $em->getRepository('AnujNairBundle:Blog')
                ->getBlogById($id);

            // Make sure URL points to correct place for SEO purposes
            if ($title !== $blog->getUrlSafeTitle()) {
                return $this->redirect($this->generateUrl('_an_blog_article', [
                    'id'    => $blog->getId(),
                    'title' => $blog->getUrlSafeTitle()
                ]), 301);
            }
        } catch (NoResultException $e) {
            throw $this->createNotFoundException('I couldn\'t find that blog post!');
        }

        $comment = new Comment();
        $commentForm = $this->createForm(new CommentType(), $comment);
        $commentForm->handleRequest($request);

        // Posting a comment, let's save it!
        if ($commentForm->isValid()) {

        }

        return [
            'blog'        => $blog,
            'commentForm' => $commentForm->createView()
        ];
    }

    /**
     * @Route("/t/{tagId}", requirements={"tagId" : "[\d]+"})
     * @Route("/t/{tagId}-", requirements={"tagId" : "[\d]+"})
     * @Route("/t/{tagId}-{name}", name="_an_blog_tag", requirements={"tagId" : "[\d]+", "name" : "[\w\-]+"})
     * @Template("AnujNairBundle:Blog:index.html.twig")
     * @param Request $request
     * @param int $tagId
     * @param string $name
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function tagAction(Request $request, $tagId, $name = null)
    {
        $page = $request->get('page', 1);
        $noPerPage = min($request->get('noPerPage', 10), 100);

        $em = $this->getDoctrine()->getManager();

        try {
            /** @var Tag $tag */
            $tag = $em->getRepository('AnujNairBundle:Tag')
                ->getTagById($tagId);

            // Make sure URL points to correct place for SEO purposes
            if ($name !== $tag->getUrlSafeName()) {
                return $this->redirect($this->generateUrl('_an_blog_article', [
                    'id'    => $tag->getId(),
                    'title' => $tag->getUrlSafeName()
                ]), 301);
            }

        } catch (NoResultException $e) {
            throw $this->createNotFoundException('I couldn\'t find that tag!');
        }

        $blogPosts = $em->getRepository('AnujNairBundle:Blog')
            ->getBlogPostsByTagId($tag->getId(), $page, $noPerPage);
        $archive = $em->getRepository('AnujNairBundle:Blog')
            ->getBlogPostsByYearMonth(1, 20);
        $tagSummary = $em->getRepository('AnujNairBundle:Tag')
            ->getTagSummary();

        return [
            'page'       => $page,
            'noPerPage'  => $noPerPage,
            'blogPosts'  => $blogPosts,
            'archive'    => $archive,
            'tagSummary' => $tagSummary,
            'tagId'      => $tagId
        ];
    }

}