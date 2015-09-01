<?php

namespace AnujRNair\AnujNairBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ContactController
 * @package AnujRNair\AnujNairBundle\Controller
 * @Route("/contact")
 */
class ContactController extends Controller
{

    /**
     * @Route("/", name="_an_contact_index")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

}
