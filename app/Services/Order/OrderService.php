<?php

namespace App\Services\Order;

use Stripe\Stripe;
use App\Models\Order;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\DB;
use App\Repositories\Order\OrderRepo;

class OrderService
{
    public function __construct(private OrderRepo $repo) {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Now accepts $addressId from the Controller
     */
    public function placeOrder(int $addressId)
    {
        $user = auth()->user();
        $cartItems = $user->cartProducts;

        if ($cartItems->isEmpty()) {
            throw new \Exception("Cart is empty");
        }

        return DB::transaction(function () use ($user, $cartItems, $addressId) {
            $totalAmount = 0;
            $itemsToSave = [];

            foreach ($cartItems as $product) {
                if ($product->stock < $product->pivot->qty) {
                    throw new \Exception("Product {$product->product_name} is out of stock.");
                }

                $discountedPrice = $product->price * (1 - ($product->discount / 100));
                $totalAmount += $discountedPrice * $product->pivot->qty;

                $itemsToSave[] = [
                    'product_id' => $product->id,
                    'qty' => $product->pivot->qty,
                    'price' => $discountedPrice,
                ];
                // Decrement Logic
                $product->decrement('stock', $product->pivot->qty);
            }

            try {
                $paymentIntent = PaymentIntent::create([
                    'amount' => $totalAmount * 100,
                    'currency' => 'usd',
                    // Added address_id to Stripe metadata for tracking
                    'metadata' => [
                        'user_id' => $user->id,
                        'address_id' => $addressId
                    ],
                ]);
            } catch (\Exception $e) {
                throw new \Exception("Stripe Error: " . $e->getMessage());
            }

            // Create order with the selected address_id
            $order = $this->repo->createOrder([
                'user_id' => $user->id,
                'address_id' => $addressId, // New field saved here
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_intent_id' => $paymentIntent->id
            ]);

            foreach ($itemsToSave as $item) {
                $this->repo->createOrderItem($order, $item);
            }

            $user->cartProducts()->detach();

            return [
                'order' => $order->load('items'),
                'client_secret' => $paymentIntent->client_secret
            ];
        });
    }

    public function getUserOrder(){
        return $this->repo->getUserOrders();
    }

    public function getOrderDetails($id){
        return $this->repo->findUserOrder($id);
    }

    public function changeStatus($id,string $status){

        $order = $this->repo->changeStatus($id,$status);

       // Logic: Trigger notification if shipped
        if ($status === 'shipped') {
            // Notification::send($order->user, new OrderShippedNotification($order));
        }

        return $order;
    }

    public function getAllOrdersForAdmin(){

        return $this->repo->getAllOrdersForAdmin();
    }
}
