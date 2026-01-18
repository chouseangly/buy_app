<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\ImageStorage\ImageStorageInterface;

class ProductRepo{
    public function __construct(private ImageStorageInterface $imageStorage ){}


    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function addImages(Product $product,array $images){
        foreach($images as $image){
            $stored = $this->imageStorage->store($image);

            $product->images()->create([
                'img_url' => $stored['url'], // Use 'url' from the storage service
            ]);
        }
    }

    public function getAll(){
        return Product::with('images')->paginate(10);
    }

    public function update($id,array $data): Product
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function delete($id){
        
        $product = Product::findOrFail($id);

       return $product->delete();

    }
}
