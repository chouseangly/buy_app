<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\OrderItemResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
   public function toArray(Request $request): array
{
    return [
        'order_id'     => $this->id,
        'total_paid'   => $this->total_amount,
        'status'       => $this->status,
        'order_date'   => $this->created_at->format('Y-m-d H:i:s'),
        // Map the items through the OrderItemResource
        'items'        => OrderItemResource::collection($this->whenLoaded('items')),
    ];
}
}
