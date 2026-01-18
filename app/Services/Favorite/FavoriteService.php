<?php

namespace App\Services\Favorite;

use App\Models\Product;
use App\Repositories\Favorite\FavoriteRepo;

class FavoriteService{
    public function __construct(private FavoriteRepo $repo){}

    public function addFavorite($id){
        Product::findOrFail($id);

        return $this->repo->addFavorite($id);
    }

    public function getFavorites(){
        
        return $this->repo->getFavorites();
    }

}
