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
        $blogPosts = $em
            ->getRepository('AnujNairBundle:Blog')
            ->getBlogPosts($page, $noPerPage);
        $archive = $em
            ->getRepository('AnujNairBundle:Blog')
            ->getBlogPostsByYearMonth(1, 20);
        $tagSummary = $em
            ->getRepository('AnujNairBundle:Tag')
            ->getBlogTagSummary();

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
     * @Route("/{id}-{title}", name="_an_blog_article", requirements={"id" : "[\d]+"})
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
                    'id'    => $blog->getId(),
                    'title' => $blog->getUrlSafeTitle()
                ]), 301);
            }
        } catch (NoResultException $e) {
            throw $this->createNotFoundException('The blog post doesn\'t exist.');
        }

        $actionUrl = $this->generateUrl('_an_blog_article', [
            'id'    => $blog->getId(),
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
            'blog'             => $blog,
            'similarBlogPosts' => $similarBlogPosts,
            'commentForm'      => $commentForm->createView()
        ];
    }

    /**
     * @Route("/t/{tagId}", requirements={"tagId" : "[\d]+"})
     * @Route("/t/{tagId}-", requirements={"tagId" : "[\d]+"})
     * @Route("/t/{tagId}-{name}", name="_an_blog_tag", requirements={"tagId" : "[\d]+"})
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
                    'name'  => $tag->getUrlSafeName()
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
            'page'       => $page,
            'noPerPage'  => $noPerPage,
            'blogPosts'  => $blogPosts,
            'archive'    => $archive,
            'tagSummary' => $tagSummary,
            'tagId'      => $tagId
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

        // Posting the comment, parse and return!
        if ($commentForm->isValid()) {
            return JsonResponse::create(['parsed' => PostHelper::parseBBCode($comment->getComment())]);
        } else {
            $errors = [];
            foreach ($commentForm->getErrors(true) as $error) {
                $field = $error->getOrigin()->getName();
                if ($field === 'name') {
                    continue;
                }
                $errors[$field][] = $error->getMessage();
            }
            return JsonResponse::create(['parsed' => null, 'errors' => $errors]);
        }
    }

}