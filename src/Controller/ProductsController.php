<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\Categories;
use App\Service\Cart\Cart;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsController extends AbstractController
{
    /**
     * @Route("/products/{id}", name="listproducts")
     */
    public function products($id, Cart $cart)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $detcate = $this->getDoctrine()->getRepository(Categories::class)->find($id);
        $products = $this->getDoctrine()->getRepository(Products::class)->findByCate($id);

        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal();

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
     * @Route("/product/{id}", name="product")
     */
    public function product($id, Cart $cart)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $prod = $this->getDoctrine()->getRepository(Products::class)->find($id);
        $id = $prod->getCategories()->getId();

        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal();

        return $this->render('products/product.html.twig', [
            'prod' => $prod,
            'selectcate'=> $id,
            'cates'=> $cates,
            'panier' => $viewpanier,
            'total' => $total
        ]);
    }

    /**
     * @Route("/news", name="news")
     */
    public function news(Cart $cart)
    {
        $id = 5;
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $newsprod = $this->getDoctrine()->getRepository(Products::class)->findNews(30);

        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal();

        return $this->render('products/news.html.twig', [
            'products' => $newsprod,
            'selectcate' => $id,
            'cates' => $cates,
            'panier' => $viewpanier,
            'total' => $total
        ]);
    }
}
