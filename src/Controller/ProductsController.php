<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\Categories;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsController extends AbstractController
{
    /**
     * @Route("/products", name="products")
     */
    public function index()
    {
        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }

    /**
     * @Route("/products/{id}", name="listproducts")
     */
    public function products($id)
    {
        $listcate = $this->getDoctrine();
        $cates = $listcate->getRepository(Categories::class)->findAll();
        $detailcate = $this->getDoctrine();
        $detcate = $detailcate->getRepository(Categories::class)->find($id);

        $listprod = $this->getDoctrine();
        $products = $listprod->getRepository(Products::class)->findByCate($id);

        
        return $this->render('products/products.html.twig', [
            'controller_name' => 'Products/'.$id ,
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
