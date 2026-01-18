<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Order\OrderService;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function checkout(OrderService $service){
        try{
            $order = $service->placeOrder();
            return response()->json(
                [
                    'data'=>$order,
                    'message' => 'Order place successfully'
                ]
            );
        }
        catch (\Exception $e)
        {
        return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // Get all orders for the authenticated user
    public function getOrders(OrderService $service){

        $orders = $service->getUserOrder();

        return response()->json(
            [
                'data' => OrderResource::collection($orders),
                'message' => 'get user orders succesfully'
            ]
            );

    }

    //Get the details of a specific order
    public function getOrderDetails($id,OrderService $service){
        try{

            $order = $service->getOrderDetails($id);
        

            return response()->json(
                [
                    'data' =>new OrderResource($order),
                    'message' => 'get order details successully'
                ]
            );

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Custom message when the ID is wrong
        return response()->json([
            'message' => "Sorry, Order #{$id} does not exist in our records."
        ], 404);
    }
    }
}
