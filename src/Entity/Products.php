<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductsRepository")
 */
class Products
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
    private $prod_name;

    /**
     * @ORM\Column(type="float")
     */
    private $prod_price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prod_descrip;

    /**
     * @ORM\Column(type="text")
     */
    private $prod_info;

    /**
     * @ORM\Column(type="boolean")
     */
    private $prod_stock;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prod_picture;

    /**
     * @ORM\Column(type="datetime")
     */
    private $prod_datecreat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categories", inversedBy="Products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity=Orders::class, inversedBy="products")
     */
    private $Orders;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProdName(): ?string
    {
        return $this->prod_name;
    }

    public function setProdName(string $prod_name): self
    {
        $this->prod_name = $prod_name;

        return $this;
    }

    public function getProdPrice(): ?float
    {
        return $this->prod_price;
    }

    public function setProdPrice(float $prod_price): self
    {
        $this->prod_price = $prod_price;

        return $this;
    }

    public function getProdDescrip(): ?string
    {
        return $this->prod_descrip;
    }

    public function setProdDescrip(string $prod_descrip): self
    {
        $this->prod_descrip = $prod_descrip;

        return $this;
    }

    public function getProdInfo(): ?string
    {
        return $this->prod_info;
    }

    public function setProdInfo(?string $prod_info): self
    {
        $this->prod_info = $prod_info;

        return $this;
    }

    public function getProdStock()
    {
        return $this->prod_stock;
    }

    public function setProdStock($prod_stock): self
    {
        $this->prod_stock = $prod_stock;

        return $this;
    }

    public function getProdPicture(): ?string
    {
        return $this->prod_picture;
    }

    public function setProdPicture(string $prod_picture): self
    {
        $this->prod_picture = $prod_picture;

        return $this;
    }

    public function getProdDatecreat(): ?\DateTimeInterface
    {
        return $this->prod_datecreat;
    }

    public function setProdDatecreat(\DateTimeInterface $prod_datecreat): self
    {
        $this->prod_datecreat = $prod_datecreat;

        return $this;
    }

    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    public function setCategories(?Categories $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function getOrders(): ?Orders
    {
        return $this->Orders;
    }

    public function setOrders(?Orders $Orders): self
    {
        $this->Orders = $Orders;

        return $this;
    }
}
