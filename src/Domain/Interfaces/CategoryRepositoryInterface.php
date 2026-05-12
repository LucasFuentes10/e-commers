<?php

namespace App\Domain\Interfaces;

interface CategoryRepositoryInterface
{
    public function getAll(): array;
}