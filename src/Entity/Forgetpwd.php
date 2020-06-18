<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Forgetpwd
{
    private $email;

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
}
