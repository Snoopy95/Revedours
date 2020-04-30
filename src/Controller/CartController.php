<?php

namespace App\Controller;

use App\Entity\Addresses;
use App\Entity\Categories;
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
        $total = $cart->getTotal($viewpanier);
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
            $id = $idadress['id'];
            dd($idadress, $id);
            return $this->redirectToRoute('validator', ['id' => $id]);
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
            $total = $cart->getTotal($viewpanier);
            
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
        $total = $cart->getTotal($viewpanier);

        return $this->json([
            'message' => 'produit retiré du panier',
            'panier' => $viewpanier,
            'total' => $total
        ], 200);
    }

    /**
     * @Route("/cart/removecart", name="removecart")
     */
    public function removecart(SessionInterface $session)
    {
        $panier = $session->get('panier, []');
        $panier = [];
        $session->set('panier', $panier);

        return $this->json([
            'message' => 'Panier annuler',
            'panier' => [],
            'total' => 0
        ], 200);
    }

    /**
     * @Route("/cart/validator/{id}", name="validator")
     */
    public function validator($id, Cart $cart)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal($viewpanier);
        $adress = $this->getDoctrine()->getRepository(Addresses::class)->find($id);

        return $this->render('cart/validator.html.twig', [
            'cates' => $cates,
            'selectcate' => 0,
            'panier' => $viewpanier,
            'total' => $total,
            'adress' => $adress
        ]);
    }
}
