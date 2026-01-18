<?php

namespace App\Repositories\Favorite;

class FavoriteRepo{
    public function addFavorite($id){

        
        $user = auth()->user();
        // toggle() adds the record if missing, or removes it if it exists

        //toggle is the build in method of laravel that work only Many-To-Many relationship

        //test the same url on postman if firstclick means add favorite and second click remove favorite


        return $user->favoriteProducts()->toggle($id);
    }

    public function getFavorites(){

        // Returns the full product objects that the user favorited
        return auth()->user()->favoriteProducts()->with('images')->paginate(10);
    }
}
