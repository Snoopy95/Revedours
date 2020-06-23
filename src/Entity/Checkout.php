<?php

namespace App\Entity;

class Checkout
{
    private $name;

    private $ncb;

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
