<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Service\Cart\Cart;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function cart(Cart $cart)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();

        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal($viewpanier);
        $montant['TTC'] = $total;
        $montant['HT'] = $montant['TTC']/1.055 ;
        $montant['TVA'] = $montant['TTC']-$montant['HT'];

        return $this->render('cart/cart.html.twig', [
            'cates' => $cates,
            'selectcate'=> 0,
            'panier' => $viewpanier,
            'total' => $total,
            'montant' => $montant
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
