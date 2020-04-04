<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Roles;
use App\Entity\Products;
use App\Entity\Categories;
use App\Form\AddProdType;
use App\Form\CategoriesType;
use App\Form\UpdateProdType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// session_start();

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="dashboard")
     */
    public function dashboard()
    {
        return $this->render('Admin/dashboard.html.twig');
    }

    /**
     * @Route("/admin/listprods", name="listprods")
     */
    public function listprods()
    {
        $allprod = $this->getDoctrine();
        $listprods = $allprod->getRepository(Products::class)->findAll();
        return $this->render('Admin/listprods.html.twig', [
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
    public function updateprod(EntityManagerInterface $entityManager, $id, Request $request)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        
        $updateprod = $this->getDoctrine();
        $udprod= $updateprod->getRepository(Products::class)->find($id);

        $udprod -> setProdDatecreat(new \DateTime());

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
            $udprod -> setProdPicture($image);
            $entityManager->flush();
            $this->addFlash('info', 'Mise à jour reussi');

            return $this->redirectToRoute('listprods');
        }
        return $this->render('Admin/updateprod.html.twig', [
            'form'=>$form->createView(),
            'cates'=> $cates,
            'prod' => $udprod]);
    }

    /**
     * @Route("/admin/addprod", name="addprod")
     */
    public function addprod(EntityManagerInterface $entityManager, Request $request)
    {
        $cates = $this->getDoctrine()->getRepository(Categories::class)->findAll();

        $addprod = new Products();
        $addprod -> setProdDatecreat(new \DateTime());

        $form = $this->createForm(AddProdType::class, $addprod);
        $form->handleRequest($request);
        $this->addFlash('info', 'Ajout d\'un produit');

        if ($form ->isSubmitted() && $form->isValid()) {
            if (isset($_SESSION['image'])) {
                $image= $_SESSION['image'];
                unset($_SESSION['image']);
            } else {
                $image = "default.png";
            }

            $addprod -> setProdPicture($image);
            $entityManager->persist($addprod);
            $entityManager->flush();
            $this->addFlash('info', 'Produit bien enregistrée');

            return $this->redirectToRoute('listprods');
        }
        return $this->render('Admin/addprod.html.twig', [
            'form'=>$form->createView(),
            'cates'=> $cates,
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
        return $this->redirectToRoute('addprod');
    }

    /**
     * @Route("/admin/reload", name="reload")
     */
    public function reload()
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
        return $this->redirectToRoute('updateprod');
    }

    /**
     * @Route("/admin/listecates", name="listecates")
     */
    public function listcates()
    {
        $listcate = $this->getDoctrine();
        $cates = $listcate->getRepository(Categories::class)->findAll();
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

        $em = $this->getDoctrine();
        $cate = $em->getRepository(Categories::class)->find($id);

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
                    $refcate = $resul ->getCategories();
                    if ($em == $Fichier) {
                        $del = false;
                        $href= "/product/" . $refprod  . "/" . $refcate -> getId();
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
     * @Route("/admin/delimage/{id}", name="delimage")
     */
    public function delimage($id)
    {
        $adresse = "../public/uploads/";
        $handle = opendir($adresse);
        $em = $adresse . $id;
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
        $listusers = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('admin/testdump.html.twig', [
            'listusers' => $listusers,
        ]);
    }
}
