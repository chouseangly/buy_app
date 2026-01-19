<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\ImageStorage\ImageStorageInterface;

class CategoryRepo{
    public function __construct(private ImageStorageInterface $imageStorage ){}

    public function addImage(Category $category ,  $image){
        $stored = $this->imageStorage->store($image);

        $category->update([
            'img' => $stored['url']
        ]);

    }

    public function createCategory(array $data){
        return Category::create($data);
    }

}
