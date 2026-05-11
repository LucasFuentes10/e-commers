<?php
// src/Domain/Entities/User.php

namespace App\Domain\Entities;

class User {
    private $id;
    private $name;
    private $email;
    private $password;

    public function __construct($name, $email, $password, $id = null) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->id = $id;
    }

    public function getHashedPassword() {
        return password_hash($this->password, PASSWORD_BCRYPT);
    }
    
    // Getters...
}