<?php

namespace App\Services\Coupon;

use App\Repositories\Coupon\CouponRepo;

class CouponService{
    public function __construct(private CouponRepo $repo){}

    public function addCoupon($request){

        return $this->repo->addCoupon([
            'code' => $request->code,
            'type' => $request->type,
            'value' => $request->value,
            'usage_limit' => $request->usage_limit,
            'expires_at' => $request->expires_at
        ]);
    }
}
