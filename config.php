<?php
// config/services.php

// Imaginemos que tenemos una implementación real de la DB
$userRepository = new \App\Infrastructure\Persistence\MySQLUserRepository($pdo);

// El caso de uso recibe la implementación, pero solo le importa la Interfaz
$loginUseCase = new \App\Application\LoginUseCase($userRepository);