<?php

namespace AnujRNair\AnujNairBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class PortfolioController
 * @package AnujRNair\AnujNairBundle\Controller
 * @Route("/portfolio")
 */
class PortfolioController extends Controller
{

    /**
     * @Route("/", name="_an_portfolio_index")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

}
