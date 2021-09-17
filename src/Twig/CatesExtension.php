<?php

namespace App\Twig;

use App\Entity\Categories;
use App\Service\Cart\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatesExtension extends AbstractController
{
    protected $em;
    protected $cart;

    public function __construct(EntityManagerInterface $em, Cart $cart)
    {
        $this->em = $em;
        $this->cart = $cart;
    }

    public function getCates():array
    {
        return $this->em->getRepository(Categories::class)->findAll();
    }

    public function getCart():array
    {
        return $this->cart->getViewCart();
    }

    public function getMontant():array
    {
        return $this->cart->getTotal();
    }
}
