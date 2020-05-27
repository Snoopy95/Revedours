<?php

namespace App\Service\Cart;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Products;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    protected $session;
    protected $entityManager;

    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }
    public function add($id)
    {
        $panier = $this->session->get('panier', []);
        $panier[$id] = $id;
        $this->session->set('panier', $panier);
    }
    public function del($id)
    {
        $panier = $this->session->get('panier', []);
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        };
        $this->session->set('panier', $panier);
    }
    public function getViewCart()
    {
        $panier = $this->session->get('panier', []);
        $viewpanier = [];
        foreach ($panier as $id) {
            $item = $this->entityManager->getRepository(Products::class)->find($id);
            $viewpanier[] = [
                'id' => $item->getId(),
                'name' => $item->getProdName(),
                'price' => $item->getProdPrice(),
                'picture' => $item->getProdPicture()
            ];
        };
        return $viewpanier;
    }
    public function getTotal()
    {
        $total = 0;
        $viewpanier = $this->getViewCart();
        foreach ($viewpanier as $item) {
            $total += $item['price'];
        };
        $montant['TTC'] = $total;
        $montant['EXP'] = $total + 20;
        // TVA a 5.5% (/1.055) pour 20%(/1.2)
        $montant['HT'] = round($montant['TTC'] / 1.055, 2);
        $montant['TVA'] = round($montant['TTC'] - $montant['HT'], 2);
        return $montant;
    }
}
