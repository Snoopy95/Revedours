<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Entity\Categories;
use App\Service\Cart\Cart;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SocialController extends AbstractController
{
    /**
     * @Route("/contactme", name="contactme")
     */
    public function contactme(MailerInterface $mailer, Request $request, Cart $cart)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal();

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $email = (new TemplatedEmail())
            ->from('revedours@createurweb.fr')
            ->to('Boxalacon@gmail.com')
            ->subject('Demande d\'info')
            ->htmlTemplate('emails/contact.html.twig')
            ->context([
                'name' => $contact->getName(),
                'mail' => $contact->getMail(),
                'objet' => $contact->getObjet(),
                'message' => $contact->getMessage()
            ]);

            $mailer->send($email);
            $this->addFlash('success', 'Mail bien envoyer');

            return $this->redirectToRoute('index');
        }

        return $this->render('social/Contactme.html.twig', [
                'form' => $form->createView(),
                'cates' => $cates,
                'selectcate' => 0,
                'panier' => $viewpanier,
                'total' => $total
        ]);
    }
}
