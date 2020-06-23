<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\Checkout;
use App\Entity\Products;
use App\Entity\Addresses;
use App\Entity\Categories;
use App\Form\CheckoutType;
use App\Service\Cart\Cart;
use App\Form\AddressesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function cart(Cart $cart, UserInterface $user, Request $request, EntityManagerInterface $em)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal();
        $adress = $this->getDoctrine()->getRepository(Addresses::class)->findBy(
            ['user' => $user]
        );

        if (!$adress) {
            $adress = [];
        };
            $addresse= new Addresses();
            $form = $this->createForm(AddressesType::class, $addresse);
            $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addresse->setUser($user);
            $em->persist($addresse);
            $em->flush();
            // Recuperation de l'id de l'adresse qui vient d etre creee
            $idadress = $this->getDoctrine()->getRepository(Addresses::class)->findBy(
                ['user' => $user],
                ['id' => 'DESC'],
                1,
                0
            );
            $id = $idadress[0]->getId();
            return $this->redirectToRoute('payment', ['id' => $id]);
        }

        return $this->render('cart/cart.html.twig', [
            'cates' => $cates,
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
    public function addcart($id, SessionInterface $session, Cart $cart)
    {
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            return $this->json([
                'message' => 'Produit déjà dans le panier',
                'countpanier' => count($panier)
            ], 405);
        } else {
            $cart->add($id);
            $viewpanier = $cart->getViewcart();
            $total = $cart->getTotal();
            
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
    public function delcart($id, Cart $cart)
    {
        $cart->del($id);
        $viewpanier = $cart->getViewcart();
        $total = $cart->getTotal();

        return $this->json([
            'message' => 'produit retiré du panier',
            'panier' => $viewpanier,
            'total' => $total
        ], 200);
    }

    /**
     * @Route("/cart/removecart", name="removecart")
     */
    public function removecart(SessionInterface $session, Cart $cart)
    {
        $panier = $session->get('panier, []');
        $panier = [];
        $session->set('panier', $panier);
        $viewpanier = $cart->getViewcart();
        $total = $cart->getTotal();

        return $this->json([
            'message' => 'Panier annuler',
            'panier' => $viewpanier,
            'total' => $total
        ], 200);
    }

    /**
     * @Route("/cart/payment/{id}", name="payment")
     */
    public function payment($id, Cart $cart, Request $request, UserInterface $user, EntityManagerInterface $entity, SessionInterface $session)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal();
        $adres = $this->getDoctrine()->getRepository(Addresses::class)->find($id);

        $checkout= new Checkout();
        $form = $this->createForm(CheckoutType::class, $checkout);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($checkout->getCvv()<100) {
                $this->addFlash("danger", "Paiement refusé !!!");
                $this->redirectToRoute('payment', ['id' => $id]);
            } else {
                $order = new Orders();
                $order->setAddresses($adres);
                $order->setAmount($total['TTC']);
                $order->setDatecreat(new \DateTime());
                $order->setUsers($user);
                $order->setPay($checkout->getNcb());
                $order->setStatus('1');

                foreach ($viewpanier as $id) {
                    $prod = $this->getDoctrine()->getRepository(Products::class)->find($id['id']);
                    $order->addProduct($prod);
                };

                $lastorder = $this->getDoctrine()->getRepository(Orders::class)->findOneBy(
                    [],
                    ['datecreat' => 'DESC']
                );

                $datenow = date('ym');

                if ($lastorder == null) {
                    $numorder = ($datenow * 1000) + 1;
                } else {
                    $lastnum = $lastorder->getNumberOrder();
                    if (substr($lastnum, 0, 4) == $datenow) {
                        $orderid=intval(substr($lastnum, 4, 3))+1;
                    } else {
                        $orderid = 1;
                    }
                    $numorder = ($datenow*1000)+$orderid;
                }
                $order->setNumberOrder($numorder);

                $entity->persist($order);
                $entity->flush();

                $panier = $session->get('panier, []');
                $panier = [];
                $session->set('panier', $panier);

                $this->addFlash("success", "Merci pour votre commande");
                return $this->redirectToRoute('index');
            }
        }

        return $this->render('cart/payment.html.twig', [
            'form' => $form->createView(),
            'cates' => $cates,
            'selectcate' => 0,
            'panier' => $viewpanier,
            'total' => $total,
            'adres' => $adres
        ]);
    }
}
