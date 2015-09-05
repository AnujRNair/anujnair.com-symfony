<?php

namespace AnujRNair\AnujNairBundle\Controller\Components;

use AnujRNair\AnujNairBundle\Helper\NavHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class HeaderController
 * @package AnujRNair\AnujNairBundle\Controller\Components
 * @Route("/header")
 */
class HeaderController extends Controller
{

    /**
     * @Route("/index/{activeRoute}", name="_an_header_index")
     * @Template("AnujNairBundle:Header:index.html.twig")
     * @param string $activeRoute
     * @return array
     */
    public function indexAction($activeRoute)
    {
        $nav = new NavHelper();

        $nav
            ->add('_an_blog_index', 'Blog', 'icon-pencil')
            ->add('_an_portfolio_index', 'Portfolio', 'icon-globe')
            ->add('_an_about_index', 'About Me', 'icon-user')
            ->setActive($activeRoute);

        return [
            'nav' => $nav
        ];
    }

}
