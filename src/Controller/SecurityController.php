<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\UserType;
use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/newuser", name=",newuser")
     */
    public function newuser(Request $request, EntityManagerInterface $entity, UserPasswordEncoderInterface $encoder)
    {
        $listcate = $this->getDoctrine();
        $cates = $listcate->getRepository(Categories::class)->findAll();

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $user->setDatecreat(new \DateTime());
            $em= $this->getDoctrine();
            $role = $em->getRepository(Role::class)->find(1);
            $user->setRole($role);

            $entity->persist($user);
            $entity->flush();

            return $this->redirectToRoute('index');
        }
    
        return $this->render('security/newuser.html.twig', [
            'form' => $form->createView(),
            'cates' => $cates,
            'selectcate'=> 0
        ]);
    }
}
