<?php

namespace AnujRNair\AnujNairBundle\Controller;

use AnujRNair\AnujNairBundle\Entity\Portfolio;
use AnujRNair\AnujNairBundle\Entity\Tag;
use Doctrine\ORM\NoResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PortfolioController
 * @package AnujRNair\AnujNairBundle\Controller
 * @Route("/portfolio")
 */
class PortfolioController extends BaseController
{

    /**
     * @Route("/", name="_an_portfolio_index", defaults={"webpack" = "portfolio-index"})
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em
            ->getRepository('AnujNairBundle:Portfolio')
            ->getPortfolioList(1, 50);
        $tagSummary = $em
            ->getRepository('AnujNairBundle:Tag')
            ->getPortfolioTagSummary(20);

        return [
            'json' => json_encode([
                'articles' => $articles,
                'tags' => $this->getTagsForObj($articles),
                'tagSummary' => $tagSummary
            ])
        ];
    }

    /**
     * @Route("/{id}", requirements={"id" : "[\d]+"}, defaults={"webpack" = "portfolio-article"})
     * @Route("/{id}-", requirements={"id" : "[\d]+"}, defaults={"webpack" = "portfolio-article"})
     * @Route("/{id}-{name}", name="_an_portfolio_article", requirements={"id" : "[\d]+"}, defaults={"webpack" = "portfolio-article"})
     * @Template("AnujNairBundle:Portfolio:article.html.twig")
     * @param int $id
     * @param string $name
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws NoResultException
     */
    public function articleAction($id, $name = null)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            /** @var Portfolio $article */
            $article = $em
                ->getRepository('AnujNairBundle:Portfolio')
                ->getPortfolioById($id);

            // Make sure URL points to correct place for SEO purposes
            if ($name !== $article->getUrlSafeName()) {
                return $this->redirect($this->generateUrl('_an_portfolio_article', [
                    'id' => $article->getId(),
                    'name' => $article->getUrlSafeName()
                ]), 301);
            }
        } catch (NoResultException $e) {
            throw $this->createNotFoundException('That portfolio article doesn\'t exist.');
        }

        $similar = $em
            ->getRepository('AnujNairBundle:Portfolio')
            ->getSimilarPortfolioArticles($id, 1, 20);

        $archive = $em
            ->getRepository('AnujNairBundle:Blog')
            ->getBlogPostsByYearMonth(1, 10);

        return [
            'json' => json_encode([
                'article' => $article,
                'similar' => $similar,
                'archive' => $archive,
                'tags' => $this->getTagsForObj([$article]),
            ]),
            'article' => $article
        ];
    }

    /**
     * @Route("/t/{tagId}", requirements={"tagId" : "[\d]+"}, defaults={"webpack" = "portfolio-tag"})
     * @Route("/t/{tagId}-", requirements={"tagId" : "[\d]+"}, defaults={"webpack" = "portfolio-tag"})
     * @Route("/t/{tagId}-{name}", name="_an_portfolio_tag", requirements={"tagId" : "[\d]+"}, defaults={"webpack" = "portfolio-tag"})
     * @Template("AnujNairBundle:Portfolio:index.html.twig")
     * @param Request $request
     * @param int $tagId
     * @param string $name
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
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
                return $this->redirect($this->generateUrl('_an_portfolio_tag', [
                    'tagId' => $tag->getId(),
                    'name' => $tag->getUrlSafeName()
                ]), 301);
            }

        } catch (NoResultException $e) {
            throw $this->createNotFoundException('I couldn\'t find that tag!');
        }

        $articles = $em
            ->getRepository('AnujNairBundle:Portfolio')
            ->getPortfolioListByTagId($tag->getId(), $page, $noPerPage);
        $tagSummary = $em
            ->getRepository('AnujNairBundle:Tag')
            ->getPortfolioTagSummary(20);

        return [
            'json' => json_encode([
                'articles' => $articles,
                'tags' => $this->getTagsForObj($articles),
                'tagSummary' => $tagSummary,
                'tagId' => (int)$tagId
            ]),
        ];
    }

}
