<?php

namespace App\Http\Controllers\Product;

use App\Http\Resources\ProductResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Services\Product\ProductService;

class ProductController extends Controller
{
    public function addProduct(StoreProductRequest $request, ProductService $service)
    {
        $product = $service->createProduct($request);
        return response()->json([
            'data' => new ProductResource($product),
            'message' => 'post product successfully'
        ], 201);
    }

    public function getAllProducts(ProductService $service)
    {
        $products = $service->getAllProducts();

        if (!$products) {
            return response()->json(
                [
                    'message' => 'post not found'
                ],
                404
            );
        }

        return response()->json(
            [
                'data' => ProductResource::collection($products),
                'message' => 'get all product successfully'
            ]
        );
    }

    public function updateProduct(StoreProductRequest $request,$id,ProductService $service){
        $product = $service->update($id,$request);

           if (!$product) {
            return response()->json(
                [
                    'message' => 'post not found'
                ],
                404
            );
        }

        return response()->json([
            'data' => new ProductResource($product),
            'message' => 'update product successfully'
        ], 201);
    }

    public function deleteProduct($id,ProductService $service){
        $product = $service->delete($id);

        if (!$product) {
            return response()->json(
                [
                    'message' => 'post not found'
                ],
                404
            );
        }

         return response()->json([
            'message' => 'delete product successfully'
        ], 201);
    }
}
