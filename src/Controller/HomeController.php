<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Categories;
use App\Repository\ProductsRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SessionInterface $session, ProductsRepository $productsRepository)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $posts = $this->getDoctrine()->getRepository(Comments::class)->findBy(
            ['status' => 1],
            ['datecreat' => 'DESC'],
            4,
            0
        );

        $panier = $session->get('panier', []);
        $viewpanier = [];
        
        foreach ($panier as $id) {
            $viewpanier[] = [
                'product' => $productsRepository->find($id)
            ];
        };

        $total = 0;
        foreach ($viewpanier as $item) {
            $total += $item['product']->getProdPrice();
        };

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
    public function posts(SessionInterface $session, ProductsRepository $productsRepository)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $posts = $this->getDoctrine()->getRepository(Comments::class)->findBy(
            ['status' => 1],
            ['datecreat' => 'DESC']
        );
        $panier = $session->get('panier', []);
        $viewpanier = [];

        foreach ($panier as $id) {
            $viewpanier[] = [
                'product' => $productsRepository->find($id)
            ];
        };

        $total = 0;
        foreach ($viewpanier as $item) {
            $total += $item['product']->getProdPrice();
        };

        return $this->render('home/allcomments.html.twig', [
            'cates'=> $cates,
            'selectcate'=> 0,
            'posts' => $posts,
            'panier' => $viewpanier,
            'total' => $total
        ]);
    }
}
