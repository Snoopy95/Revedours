<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Categories;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $listcate = $this->getDoctrine();
        $cates = $listcate->getRepository(Categories::class)->findAll();
        $posts = $this->getDoctrine()->getRepository(Comments::class)->findBy(
            ['status' => 1],
            ['datecreat' => 'DESC'],
            4,
            0
        );

        return $this->render('home/home.html.twig', [
            'cates'=> $cates,
            'selectcate'=> 0,
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/posts", name="posts")
     */
    public function posts()
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $posts = $this->getDoctrine()->getRepository(Comments::class)->findBy(
            ['status' => 1],
            ['datecreat' => 'DESC']
        );
        return $this->render('home/allcomments.html.twig', [
            'cates'=> $cates,
            'selectcate'=> 0,
            'posts' => $posts
        ]);
    }
}
