<?php

namespace App\Controller;

use App\Service\Cart\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function cart()
    {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
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
}
