<?php

namespace App\Repositories\Cart;

class CartRepo{

    public function addToCart(int $productId, int $qty = 1){
        $user = auth()->user();

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
