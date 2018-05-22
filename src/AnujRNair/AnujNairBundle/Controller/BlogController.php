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

        $userIds = array_unique(array_map(function($post) {
            return $post->getUser()->getId();
        }, $posts));
        $users = $em
            ->getRepository('AnujNairBundle:User')
            ->getUsersByIds($userIds);

        $tagIds = [];
        $multiTagIds = array_map(function($post) {
            return $post->getTagIds();
        }, $posts);
        array_walk_recursive($multiTagIds, function($v) use (&$tagIds) {
            $tagIds[] = $v;
        });
        $tags = $em
            ->getRepository('AnujNairBundle:Tag')
            ->getTagsByIds($tagIds);

        return [
            'json' => json_encode([
                'page' => $page,
                'noPerPage' => $noPerPage,
                'posts' => $posts,
                'users' => $users,
                'tags' => $tags,
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
        } catch (NoResultException $e) {
            throw $this->createNotFoundException('The blog post doesn\'t exist.');
        }

        $actionUrl = $this->generateUrl('_an_blog_article', [
            'id' => $blog->getId(),
            'title' => $blog->getUrlSafeTitle()
        ]);

        $comment = new Comment();
        $commentForm = $this->createForm(new CommentType($actionUrl . '#post-comment'), $comment);
        $commentForm->handleRequest($request);

        // Posting a comment, let's save it!
        if ($commentForm->isValid()) {
            // Get details that we will need
            $ip = $request->getClientIp();
            $userAgent = substr($request->headers->get('User-Agent'), 0, 255);
            $datetime = new \DateTime();
            $guest = $comment->getGuest();

            // Try and find an existing guest
            /** @var Guest $existingGuest */
            $existingGuest = $em
                ->getRepository('AnujNairBundle:Guest')
                ->getGuestByNameIpUserAgent($guest->getName(), $ip, $userAgent);

            if ($existingGuest !== null) {
                $guest = $existingGuest;
            } else {
                $guest
                    ->setDateCreated($datetime)
                    ->setIpCreated($ip)
                    ->setUserAgent($userAgent);
            }
            $guest
                ->setDateLastVisited($datetime)
                ->setIpLastVisited($ip);

            // Save to the database
            $comment->setBlog($blog);
            $comment->setGuest($guest);
            $em->persist($comment);
            $em->persist($guest);
            $em->flush();

            // Send email
            /** @var \Swift_Mime_Message $message */
            $message = \Swift_Message::newInstance()
                ->setSubject('AnujNair.com comment has been posted')
                ->addFrom($this->container->getParameter('mailer_to'))
                ->addTo($this->container->getParameter('mailer_to'))
                ->addReplyTo($this->container->getParameter('mailer_to'))
                ->setBody(
                    $this->renderView('AnujNairBundle:Email:commentEmail.html.twig', [
                        'comment' => $comment
                    ]),
                    'text/html'
                );
            $this->get('mailer')->send($message);

            return $this->redirect($actionUrl . '#comment' . $comment->getId());
        }

        $similarBlogPosts = $em
            ->getRepository('AnujNairBundle:Blog')
            ->getSimilarBlogPosts($id, 1, 20);

        return [
            'json' => json_encode([
                'blog' => $blog,
                'similarBlogPosts' => $similarBlogPosts
            ]),
            'commentForm' => $commentForm->createView()
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

        $blogPosts = $em
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
                'blogPosts' => $blogPosts,
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

}
