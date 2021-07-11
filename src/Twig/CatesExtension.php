<?php

namespace App\Twig;

use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatesExtension extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getCates():array
    {
        return $this->em->getRepository(Categories::class)->findAll();
    }
}
