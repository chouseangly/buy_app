<?php

namespace App\Http\Controllers\Coupon;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Coupon\CouponService;

class CouponController extends Controller
{
    public function AddCoupon(Request $request , CouponService $service)
    {

        $request->validate([
            'code'        => 'required|string|unique:coupons,code',
            'type'        => 'required|in:fixed,percent',
            'value'       => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at'  => 'required|date|after:today',
        ]);

        $coupon = $service->addCoupon($request);

        return response()->json([
            'data' => $coupon,
            'message' => 'add coupon successfully'
        ],201);
    }
}
