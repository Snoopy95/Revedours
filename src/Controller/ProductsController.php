<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\Categories;
use App\Repository\ProductsRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProductsController extends AbstractController
{
    /**
     * @Route("/products/{id}", name="listproducts")
     */
    public function products($id, SessionInterface $session, ProductsRepository $productsRepository)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $detcate = $this->getDoctrine()->getRepository(Categories::class)->find($id);
        $products = $this->getDoctrine()->getRepository(Products::class)->findByCate($id);

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

        return $this->render('products/products.html.twig', [
            'selectcate' => $id,
            'cates' => $cates,
            'products' =>$products,
            'detcate' =>$detcate,
            'panier' => $viewpanier,
            'total' => $total
        ]);
    }

    /**
     * @Route("/product/{id}/{cateid}", name="product")
     */
    public function product($id, $cateid, SessionInterface $session, ProductsRepository $productsRepository)
    {
        $listcate = $this->getDoctrine();
        $cates = $listcate->getRepository(Categories::class)->findAll();
        $deprod = $this->getDoctrine();
        $prod = $deprod->getRepository(Products::class)->find($id);

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

        return $this->render('products/product.html.twig', [
            'prod' => $prod,
            'selectcate'=> $cateid,
            'cates'=> $cates,
            'panier' => $viewpanier,
            'total' => $total
        ]);
    }
}
