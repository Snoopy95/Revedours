<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Roles;
use App\Form\UserType;
use App\Entity\Forgetpwd;
use App\Entity\Categories;
use App\Form\ResetPwdType;
use App\Service\Cart\Cart;
use App\Form\ForgetPwdType;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/newuser", name="newuser")
     */
    public function newuser(Request $request, EntityManagerInterface $entity, UserPasswordEncoderInterface $encoder, Cart $cart)
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
            $em = $this->getDoctrine();
            $role = $em->getRepository(Roles::class)->find(1);
            $user->setRoles($role);

            $entity->persist($user);
            $entity->flush();

            return $this->redirectToRoute('index');
        }

        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal($viewpanier);

        return $this->render('security/newuser.html.twig', [
            'form' => $form->createView(),
            'cates' => $cates,
            'selectcate' => 0,
            'panier' => $viewpanier,
            'total' => $total
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        $this->getDoctrine()->getRepository(Roles::class)->findAll();
        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        #code...
    }

    /**
     * @Route("/forgetpwd", name="mdpoublie")
     */
    public function mdpoublie(Cart $cart, Request $request, TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $entity, MailerInterface $mailer)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();

        $useremail= new Forgetpwd();
        $form = $this->createForm(ForgetPwdType::class, $useremail);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $email = $useremail->getEmail();
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(
                ['email' => $email]
            );
            if ($user) {
                $token = $tokenGenerator->generateToken();
                $user->setResetpwd($token);
                $entity->persist($user);
                $entity->flush();
                
                $url = $this->generateUrl('resetpwd', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                $email = (new Email())
                ->from('Boxalacon@gmail.com')
                ->to($user->getEmail())
                ->subject('Mot de passe perdu !!')
                ->html('<h2> Bonjours ' . $user->getUsername() . '</h2>
                    <p> Cliquez sur le lien suivant pour réinitialiser votre mot de passe </p>
                    <h3>'. $url . '</h3>');
                $mailer->send($email);
                
                $this->addFlash('index', 'Verifier votre boite mail !!!');
                return $this->redirectToRoute('index');
            } else {
                $this->addFlash('mailerror', 'L\'adresse d\'email saisie n\'existe pas !!!');
                return $this->redirectToRoute('mdpoublie');
            }
        };

        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal();

        return $this->render('security/forgetpwd.html.twig', [
            'cates'=> $cates,
            'selectcate' => 0,
            'panier' => $viewpanier,
            'total' => $total,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/resetpwd/{token}", name="resetpwd")
     */
    public function resetpwd($token, Cart $cart, Request $request, EntityManagerInterface $entity, UserPasswordEncoderInterface $encoder)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();

        $user= $this->getDoctrine()->getRepository(User::class)->findOneBy(
            ['resetpwd' => $token]
        );

        if (!$user) {
            $this->addFlash('index', 'Erreur de token');
            return $this->redirectToRoute('index');
        }

        $form = $this->createForm(ResetPwdType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setResetpwd(null);

            $entity->persist($user);
            $entity->flush();

            $this->addFlash('index', 'Mot de passe changer !!!');
            return $this->redirectToRoute('index');
        }

        $viewpanier = $cart->getViewCart();
        $total = $cart->getTotal();
        
        return $this->render('security/resetpwd.html.twig', [
            'cates' => $cates,
            'selectcate' => 0,
            'panier' => $viewpanier,
            'total' => $total,
            'form' => $form->createView()
        ]);
    }
}
