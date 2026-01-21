<?php

namespace App\Repositories\Cart;

use App\Models\Product;
use Exception;

class CartRepo{

    public function addToCart(int $productId, int $qty = 1){

        $user = auth()->user();

        // Fetch the product to check its current stock
        $product = Product::findOrFail($productId);
        // Validation: Check if requested quantity exceeds available stock
        if ($product->stock < $qty) {
            throw new Exception("Only {$product->stock} items available in stock.");
        }

        // syncWithoutDetaching adds the product or updates it without removing others

        //Update the entire cart at once.

        return $user->cartProducts()->syncWithoutDetaching([

            //This tells Laravel which product is being added to the pivot table
                $productId => ['qty'=>$qty]
        ]);
    }

    public function removeFromCart($productId){

        //build in method  detach(): Remove a product from the user's cart.

        return auth()->user()->cartProducts()->detach($productId);
    }

    public function getAll(){
        return auth()->user()->cartProducts()->with('images')->paginate(10);
    }
}
