<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoriesRepository")
 */
class Categories
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $cate_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cate_descrip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cate_picture;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Products", mappedBy="categories")
     */
    private $Products;

    public function __construct()
    {
        $this->Products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCateName(): ?string
    {
        return $this->cate_name;
    }

    public function setCateName(string $cate_name): self
    {
        $this->cate_name = $cate_name;

        return $this;
    }

    public function getCateDescrip(): ?string
    {
        return $this->cate_descrip;
    }

    public function setCateDescrip(string $cate_descrip): self
    {
        $this->cate_descrip = $cate_descrip;

        return $this;
    }

    public function getCatePicture(): ?string
    {
        return $this->cate_picture;
    }

    public function setCatePicture(string $cate_picture): self
    {
        $this->cate_picture = $cate_picture;

        return $this;
    }

    /**
     * @return Collection|Products[]
     */
    public function getProducts(): Collection
    {
        return $this->Products;
    }

    public function addProduct(Products $product): self
    {
        if (!$this->Products->contains($product)) {
            $this->Products[] = $product;
            $product->setCategories($this);
        }

        return $this;
    }

    public function removeProduct(Products $product): self
    {
        if ($this->Products->contains($product)) {
            $this->Products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getCategories() === $this) {
                $product->setCategories(null);
            }
        }

        return $this;
    }
}
