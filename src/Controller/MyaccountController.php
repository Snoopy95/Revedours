<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\Comments;
use App\Entity\Categories;
use App\Service\Cart\Cart;
use App\Form\AddCommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MyaccountController extends AbstractController
{
    /**
     * @Route("/myaccount/myorders", name="myorders")
     */
    public function myorders(Cart $cart, UserInterface $user)
    {
        $myorders = $this->getDoctrine()->getRepository(Orders::class)->findBy(
            ['Users' => $user->getId()],
            ['datecreat' => 'DESC'],
        );
        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal();

        return $this->render('myaccount/myorders.html.twig', [
            'selectcate' => 0,
            'myorders' =>$myorders,
            'panier' => $viewpanier,
            'total' => $total
        ]);
    }

    /**
     * @Route("/myaccount/myorder/{id}", name="myorder")
     */
    public function myorder($id, Cart $cart, UserInterface $user)
    {
        $myorder = $this->getDoctrine()->getRepository(Orders::class)->find($id);
        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal();

        return $this->render('myaccount/myorder.html.twig', [
            'selectcate' => 0,
            'myorder' => $myorder,
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
