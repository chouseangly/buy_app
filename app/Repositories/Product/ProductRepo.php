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
       return Product::with('images')
        ->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('product_name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        })
        ->when($filters['category_id'] ?? null, function ($query, $categoryId) {
            $query->where('category_id', $categoryId);
        })
        ->when($filters['min_price'] ?? null, function ($query, $minPrice) {
            $query->where('price', '>=', $minPrice);
        })
        ->when($filters['max_price'] ?? null, function ($query, $maxPrice) {
            $query->where('price', '<=', $maxPrice);
        })
        ->paginate(10);
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
