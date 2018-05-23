<?php

namespace AnujRNair\AnujNairBundle\Controller;

use AnujRNair\AnujNairBundle\Entity\Blog;
use AnujRNair\AnujNairBundle\Entity\Comment;
use AnujRNair\AnujNairBundle\Entity\Guest;
use AnujRNair\AnujNairBundle\Entity\Tag;
use AnujRNair\AnujNairBundle\Forms\Blog\CommentType;
use AnujRNair\AnujNairBundle\Helper\PostHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BlogController
 * @package AnujRNair\AnujNairBundle\Controller
 * @Route("/blog")
 */
class BlogController extends Controller
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
                'users' => $this->getUsersForPosts($posts),
                'tags' => $this->getTagsForPosts($posts),
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

        $similarBlogPosts = $em
            ->getRepository('AnujNairBundle:Blog')
            ->getSimilarBlogPosts($id, 1, 20);

        return [
            'json' => json_encode([
                'blog' => $blog,
                'users' => $this->getUsersForPosts([$blog]),
                'tags' => $this->getTagsForPosts([$blog]),
                'similarBlogPosts' => $similarBlogPosts
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
                'users' => $this->getUsersForPosts($posts),
                'tags' => $this->getTagsForPosts($posts),
                'archive' => $archive,
                'tagSummary' => $tagSummary,
                'tagId' => $tagId
            ])
        ];
    }

    /**
     * @Route("/preview", name="_an_blog_preview")
     * @Method({"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response|static
     */
    public function previewPostAction(Request $request)
    {
        $comment = new Comment();
        $commentForm = $this->createForm(new CommentType(), $comment);
        $commentForm->handleRequest($request);

        $errors = [];
        $error = $commentForm->getErrors(true);
        if (count($error) > 0) {
            foreach ($error as $err) {
                $field = $err->getOrigin()->getName();
                if ($field === 'name') {
                    continue;
                }
                $errors[$field][] = $err->getMessage();
            }
        }

        if (count($errors) > 0) {
            return JsonResponse::create(['parsed' => null, 'errors' => $errors]);
        }

        return JsonResponse::create(['parsed' => PostHelper::parseBBCode($comment->getComment())]);
    }

    /**
     * Get an array of tag ids from an array of posts
     * @param Blog[] $posts
     * @return Integer[]
     */
    private function getTagsForPosts($posts)
    {
        $em = $this->getDoctrine()->getManager();

        $tagIds = [];
        $multiTagIds = array_map(function ($post) {
            return $post->getTagIds();
        }, $posts);
        array_walk_recursive($multiTagIds, function ($v) use (&$tagIds) {
            $tagIds[] = $v;
        });

        return $em
            ->getRepository('AnujNairBundle:Tag')
            ->getTagsByIds($tagIds);
    }

    /**
     * Get an array of user ids from an array of posts
     * @param Blog[] $posts
     * @return Integer[]
     */
    private function getUsersForPosts($posts)
    {
        $em = $this->getDoctrine()->getManager();

        $userIds = array_unique(array_map(function ($post) {
            return $post->getUser()->getId();
        }, $posts));

        return $em
            ->getRepository('AnujNairBundle:User')
            ->getUsersByIds($userIds);
    }
}
