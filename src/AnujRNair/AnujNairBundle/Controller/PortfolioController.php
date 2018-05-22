<?php

namespace AnujRNair\AnujNairBundle\Controller;

use AnujRNair\AnujNairBundle\Entity\Portfolio;
use AnujRNair\AnujNairBundle\Entity\Tag;
use Doctrine\ORM\NoResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PortfolioController
 * @package AnujRNair\AnujNairBundle\Controller
 * @Route("/portfolio")
 */
class PortfolioController extends Controller
{

    /**
     * @Route("/", name="_an_portfolio_index", defaults={"webpack" = "portfolio-index"})
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $portfolioList = $em
            ->getRepository('AnujNairBundle:Portfolio')
            ->getPortfolioList(1, 50);
        $tagSummary = $em
            ->getRepository('AnujNairBundle:Tag')
            ->getPortfolioTagSummary(3);

        return [
            'portfolioList' => $portfolioList,
            'tagSummary'    => $tagSummary
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
     */
    public function articleAction($id, $name = null)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            /** @var Portfolio $portfolio */
            $portfolio = $em
                ->getRepository('AnujNairBundle:Portfolio')
                ->getPortfolioById($id);

            // Make sure URL points to correct place for SEO purposes
            if ($name !== $portfolio->getUrlSafeName()) {
                return $this->redirect($this->generateUrl('_an_portfolio_article', [
                    'id'   => $portfolio->getId(),
                    'name' => $portfolio->getUrlSafeName()
                ]), 301);
            }
        } catch (NoResultException $e) {
            throw $this->createNotFoundException('That portfolio article doesn\'t exist.');
        }

        $similarPortfolio = $em
            ->getRepository('AnujNairBundle:Portfolio')
            ->getSimilarPortfolioArticles($id, 1, 20);

        $blogArchive = $em
            ->getRepository('AnujNairBundle:Blog')
            ->getBlogPostsByYearMonth(1, 10);

        return [
            'portfolio'                => $portfolio,
            'similarPortfolioArticles' => $similarPortfolio,
            'blogArchive'              => $blogArchive
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
                    'name'  => $tag->getUrlSafeName()
                ]), 301);
            }

        } catch (NoResultException $e) {
            throw $this->createNotFoundException('I couldn\'t find that tag!');
        }

        $portfolioList = $em
            ->getRepository('AnujNairBundle:Portfolio')
            ->getPortfolioListByTagId($tag->getId(), $page, $noPerPage);
        $tagSummary = $em
            ->getRepository('AnujNairBundle:Tag')
            ->getPortfolioTagSummary(3);

        return [
            'portfolioList' => $portfolioList,
            'tagSummary'    => $tagSummary
        ];
    }

}
