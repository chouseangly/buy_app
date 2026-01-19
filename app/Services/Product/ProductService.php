<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Repositories\Product\ProductRepo;


class ProductService{
    public function __construct(private ProductRepo $repo){}

    public function createProduct($request){
        return DB::transaction(function () use ($request){
            $product = $this->repo->createProduct([
                'product_name' => $request->product_name,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'discount' => $request->discount,
                'user_id' => auth()->id(),
                'is_active' => $request->is_active,
                'viewer' => $request->viewer
            ]);

            if($request->hasFile('images')){
                $this->repo->addImages($product,$request->file('images'));
            }
            return $product->load('images');
        });
    }

    public function getAllProducts(array $filters = []){
        return $this->repo->getAll($filters);
    }

    public function update($id,$request){
        return DB::transaction(function () use ($id,$request) {
            $product = $this->repo->update($id,[
                'product_name' => $request->product_name,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'discount' => $request->discount,
                'user_id' => auth()->id(),
                'is_active' => $request->is_active,
                'viewer' => $request->viewer
            ]);

            if($request->hasFile('images')){
                $this->repo->addImages($product,$request->file('images'));
            }
            return $product->load('images');
        });
    }

    public function delete($id){
        return DB::transaction(function() use($id){
            return $this->repo->delete($id);
        });
    }

}
