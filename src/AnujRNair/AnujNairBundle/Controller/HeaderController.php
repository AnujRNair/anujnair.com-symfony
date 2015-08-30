<?php

namespace AnujRNair\AnujNairBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class HeaderController
 * @package AnujRNair\AnujNairBundle\Controller
 * @Route("/header")
 */
class HeaderController extends Controller{

    /**
     * @Route("/index", name="_an_header_index")
     * @Template("AnujNairBundle:Header:index.html.twig")
     */
    public function indexAction()
    {
        return [];
    }

}
