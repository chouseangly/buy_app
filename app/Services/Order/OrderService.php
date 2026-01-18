<?php

namespace App\Services\Order;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Repositories\Order\OrderRepo;

class OrderService
{
    public function __construct(private OrderRepo $repo) {}

    public function placeOrder()
    {
        $user = auth()->user();
        $cartItems = $user->cartProducts;

        if ($cartItems->isEmpty()) {
            throw new \Exception("Cart is empty");
        }

        return DB::transaction(function () use ($user, $cartItems) {
            $totalAmount = 0;
            $itemsToSave = [];

            foreach ($cartItems as $product) {
                $discountedPrice = $product->price * (1 - ($product->discount / 100));
                $totalAmount += $discountedPrice * $product->pivot->qty;

                $itemsToSave[] = [
                    'product_id' => $product->id,
                    'qty' => $product->pivot->qty,
                    'price' => $discountedPrice,
                ];
            }

            // Use Repository to create the main order
            $order = $this->repo->createOrder([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'pending'
            ]);

            // Use Repository to create each item
            foreach ($itemsToSave as $item) {
                $this->repo->createOrderItem($order, $item);
            }

            // Clear Cart
            $user->cartProducts()->detach();

            return $order->load('items');
        });
    }

    public function getUserOrder(){
        return $this->repo->getUserOrders();
    }

    public function getOrderDetails($id){
        
        return $this->repo->findUserOrder($id);
    }
}
