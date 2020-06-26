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

    protected $cart;
    protected $em;

    public function __construct(Cart $cart, EntityManagerInterface $em)
    {
        $this->cart = $cart;
        $this->em = $em;
    }

    /**
     * @Route("/newuser", name="newuser")
     */
    public function newuser(Request $request, UserPasswordEncoderInterface $encoder)
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

            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('index');
        }

        $viewpanier = $this->cart->getViewCart();
        $total = $this->cart->getTotal($viewpanier);

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
    public function mdpoublie(Request $request, TokenGeneratorInterface $tokenGenerator, MailerInterface $mailer)
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
                $this->em->persist($user);
                $this->em->flush();
                
                $url = $this->generateUrl('resetpwd', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                $email = (new Email())
                ->from('Boxalacon@gmail.com')
                ->to($user->getEmail())
                ->subject('Mot de passe perdu !!')
                ->html('<h2> Bonjours ' . $user->getUsername() . '</h2>
                    <p> Cliquez sur le lien suivant pour réinitialiser votre mot de passe </p>
                    <h3>'. $url . '</h3>');
                $mailer->send($email);
                
                $this->addFlash('success', 'Mail bien envoyer');
                return $this->redirectToRoute('index');
            } else {
                $this->addFlash('mailerror', 'L\'adresse d\'email saisie n\'existe pas !!!');
                return $this->redirectToRoute('mdpoublie');
            }
        };

        $viewpanier = $this->cart->getViewCart();
        $total = $this->cart->getTotal();

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
    public function resetpwd($token, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();

        $user= $this->getDoctrine()->getRepository(User::class)->findOneBy(
            ['resetpwd' => $token]
        );

        if (!$user) {
            $this->addFlash('danger', 'Cette page n\'existe pas !!!');
            return $this->redirectToRoute('index');
        }

        $form = $this->createForm(ResetPwdType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setResetpwd(null);

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Votre mot de passe a bien été changé !!!');
            return $this->redirectToRoute('index');
        }

        $viewpanier = $this->cart->getViewCart();
        $total = $this->cart->getTotal();
        
        return $this->render('security/resetpwd.html.twig', [
            'cates' => $cates,
            'selectcate' => 0,
            'panier' => $viewpanier,
            'total' => $total,
            'form' => $form->createView()
        ]);
    }
}
