<?php

namespace App\Controller;

use App\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $listcate = $this->getDoctrine();
        $cates = $listcate->getRepository(Categories::class)->findAll();

        return $this->render('home/home.html.twig', [
            'cates'=> $cates,
            'selectcate'=> 0 ]);
    }
}
