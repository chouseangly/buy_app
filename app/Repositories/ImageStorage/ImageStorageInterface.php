<?php

namespace App\Repositories\ImageStorage;

interface ImageStorageInterface
{
    public function store($image): array;
}
