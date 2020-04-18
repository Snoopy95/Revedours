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
        $total = $cart->getTotal($viewpanier);

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
    public function product($id, $cateid, Cart $cart)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $prod = $this->getDoctrine()->getRepository(Products::class)->find($id);

        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal($viewpanier);

        return $this->render('products/product.html.twig', [
            'prod' => $prod,
            'selectcate'=> $cateid,
            'cates'=> $cates,
            'panier' => $viewpanier,
            'total' => $total
        ]);
    }
}
