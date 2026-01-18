<?php

namespace App\Repositories\Order;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderRepo
{
    public function createOrder(array $data): Order
    {
        return Order::create($data);
    }

    public function createOrderItem(Order $order, array $itemData)
    {

        return $order->items()->create($itemData);
    }

    public function getUserOrders()
    {
        return Auth::user()->orders()->with(['items.product.images'])->latest()->get();
    }

    public function findUserOrder($id)
    {
        return Auth::user()->orders()->with(['items.product.images'])->findOrFail($id);
    }
}
