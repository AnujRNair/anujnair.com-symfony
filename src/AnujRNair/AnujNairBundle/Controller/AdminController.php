<?php

namespace AnujRNair\AnujNairBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class AdminController
 * @package AnujRNair\AnujNairBundle\Controller
 * @Route("/admin")
 */
class AdminController extends Controller
{

    /**
     * @Route("/", name="_an_admin_index")
     */
    public function indexAction()
    {
        var_dump('here'); die();
    }

}
