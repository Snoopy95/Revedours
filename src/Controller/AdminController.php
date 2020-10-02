<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Roles;
use App\Entity\Orders;
use App\Entity\Comments;
use App\Entity\Products;
use App\Form\AddProdType;
use App\Entity\Categories;
use App\Form\UpdateProdType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="dashboard")
     */
    public function dashboard()
    {
        $listusers = $this->getDoctrine()->getRepository(User::class)->findAll();
        $listroles = $this->getDoctrine()->getRepository(Roles::class)->findAll();
        $listprods = $this->getDoctrine()->getRepository(Products::class)->findAll();
        $listcates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $listposts = $this->getDoctrine()->getRepository(Comments::class)->findAll();

        $nbusers = count($listusers);
        $nbprods = count($listprods);
        $nbcates = count($listcates);
        $nbposts = count($listposts);

        if ($nbprods>0 && $nbcates>0) {
            foreach ($listcates as $cate) {
                $idcat = $cate->getId();
                $key = $cate->getCateName();
                $count = 0;
                foreach ($listprods as $value) {
                    $val = $value->getCategories();
                    $val = $val->getId();
                    if ($val == $idcat) {
                        $count ++;
                    }
                }
                $prodsbycates[$key]= $count;
            }
        }

        return $this->render('admin/dashboard.html.twig', [
            'nbprods' => $nbprods,
            'prodsbycates' => $prodsbycates,
            'listusers' => $listusers,
            'listprods' => $listprods,
            'nbusers' => $nbusers,
            'nbposts' => $nbposts
        ]);
    }

    /**
     * @Route("/admin/listprods", name="listprods")
     */
    public function listprods()
    {
        $listprods = $this->getDoctrine()->getRepository(Products::class)->findAll();

        return $this->render('admin/listprods.html.twig', [
        'allprods' => $listprods,
        ]);
    }

    /**
     * @Route("/admin/delprod/{id}", name="delprod")
     */
    public function delprod($id, EntityManagerInterface $entityManager)
    {
        $delprod = $this->getDoctrine()->getRepository(Products::class)->find($id);

        $entityManager->remove($delprod);
        $entityManager->flush();
        $this->addFlash('info', 'Article bien supprimer');

        return $this->redirectToRoute('listprods');
    }

    /**
     * @Route("/admin/updateprod/{id}", name="updateprod")
     */
    public function updateprod(EntityManagerInterface $entityManager, Request $request, $id)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $udprod= $this->getDoctrine()->getRepository(Products::class)->find($id);

        $form = $this->createForm(UpdateProdType::class, $udprod);
        $form->handleRequest($request);
        $this->addFlash('info', 'Update en cours');

        if ($form ->isSubmitted() && $form->isValid()) {
            if (isset($_SESSION['image'])) {
                $image= $_SESSION['image'];
                unset($_SESSION['image']);
            } else {
                $image = $udprod -> getProdPicture();
            }

            $udprod -> setProdDatecreat(new \DateTime());
            $udprod -> setProdPicture($image);

            $entityManager->flush();
            $this->addFlash('info', 'Mise à jour reussi');

            return $this->redirectToRoute('listprods');
        }
        return $this->render('admin/updateprod.html.twig', [
            'form' => $form->createView(),
            'cates'=> $cates,
            'prod' => $udprod]);
    }

    /**
     * @Route("/admin/addprod", name="addprod")
     */
    public function addprod(EntityManagerInterface $entityManager, Request $request)
    {
        $addprod = new Products();

        $form = $this->createForm(AddProdType::class, $addprod);
        $form->handleRequest($request);
        $this->addFlash('info', 'Ajout d\'un produit');

        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($_SESSION['image'])) {
                $image= $_SESSION['image'];
                unset($_SESSION['image']);
            } else {
                $image = "default.png";
            }

            $addprod -> setProdDatecreat(new \DateTime());
            $addprod -> setProdPicture($image);

            $entityManager->persist($addprod);
            $entityManager->flush();
            $this->addFlash('info', 'Produit bien enregistrée');

            return $this->redirectToRoute('listprods');
        }
        return $this->render('admin/addprod.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/upload", name="upload")
     */
    public function upload()
    {
        $imagePath = isset($_FILES["file"]["name"]) ? $_FILES["file"]["name"] : "default.png";
        $_SESSION['image'] = $imagePath;
        $targetPath = "../public/uploads/";
        $tempFile = $_FILES['file']['tmp_name'];
        $targetFile = $targetPath . $_FILES['file']['name'];

        if (move_uploaded_file($tempFile, $targetFile)) {
            echo "true";
        } else {
            echo "false";
        }
    }

    /**
     * @Route("/admin/listecates", name="listecates")
     */
    public function listcates()
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $this->addFlash('info', 'Format d\'image 1200x400 !!');

        return $this->render('admin/categories.html.twig', [
            'cates'=> $cates,
            ]);
    }

    /**
     * @Route("/admin/updatecate/{id}", name="updatecate")
     */
    public function updatecate(EntityManagerInterface $entityManager, $id)
    {
        $this->addFlash('info', 'Vous n\'avez rien modifier');

        $cate = $this->getDoctrine()->getRepository(Categories::class)->find($id);

        if (isset($_POST['name']) && (!empty($_POST['name']))) {
            $name = $_POST['name'];
            $this->addFlash('info', 'Modification bien effectuer');
        } else {
            $name = $cate -> getCateName();
        }
        $cate -> setCateName($name);

        if (isset($_POST['descrip']) && (!empty($_POST['descrip']))) {
            $descrip = $_POST['descrip'];
            $this->addFlash('info', 'Modification bien effectuer');
        } else {
            $descrip = $cate -> getCateDescrip();
        }
        $cate -> setCateDescrip($descrip);

        if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
            $imagePath = $_FILES["file"]["name"];
            $targetPath = "../public/images/";
            $tempFile = $_FILES['file']['tmp_name'];
            $targetFile = $targetPath . $_FILES['file']['name'];
            if (move_uploaded_file($tempFile, $targetFile)) {
                echo "true";
            } else {
                echo "false";
            }
        } else {
            $imagePath = $cate -> getCatePicture();
        }
        $cate -> setCatePicture($imagePath);
        $entityManager->flush();

        return $this->redirectToRoute('listecates');
    }

    /**
     * @Route("/admin/galerie", name="galerie")
     */
    public function galerie()
    {
        $listprods = $this->getDoctrine();
        $prods = $listprods->getRepository(Products::class)->findAll();
        $this->addFlash('info', 'Attention chaque suppression est definitive !!');

        $adresse = "../public/uploads/";
        $handle = opendir($adresse);
        $i = 0;

        while (false !== ($Fichier = readdir($handle))) {
            if ($Fichier != "." && $Fichier != "..") {
                foreach ($prods as $resul) {
                    $em = $resul ->getprodPicture();
                    $refprod = $resul ->getId();
                    if ($em == $Fichier) {
                        $del = false;
                        $href= "/product/" . $refprod ;
                        break;
                    } else {
                        $del = true;
                        $href= null;
                    }
                }
                $files[] = [
                'name' => $Fichier,
                'del' => $del,
                'href' => $href,
                'numb' => $i
                ];
                $i++;
            }
        }
        closedir($handle);

        return $this->render('admin/galerie.html.twig', [
            'files' => $files,
        ]);
    }

    /**
     * @Route("/admin/delimage/{namefile}", name="delimage")
     */
    public function delimage($namefile)
    {
        $adresse = "../public/uploads/";
        $handle = opendir($adresse);
        $em = $adresse . $namefile;
        unlink($em);
        closedir($handle);
        $this->addFlash('info', 'Image supprimer');

        return $this->redirectToRoute('galerie');
    }

    /**
     * @Route("/admin/listusers", name="listusers")
     */
    public function listusers()
    {
        $this->getDoctrine()->getRepository(Roles::class)->findAll();
        $listusers = $this->getDoctrine()->getRepository(User::class)->findBy(
            ['roles' => 1],
            ['datecreat' => 'ASC']
        );
        $listadmins = $this->getDoctrine()->getRepository(User::class)->findBy(
            ['roles' => 2],
            ['datecreat' => 'ASC']
        );

        return $this->render('admin/listusers.html.twig', [
            'listusers' => $listusers,
            'listadmins' => $listadmins
        ]);
    }

    /**
     * @Route("/admin/comments/{type}", name="comments")
     */
    public function comments($type)
    {
        if ($type == 0) {
                $critere['by'] =
                ['status' => 0];
        } elseif ($type == 1) {
            $critere['by'] =
                ['status' => 1];
        } elseif ($type == 2) {
            $critere['by'] =
                ['status' => 2];
        } else {
            $this->addFlash(
                'info',
                'Il y eu un probleme dans les commentaires'
            );
            return $this->redirectToRoute('dashboard');
        };
        $critere['ordre'] = ['datecreat' => 'DESC'];

        $listcomments = $this->getDoctrine()->getRepository(Comments::class)->findBy($critere['by'], $critere['ordre']);

        return $this->render('admin/comments.html.twig', [
            'comments' => $listcomments
        ]);
    }

    /**
     * @Route("/admin/publier/{id}/{status}", name="publier")
     */
    public function publier($id, $status, EntityManagerInterface $entityManager)
    {
        $post = $this->getDoctrine()->getRepository(Comments::class)->find($id);

        if ($status == 1 || $status == 2 || $status == 0) {
            $post->setStatus($status);
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Status modifier'
            );
            $status = 0;
        } elseif ($status == 3) {
            $entityManager->remove($post);
            $entityManager->flush();
            $status = 2;
        }
        return $this->redirectToRoute('comments', ['type' => $status]);
    }

    /**
     * @Route("/admin/upuser/{id}/{role}", name="upuser")
     */
    public function upuser($id, $role, EntityManagerInterface $entityManager)
    {
        if ($role == 0) {
            $typerole = $this->getDoctrine()->getRepository(Roles::class)->find(1);
        } elseif ($role == 1) {
            $typerole = $this->getDoctrine()->getRepository(Roles::class)->find(2);
        } else {
            return $this->redirectToRoute('listusers');
        }

        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $user->setRoles($typerole);
        $entityManager->flush();

        return $this->redirectToRoute('listusers');
    }

    /**
     * @Route("/admin/commandes", name="commandes")
     */
    public function commandes()
    {
        $cmdpasse = $this->getDoctrine()->getRepository(Orders::class)->findBy(
            ['status' => 1],
            ['datecreat' => 'ASC']
        );
        $cmdencours = $this->getDoctrine()->getRepository(Orders::class)->findBy(
            ['status' => 2],
            ['datecreat' => 'ASC']
        );
        $cmdexp = $this->getDoctrine()->getRepository(Orders::class)->findBy(
            ['status' => 3],
            ['datecreat' => 'ASC']
        );

        return $this->render('admin/commandes.html.twig', [
            'cmdpasse' => $cmdpasse,
            'cmdencours' => $cmdencours,
            'cmdexp' => $cmdexp
        ]);
    }

    /**
     * @Route("/admin/detcmd/{id}", name="detcmd")
     */
    public function detcmd($id)
    {
        $cmd = $this->getDoctrine()->getRepository(Orders::class)->find($id);

        return $this->render('admin/detcmd.html.twig', [
            'cmd' => $cmd
        ]);
    }

    /**
     * @Route("/admin/cmdstatus/{id}/{status}", name="cmdstatus")
     */
    public function cmdstatus($id, $status, EntityManagerInterface $em, Request $request, MailerInterface $mailer)
    {
        $lasturl = $request->headers->get('referer');
        $url = strstr($lasturl, '/admin');

        $cmd = $this->getDoctrine()->getRepository(Orders::class)->find($id);
        $cmd->setStatus($status);

        $em->flush();

        // $email = (new Email())
        //     ->from('Revedours@gmail.com')
        //     ->to($cmd->getUsers()->getEmail())
        //     ->subject('Commande expediée')
        //     ->html('<h3>Votre commande à ete remis au transporteur</h3>
        //                 <p> Votre commande a bien ete valider et sera traite dans les plus bref delais.</p>
        //                 <p> Vous recevrez un mail lors de l\'envoie de votre commande</p>
        //                 <p>Cordialemant l\'equipe Reve D\'Ours</p> ');
        // $mailer->send($email);

        return $this->redirect($url);
    }
}
