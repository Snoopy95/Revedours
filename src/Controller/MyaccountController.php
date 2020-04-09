<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Comments;
use App\Entity\Categories;
use App\Form\AddCommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MyaccountController extends AbstractController
{
    /**
     * @Route("/myaccount", name="myaccount")
     */
    public function index()
    {
        return $this->render('myaccount/index.html.twig', [
            'controller_name' => 'MyaccountController',
        ]);
    }

    /**
     * @Route("/myaccount/addcomment/{id}", name="addcomment")
     */
    public function addcomment($id, Request $request, EntityManagerInterface $entityManager)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        
        $addcomment = new Comments();
        $form = $this->createForm(AddCommentType::class, $addcomment);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $addcomment->setDatecreat(new \DateTime());
            $user= $this->getDoctrine()->getRepository(User::class)->find($id);
            $addcomment->setUser($user);
            $addcomment->setStatus('0');

            $entityManager->persist($addcomment);
            $entityManager->flush();

            return $this->redirectToRoute('index');
            // return $this->render('myaccount/index.html.twig', [
            //     'request' => $addcomment
            // ]);
        }

        return $this->render('myaccount/addcomment.html.twig', [
            'cates' => $cates,
            'form' => $form->createView(),
            'selectcate'=> 0
        ]);
    }
}
