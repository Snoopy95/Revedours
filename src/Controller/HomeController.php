<?php

namespace App\Controller;

use App\Entity\Config;
use App\Entity\Comments;
use App\Entity\Categories;
use App\Entity\Products;
use App\Service\Cart\Cart;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->redirectToRoute('index');
    }

    /**
    * @Route("/adlog1204", name="adlog1204")
    */
    public function adlog1204()
    {
        return $this->render('home/logadmin.html.twig');
    }

    /**
     * @Route("/index", name="index")
     */
    public function index(Cart $cart)
    {
        $config = $this->getDoctrine()->getRepository(Config::class)->findOneBy(
            ['configname' => 'online'],
            []
        );

        $online = $config->getValeur();

        if ($online) {
            $posts = $this->getDoctrine()->getRepository(Comments::class)->findBy(
                ['status' => 1],
                ['datecreat' => 'DESC'],
                4,
                0
            );
            $viewpanier = $cart->getViewCart();
            $total = $cart->getTotal();

            return $this->render('home/home.html.twig', [
                'posts' => $posts,
                'selectcate'=> 0,
                'panier' => $viewpanier,
                'total' => $total
            ]);
        } else {
            return $this->render('offline.html.twig');
        }
    }

    /**
     * @Route("/posts", name="posts")
     */
    public function posts(Cart $cart)
    {
        $posts = $this->getDoctrine()->getRepository(Comments::class)->findBy(
            ['status' => 1],
            ['datecreat' => 'DESC']
        );
        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal();

        return $this->render('home/allcomments.html.twig', [
            'selectcate'=> 0,
            'posts' => $posts,
            'panier' => $viewpanier,
            'total' => $total
        ]);
    }

    /**
     * @Route("/galerie", name="galerie")
     */
    public function galerie(Cart $cart)
    {
        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal();
        $toiles= $this->getDoctrine()->getRepository(Products::class)->findsold();

        return $this->render('home/galerie.html.twig', [
            'selectcate'=> 6,
            'panier' => $viewpanier,
            'total' => $total,
            'toiles' => $toiles
        ]);
    }
}
