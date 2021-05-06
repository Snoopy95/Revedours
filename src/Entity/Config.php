<?php

namespace App\Entity;

use App\Repository\ConfigRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConfigRepository::class)
 */
class Config
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $configname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $valeur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConfigname(): ?string
    {
        return $this->configname;
    }

    public function setConfigname(string $configname): self
    {
        $this->configname = $configname;

        return $this;
    }

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(string $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }
}
