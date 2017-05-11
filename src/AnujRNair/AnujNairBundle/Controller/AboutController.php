<?php

namespace AnujRNair\AnujNairBundle\Controller;

use AnujRNair\AnujNairBundle\Entity\Form\Contact;
use AnujRNair\AnujNairBundle\Forms\About\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $actionUrl = $this->generateUrl('_an_about_index') . '#contact-me';

        $contact = new Contact();
        $contactForm = $this->createForm(new ContactType($actionUrl), $contact);
        $contactForm->handleRequest($request);

        if ($contactForm->isValid()) {
            $contact->setIp($request->getClientIp());

            /** @var \Swift_Mime_Message $message */
            $message = \Swift_Message::newInstance()
                ->setSubject($contact->getSubject())
                ->addFrom($contact->getEmail())
                ->addTo($this->container->getParameter('mailer_to'))
                ->addReplyTo($contact->getEmail())
                ->setBody(
                    $this->renderView('AnujNairBundle:Email:contactEmail.html.twig', [
                        'contact' => $contact
                    ]),
                    'text/html'
                );
            $this->get('mailer')->send($message);
            $this->addFlash('success', 'Thanks for your email! I\'ll be in contact shortly.');
            return $this->redirect($actionUrl);
        }

        return [
            'contactForm' => $contactForm->createView(),
            'years' => intval(date('Y')) - 2002
        ];
    }

}
