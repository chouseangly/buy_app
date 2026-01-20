<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    /**
     * Handle the incoming Stripe webhook request.
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            // Verify the webhook signature to ensure it came from Stripe
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (SignatureVerificationException $e) {
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        // Handle the specific event type
        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;
            $this->processOrder($paymentIntent);
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Update the order status in the database.
     */
    protected function processOrder($paymentIntent)
    {
        // Find the order using the payment_intent_id stored during checkout
        $order = Order::where('payment_intent_id', $paymentIntent->id)->first();

        if ($order) {
            if ($order->status === 'pending') {
                $order->update(['status' => 'paid']);
                Log::info("Order ID {$order->id} has been marked as PAID via Stripe Webhook.");
            }
        } else {
            Log::warning("Webhook Received: No order found for Payment Intent ID: {$paymentIntent->id}");
        }
    }
}
