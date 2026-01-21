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
        return Auth::user()->orders()->with(['address','items.product.images'])->latest()->get();
    }

    public function findUserOrder($id)
    {
        return Auth::user()->orders()->with(['address','items.product.images'])->findOrFail($id);
    }

    public function changeStatus( $id, string $status){
        $order = Order::findOrFail($id);
        $order->update(['status' => $status]);
        return $order;
    }

    public function getAllOrdersForAdmin(){
        $order = Order::with(['user', 'address', 'items.product.images'])->latest()->paginate(15);

        return $order;
    }
}
