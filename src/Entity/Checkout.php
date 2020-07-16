<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Checkout
{
    private $name;

    /**
     * @Assert\Luhn(message="Please check your credit card number.")
     * @Assert\NotBlank
     */
    private $ncb;

    /**
     * @Assert\Range(
     *  min = "now -1 day",
     *  max = "+3 years",
     * )
     */
    private $date;
    
    private $cvv;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getNcb()
    {
        return $this->ncb;
    }

    public function setNcb($ncb)
    {
        $this->ncb = $ncb;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getCvv()
    {
        return $this->cvv;
    }

    public function setCvv($cvv)
    {
        $this->cvv = $cvv;
    }
}
