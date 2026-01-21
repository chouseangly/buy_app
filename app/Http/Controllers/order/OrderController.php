<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Order\OrderService;
use App\Http\Resources\OrderResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;
    public function checkout(Request $request, OrderService $service)
    {
        $request->validate([
        'address_id' => 'required|exists:addresses,id'
    ]);
        try {
            $order = $service->placeOrder($request->address_id);
            return response()->json([
                'message' => 'Order initiated',
                'order' => new OrderResource($order['order']),
                'client_secret' => $order['client_secret']
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // Get all orders for the authenticated user
    public function getOrders(OrderService $service)
    {

        $orders = $service->getUserOrder();

        return response()->json(
            [
                'data' => OrderResource::collection($orders),
                'message' => 'get user orders succesfully'
            ]
        );
    }

    //Get the details of a specific order
    public function getOrderDetails($id, OrderService $service)
    {
        try {

            $order = $service->getOrderDetails($id);

            // 2. Check the policy (looks for the 'view' method in OrderPolicy)
            $this->authorize('view', $order);
            return response()->json(
                [
                    'data' => new OrderResource($order),
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

    public function updateOrderStatus(Request $request,$id,OrderService $service){
        $request->validate([
            'status' => 'required|in:processing,shipped,delivered,cancelled'
        ]);

        $order = $service->changeStatus($id,$request->status);

        return response()->json([
            'data' => $order,
            'message' => "Order status updated to {$request->status}"
        ],201);
    }

    public function getAllOrdersForAdmin(OrderService $service){

        $orders = $service->getAllOrdersForAdmin();

        return response()->json([
            'data' => OrderResource::collection($orders),
            'message' => 'get all orders successfully'
        ],200);
    }
}
