<?php

namespace AnujRNair\AnujNairBundle\Controller\Components;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SitemapController extends Controller
{

    /**
     * @Route("/sitemap.{_format}", name="_an_sitemap_index", Requirements={"_format" = "xml"})
     * @Template("AnujNairBundle:Sitemap:sitemap.xml.twig")
     * @param Request $request
     * @return array
     */
    public function sitemapAction(Request $request)
    {
        $urls = [];

        $em = $this->getDoctrine()->getEntityManager();
        $hostname = $request->getHost();

        // Top level
        $urls[] = ['loc' => $this->generateUrl('_an_blog_index'), 'changefreq' => 'daily', 'priority' => '1.0'];
        $urls[] = ['loc' => $this->generateUrl('_an_portfolio_index'), 'changefreq' => 'weekly', 'priority' => '1.0'];
        $urls[] = ['loc' => $this->generateUrl('_an_about_index'), 'changefreq' => 'monthly', 'priority' => '1.0'];

        // Blog Posts
        $seenTags = [];
        foreach ($em->getRepository('AnujNairBundle:Blog')->getBlogPosts(1, 100) as $blog) {
            $urls[] = [
                'loc'        => $this->generateUrl('_an_blog_article', ['id' => $blog->getId(), 'title' => $blog->getUrlSafeTitle()]),
                'changefreq' => 'daily',
                'priority'   => '1.0'
            ];
            foreach ($blog->getTags() as $tag) {
                if (in_array($tag->getId(), $seenTags)) {
                    continue;
                }
                $urls[] = [
                    'loc'        => $this->generateUrl('_an_blog_tag', ['tagId' => $tag->getId(), 'name' => $tag->getUrlSafeName()]),
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
                'loc'        => $this->generateUrl('_an_portfolio_article', ['id' => $portfolio->getId(), 'name' => $portfolio->getUrlSafeName()]),
                'changefreq' => 'weekly',
                'priority'   => '1.0'
            ];
            foreach ($portfolio->getTags() as $tag) {
                if (in_array($tag->getId(), $seenTags)) {
                    continue;
                }
                $urls[] = [
                    'loc'        => $this->generateUrl('_an_portfolio_tag', ['tagId' => $tag->getId(), 'name' => $tag->getUrlSafeName()]),
                    'changefreq' => 'monthly',
                    'priority'   => '0.9'
                ];
                $seenTags[] = $tag->getId();
            }
        }

        return [
            'urls' => $urls,
            'hostname' => $hostname
        ];
    }

}
