<?php

namespace TL\CoreBundle\Mail;

use Symfony\Component\Templating\EngineInterface;

class Mailer 
{
    protected $mailer;

    protected $templating;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;

        $this->templating = $templating;
    }

    public function sendContactMessage($contact)
    {
        $template = 'TLCoreBundle:Contact:contactTemplate.html.twig';

        $from = $contact['email'];

        $to = 'lbaxel95@gmail.com';

        $subject = '[TodoList With Symfony] Contact Form';

        $body = $this
                ->templating
                ->render($template, array('contact' => $contact));

        $this->sendMessage($from, $to, $subject, $body);
    }

    public function sendMessage($from, $to, $subject, $body)
    {
        $mail = \Swift_Message::newInstance();

        $mail
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->SetContentType('text/html');

            $this->mailer->send($mail);
    }

}