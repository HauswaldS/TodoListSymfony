<?php

namespace TL\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TL\CoreBundle\Type\ContactType;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('TLCoreBundle:Home:index.html.twig');
    }

    public function contactAction(Request $request)
    {   $defaultData = array('message' => "DefaultMessage");
        $contactForm = $this->createForm(ContactType::class);
        if($request->isMethod('POST')){
            $this->get('tl_core.mailer')->sendContactMessage($contactForm->getData());
            return $this->redirectToRoute('tl_dashboard_home');
        }

        return $this->render('TLCoreBundle:Contact:contact.html.twig', array(
            'contactForm'  => $contactForm->createView()
        ));
    }
}