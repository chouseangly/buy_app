<?php

namespace App\Models;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'product_name',
        'description',
        'price',
        'discount',
        'viewer',
        'is_active',
    ];

    public function images(){

        return $this->hasMany(ProductImage::class)->limit(5);
    }

    public function favoriteBy(){
        return $this->belongsToMany(User::class,'favorites')->WithTimestamps();
    }
}
