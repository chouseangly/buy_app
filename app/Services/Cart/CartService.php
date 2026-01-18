<?php

namespace App\Services\Cart;

use App\Repositories\Cart\CartRepo;

class CartService{
    public function __construct(private CartRepo $repo){}

    public function addToCart($id,$qty){

        return $this->repo->addToCart($id,$qty);
    }

    public function removeFromCart($id){

        return $this->repo->removeFromCart($id);
    }

    public function getAll(){

        return $this->repo->getAll();

    }

}
