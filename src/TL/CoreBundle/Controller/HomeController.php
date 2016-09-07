<?php

namespace TL\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('TLCoreBundle:Home:index.html.twig');
    }

    public function contactAction(Request $request)
    {
        $defaultData = array();
        $contactForm = $this
                        ->createFormBuilder($defaultData)
                        ->add('name', TextType::class)
                        ->add('surname', TextType::class)
                        ->add('email', EmailType::class)
                        ->add('send', SubmitType::class)
                        ->getForm();

        if($request->isMethod('POST') && $contactForm->handleRequest($request)){

            // $this->get('tl_core.mailer')->sendContactMessage($contactForm->getData());
            $contactInfo = $contactForm->getData();

                        $message = \Swift_Message::newInstance()
                        ->setSubject('Contact page - new Message')
                        ->setFrom($contactInfo['email'])
                        ->setTo('lbaxel95@gmail.com')
                        ->setBody(
                            $this->renderView('TLCoreBundle:Contact:contactTemplate.html.twig',
                            array('name'    => $contactInfo['name'],
                                  'surname' => $contactInfo['surname'],
                                  'email'   => $contactInfo['email']
                            ))
                        );
                        $this->get('mailer')->send($message);

            return $this->redirectToRoute('tl_core_homepage');
        }

        return $this->render('TLCoreBundle:Contact:contact.html.twig', array(
            'contactForm'  => $contactForm->createView()
        ));
    }
}