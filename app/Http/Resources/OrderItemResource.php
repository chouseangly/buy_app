<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
   public function toArray(Request $request): array
{
    return [
        'id'       => $this->id,
        'qty'      => $this->qty,
        'price_at_purchase' => $this->price,
        // We pass the product through your existing ProductResource!
        'product'  => new ProductResource($this->whenLoaded('product')),
    ];
}
}
