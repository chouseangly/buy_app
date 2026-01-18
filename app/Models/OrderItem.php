<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'price'
    ];

    /**
     * Get the product associated with the order item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
