<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index()
    {
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }

    /**
     * @Route("/panier/addpanier/{id}", name="addpanier")
     */
    public function addpanier($id, SessionInterface $session, ProductsRepository $productsRepository): Response
    {
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            $countpanier = count($panier);

            return $this->json([
                'message' => 'Produt déjà dans le panier',
                'countpanier' => $countpanier
            ], 405);
        } else {
            $panier[$id] = $id;
            $session->set('panier', $panier);
            $countpanier = count($panier);

            $viewpanier = [];
            foreach ($panier as $id) {
                $item = $this->getDoctrine()->getRepository(Products::class)->find($id);

                $viewpanier[] = [
                    'id' => $item->getId(),
                    'name' => $item->getProdName(),
                    'price' => $item->getProdPrice()
                ];
            };
            $total = 0;
            foreach ($viewpanier as $item) {
                $total += $item['price'];
            };
            
            return $this->json([
                'message' => 'tous à bien marché',
                'countpanier' => $countpanier,
                'panier' => $viewpanier,
                'total' => $total
            ], 200);
        }
    }

    /**
     * @Route("panier/delprod/{id}", name="delprod")
     */
    public function delprod($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        };
        $session->set('panier', $panier);
        $countpanier = count($panier);

        $viewpanier = [];
        foreach ($panier as $id) {
            $item = $this->getDoctrine()->getRepository(Products::class)->find($id);

            $viewpanier[] = [
                'id' => $item->getId(),
                'name' => $item->getProdName(),
                'price' => $item->getProdPrice()
            ];
        };
        $total = 0;
        foreach ($viewpanier as $item) {
            $total += $item['price'];
        };

        return $this->json([
            'message' => 'produit retiré du panier',
            'countpanier' => $countpanier,
            'panier' => $viewpanier,
            'total' => $total
        ], 200);
    }
}
