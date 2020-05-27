<?php

namespace App\Controller;

use App\Service\Cart\Cart;
use App\Entity\Comments;
use App\Entity\Categories;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Cart $cart)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $posts = $this->getDoctrine()->getRepository(Comments::class)->findBy(
            ['status' => 1],
            ['datecreat' => 'DESC'],
            4,
            0
        );
        
        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal();

        return $this->render('home/home.html.twig', [
            'cates'=> $cates,
            'selectcate'=> 0,
            'posts' => $posts,
            'panier' => $viewpanier,
            'total' => $total
        ]);
    }

    /**
     * @Route("/posts", name="posts")
     */
    public function posts(Cart $cart)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $posts = $this->getDoctrine()->getRepository(Comments::class)->findBy(
            ['status' => 1],
            ['datecreat' => 'DESC']
        );
        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal();

        return $this->render('home/allcomments.html.twig', [
            'cates'=> $cates,
            'selectcate'=> 0,
            'posts' => $posts,
            'panier' => $viewpanier,
            'total' => $total
        ]);
    }
}
