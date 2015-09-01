<?php

namespace AnujRNair\AnujNairBundle\Controller\Components;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class FooterController
 * @package AnujRNair\AnujNairBundle\Controller\Components
 * @Route("/footer")
 */
class FooterController extends Controller{

    /**
     * @Route("/index", name="_an_footer_index")
     * @Template("AnujNairBundle:Footer:index.html.twig")
     */
    public function indexAction()
    {
        return [
            'year' => date('Y')
        ];
    }

}
