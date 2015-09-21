<?php

namespace AnujRNair\AnujNairBundle\Controller\Components;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapController extends Controller
{

    /**
     * @Route("/sitemap.{_format}", name="_an_sitemap_index", Requirements={"_format" = "xml"})
     * @Template("AnujNairBundle:Sitemap:sitemap.xml.twig")
     * @return array
     */
    public function sitemapAction()
    {
        $urls = [];
        $em = $this->getDoctrine()->getEntityManager();

        // Top level
        $urls[] = [
            'loc' => $this->generateUrl('_an_blog_index', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'changefreq' => 'daily',
            'priority' => '1.0'
        ];
        $urls[] = [
            'loc' => $this->generateUrl('_an_portfolio_index', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'changefreq' => 'weekly',
            'priority' => '1.0'
        ];
        $urls[] = [
            'loc' => $this->generateUrl('_an_about_index', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'changefreq' => 'monthly',
            'priority' => '1.0'
        ];

        // Blog Posts
        $seenTags = [];
        foreach ($em->getRepository('AnujNairBundle:Blog')->getBlogPosts(1, 100) as $blog) {
            $urls[] = [
                'loc'        => $this->generateUrl('_an_blog_article', ['id' => $blog->getId(), 'title' => $blog->getUrlSafeTitle()], UrlGeneratorInterface::ABSOLUTE_URL),
                'changefreq' => 'daily',
                'priority'   => '1.0',
                'lastmod'    => ($blog->getDateUpdated() !== null ? $blog->getDateUpdated()->format('Y-m-d') : $blog->getDatePublished()->format('Y-m-d'))
            ];
            foreach ($blog->getTags() as $tag) {
                if (in_array($tag->getId(), $seenTags)) {
                    continue;
                }
                $urls[] = [
                    'loc'        => $this->generateUrl('_an_blog_tag', ['tagId' => $tag->getId(), 'name' => $tag->getUrlSafeName()], UrlGeneratorInterface::ABSOLUTE_URL),
                    'changefreq' => 'monthly',
                    'priority'   => '0.9'
                ];
                $seenTags[] = $tag->getId();
            }
        }

        // Portfolio articles
        $seenTags = [];
        foreach ($em->getRepository('AnujNairBundle:Portfolio')->getPortfolioList(1, 100) as $portfolio) {
            $urls[] = [
                'loc'        => $this->generateUrl('_an_portfolio_article', ['id' => $portfolio->getId(), 'name' => $portfolio->getUrlSafeName()], UrlGeneratorInterface::ABSOLUTE_URL),
                'changefreq' => 'weekly',
                'priority'   => '1.0',
                'lastmod'    => ($portfolio->getDateUpdated() !== null ? $portfolio->getDateUpdated()->format('Y-m-d') : $portfolio->getDateCreated()->format('Y-m-d'))
            ];
            foreach ($portfolio->getTags() as $tag) {
                if (in_array($tag->getId(), $seenTags)) {
                    continue;
                }
                $urls[] = [
                    'loc'        => $this->generateUrl('_an_portfolio_tag', ['tagId' => $tag->getId(), 'name' => $tag->getUrlSafeName()], UrlGeneratorInterface::ABSOLUTE_URL),
                    'changefreq' => 'monthly',
                    'priority'   => '0.9'
                ];
                $seenTags[] = $tag->getId();
            }
        }

        return [
            'urls' => $urls
        ];
    }

}
