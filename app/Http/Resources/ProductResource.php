<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $discountedPrice = $this->price * (1 - ($this->discount / 100));
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'description' => $this->description,
            'price' => $this->price,
            'discount' => $this->discount . '%',
            'sale_price' => round($discountedPrice, 2),
            'user_id'  => auth()->id(),
            'is_active' => (bool) $this->is_active,
            'viewer' => $this->viewer,
            // This turns the images collection into a simple list of URLs
            'images' => $this->images->pluck('img_url'),
            'created_at' => $this->created_at,
        ];
    }
}
