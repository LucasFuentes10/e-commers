<?php
// src/Domain/Interfaces/CategoryRepositoryInterface.php
namespace App\Domain\Interfaces;

interface CategoryRepositoryInterface
{
    public function getAll(): array;
}