<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailerController extends AbstractController
{
    /**
     * @Route("/mailer", name="mailer")
     */
    public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('Boxalacon@gmail.com')
            ->to('Boxalacon@gmail.com')
            ->subject('test mail')
            ->text('ceci est un test de mail avec Symfony');
            // ->html('<p>avoir ce que sa donne</p>
            // <h2> un Titre </h2>');

        $mailer->send($email);

        return $this->redirectToRoute('index');
    }
}
