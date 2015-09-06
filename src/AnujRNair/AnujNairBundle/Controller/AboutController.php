<?php

namespace AnujRNair\AnujNairBundle\Controller;

use AnujRNair\AnujNairBundle\Entity\Form\Contact;
use AnujRNair\AnujNairBundle\Forms\About\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class AboutController
 * @package AnujRNair\AnujNairBundle\Controller
 * @Route("/about")
 */
class AboutController extends Controller
{

    /**
     * @Route("/", name="_an_about_index")
     * @Template()
     */
    public function indexAction()
    {
        $contact = new Contact();
        $contactForm = $this->createForm(new ContactType(), $contact);

        return [
            'contactForm' => $contactForm->createView()
        ];
    }

}
