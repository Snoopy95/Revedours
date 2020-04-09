<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\Categories;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsController extends AbstractController
{
    /**
     * @Route("/products/{id}", name="listproducts")
     */
    public function products($id)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $detcate = $this->getDoctrine()->getRepository(Categories::class)->find($id);
        $products = $this->getDoctrine()->getRepository(Products::class)->findByCate($id);

        return $this->render('products/products.html.twig', [
            'selectcate' => $id,
            'cates' => $cates,
            'products' =>$products,
            'detcate' =>$detcate
        ]);
    }

    /**
     * @Route("/product/{id}/{cateid}", name="product")
     */
    public function product($id, $cateid)
    {
        $listcate = $this->getDoctrine();
        $cates = $listcate->getRepository(Categories::class)->findAll();
        $deprod = $this->getDoctrine();
        $prod = $deprod->getRepository(Products::class)->find($id);

        return $this->render('products/product.html.twig', [
            'prod' => $prod,
            'selectcate'=> $cateid,
            'cates'=> $cates
        ]);
    }
}
