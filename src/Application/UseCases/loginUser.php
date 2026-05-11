<?php
// src/Application/UseCases/LoginUser.php

namespace App\Application\UseCases;

class LoginUser {
    private $userRepository;

    public function __construct($userRepository) {
        $this->userRepository = $userRepository;
    }

    public function execute($email, $password) {
        $user = $this->userRepository->findByEmail($email);
        
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        
        return null;
    }
}