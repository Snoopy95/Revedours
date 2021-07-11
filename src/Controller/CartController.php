<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\User;
use App\Entity\Orders;
use App\Entity\Products;
use App\Entity\Addresses;
use App\Entity\Categories;
use App\Service\Cart\Cart;
use App\Form\AddressesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{

    protected $cart;
    protected $session;
    protected $em;

    public function __construct(Cart $cart, SessionInterface $session, EntityManagerInterface $em)
    {
        $this->cart = $cart;
        $this->session = $session;
        $this->em = $em;
    }

    /**
     * @Route("/cart", name="cart")
     */
    public function cart(UserInterface $user, Request $request)
    {
        $viewpanier = $this->cart->getViewCart();
        $total = $this->cart->getTotal();
        $adress = $this->getDoctrine()->getRepository(Addresses::class)->findBy(
            ['user' => $user]
        );

        if (!$viewpanier) {
            $lasturl = $request->headers->get('referer');
            $url = strstr($lasturl, '/');

            return $this->render('cart/cartvide.html.twig', [
                'selectcate' => 0,
                'panier' => $viewpanier,
                'total' => $total,
                'user' => $user,
                'url' => $url
            ]);
        };

        if (!$adress) {
            $adress = [];
        };
            $addresse= new Addresses();
            $form = $this->createForm(AddressesType::class, $addresse);
            $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addresse->setUser($user);
            $this->em->persist($addresse);
            $this->em->flush();
            // Recuperation de l'id de l'adresse qui vient d'etre creee
            $idadress = $this->getDoctrine()->getRepository(Addresses::class)->findOneBy(
                ['user' => $user],
                ['id' => 'DESC']
            );
            $id = $idadress->getId();
            return $this->redirectToRoute('payment', ['id' => $id]);
        }

        return $this->render('cart/cart.html.twig', [
            'selectcate' => 0,
            'panier' => $viewpanier,
            'total' => $total,
            'user' => $user,
            'form' => $form->createView(),
            'adress' => $adress
        ]);
    }

    /**
     * @Route("/cart/addcart/{id}", name="addcart")
     */
    public function addcart($id)
    {
        $panier = $this->session->get('panier', []);
        if (!empty($panier[$id])) {
            return $this->json([
                'message' => 'Produit déjà dans le panier',
                'countpanier' => count($panier)
            ], 405);
        } else {
            $this->cart->add($id);
            $viewpanier = $this->cart->getViewcart();
            $total = $this->cart->getTotal();
            
            return $this->json([
                'message' => 'tous à bien marché',
                'panier' => $viewpanier,
                'total' => $total
            ], 200);
        }
    }

    /**
     * @Route("/cart/delcart/{id}", name="delcart")
     */
    public function delcart($id)
    {
        $this->cart->del($id);
        $viewpanier = $this->cart->getViewcart();
        $total = $this->cart->getTotal();

        return $this->json([
            'message' => 'produit retiré du panier',
            'panier' => $viewpanier,
            'total' => $total
        ], 200);
    }

    /**
     * @Route("/cart/removecart", name="removecart")
     */
    public function removecart()
    {
        $panier = $this->session->get('panier, []');
        $panier = [];
        $this->session->set('panier', $panier);
        $viewpanier = $this->cart->getViewcart();
        $total = $this->cart->getTotal();

        return $this->json([
            'message' => 'Panier annuler',
            'panier' => $viewpanier,
            'total' => $total
        ], 200);
    }

    /**
     * @Route("/cart/payment/{id}", name="payment")
     */
    public function payment($id, UserInterface $user)
    {
        $viewpanier = $this->cart->getViewCart();
        $total = $this->cart->getTotal();
        $adres = $this->getDoctrine()->getRepository(Addresses::class)->find($id);

        $preordre=[];
        $preordre['adresse'] = $id;
        $preordre['user'] = $user->getId();
        $this->session->set('preordre', $preordre);

        return $this->render('cart/payment.html.twig', [
            'selectcate' => 0,
            'panier' => $viewpanier,
            'total' => $total,
            'adres' => $adres
        ]);
    }

    /**
     * @Route("/cart/intention", name="intention")
     */
    public function intention(): Response
    {
        $viewpanier = $this->cart->getViewCart();
        $total = $this->cart->getTotal();
        $preordre = $this->session->get('preordre');

        Stripe::setApiKey('sk_test_51HrP9mFBU85ljtQmUMSnP1RsXsfbLB6RVSfSWx8bqbjIJQAzagwfeZQpTydRCVbaHeN6kLmYZyvz5E207xFCSVEP00ZXiqwBCu');
        
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $total['EXP']*100,
            'currency' => 'eur',
        ]);

        $clientSecret = $paymentIntent->client_secret;

        return new Response($clientSecret, 200);
    }

    /**
     * @Route("/cart/success/{idtrans}", name="success")
     */
    public function success($idtrans, MailerInterface $mailer)
    {
        $viewpanier = $this->cart->getViewCart();
        $total = $this->cart->getTotal();
        $preordre = $this->session->get('preordre');

        if (empty($preordre)) {
            return $this->redirectToRoute('index');
        }

        $adresse = $this->getDoctrine()->getRepository(Addresses::class)->find($preordre['adresse']);
        $use = $this->getDoctrine()->getRepository(User::class)->find($preordre['user']);

        $order = new Orders();
                $order->setAddresses($adresse);
                $order->setAmount($total['EXP']);
                $order->setDatecreat(new \DateTime());
                $order->setUsers($use);
                $order->setStatus('1');
                $order->setPay($idtrans);

        foreach ($viewpanier as $id) {
            $prod = $this->getDoctrine()->getRepository(Products::class)->find($id['id']);
            $order->addProduct($prod);
        };

        $lastorder = $this->getDoctrine()->getRepository(Orders::class)->findOneBy(
            [],
            ['datecreat' => 'DESC']
        );
        if ($lastorder == null) {
            $numorder = 100;
        } else {
            $numorder = $lastorder->getNumberOrder()+1;
        }
        $order->setNumberOrder($numorder);

        $this->em->persist($order);
        $this->em->flush();

            $email = (new TemplatedEmail())
            ->from('Revedours@createurweb.fr')
            ->to($use->getEmail())
            ->subject('Commande valider')
            ->htmlTemplate('emails/commande.html.twig')
            ->context([
                'name' => $use->getUsername(),
                'commande' => $order->getNumberOrder(),
                'montant' => $order->getAmount($total['EXP']),
                'date' => $order->getDatecreat()
            ]);
            $mailer->send($email);
            
        $panier=[];
        $this->session->set('panier', $panier);
        $preordre=[];
        $this->session->set('preordre', $preordre);

        return $this->render('cart/success.html.twig', [
            'selectcate' => 0,
            'panier' => [],
            'total' => $total,
            'idtrans' => $idtrans,
            'adresse' => $adresse,
            'numfact' => $numorder,
        ]);
    }

    /**
     * @Route("/cart/cancel/{error}", name="cancel")
     */
    public function cancel($error)
    {
        dd($error);
    }
}
