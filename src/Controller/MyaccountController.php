<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Comments;
use App\Entity\Categories;
use App\Service\Cart\Cart;
use App\Form\AddCommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class MyaccountController extends AbstractController
{
    /**
     * @Route("/myaccount", name="myaccount")
     */
    public function index(Cart $cart)
    {
        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal();

        return $this->render('myaccount/index.html.twig', [
            'controller_name' => 'MyaccountController',
            'panier' => $viewpanier,
            'total' => $total
        ]);
    }

    /**
     * @Route("/myaccount/addcomment", name="addcomment")
     */
    public function addcomment(Request $request, EntityManagerInterface $entityManager, Cart $cart, UserInterface $user)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        
        $addcomment = new Comments();
        $form = $this->createForm(AddCommentType::class, $addcomment);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $addcomment->setDatecreat(new \DateTime());
            $addcomment->setUser($user);
            $addcomment->setStatus('0');

            $entityManager->persist($addcomment);
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal();

        return $this->render('myaccount/addcomment.html.twig', [
            'cates' => $cates,
            'form' => $form->createView(),
            'selectcate'=> 0,
            'panier' => $viewpanier,
            'total' => $total
        ]);
    }
}
