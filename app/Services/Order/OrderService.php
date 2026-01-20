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
        // Initialize Stripe with your secret key
        Stripe::setApiKey(config('services.stripe.secret'));
    }

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

                $product->decrement('stock', $product->pivot->qty);
            }

            // 1. Create the Stripe Payment Intent
            try {
                $paymentIntent = PaymentIntent::create([
                    'amount' => $totalAmount * 100, // Stripe handles amounts in cents
                    'currency' => 'usd',
                    'metadata' => ['user_id' => $user->id],
                ]);
            } catch (\Exception $e) {
                throw new \Exception("Stripe Error: " . $e->getMessage());
            }

            // 2. Create the Order with the Stripe ID
            $order = $this->repo->createOrder([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_intent_id' => $paymentIntent->id // Store this to verify payment later
            ]);

            foreach ($itemsToSave as $item) {
                $this->repo->createOrderItem($order, $item);
            }

            $user->cartProducts()->detach();

            // 3. Return the order and the client_secret for the frontend
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
}
