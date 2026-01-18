<?php

namespace App\Http\Controllers\Cart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Services\Cart\CartService;

class CartController extends Controller
{
    public function addToCart(Request $request,$id,CartService $service){
        $request->validate([
            'qty' => 'required|integer|min:1'
        ]);

             $service->addToCart($id,$request->qty);

        return response()->json([
            'message'=>'add product into cart successfully'
        ],201);
    }

    public function removeFromCart($id,CartService $service){

       $deleted = $service->removeFromCart($id);

        if ($deleted == 0) {
            return response()->json(['message' => 'Item not found in cart'], 404);
        }

        return response()->json([
            'message' => 'remove product from cart successfully'
        ]);
    }

    public function getCarts(CartService $service){
        $carts = $service->getAll();

        return response()->json([
            'data' => ProductResource::collection($carts),
            'message' => 'get all product cart successfully'
        ]);

    }
}
