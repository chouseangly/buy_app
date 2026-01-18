<?php

namespace App\Http\Controllers\Favorite;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Services\Favorite\FavoriteService;

class FavoriteController extends Controller
{
    //this message mean use for add and remove favorite from product
    public function toggleFavorite($id,FavoriteService $service){

        $status = $service->addFavorite($id);

        $message = count($status['attached']) >0 ? 'Add to favorites' : 'remove from favorite';

        return response()->json([
            'message' => $message,
        ],201);


    }

    public function getFavorites(FavoriteService $service){
        $favorite = $service->getFavorites();

        return response()->json(
            [
                'data' => ProductResource::collection($favorite),
                'message' => 'get all product favorite successfully'
            ]
        );
    }
}
