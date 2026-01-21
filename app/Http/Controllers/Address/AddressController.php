<?php

namespace App\Http\Controllers\Address;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Address\AddressService;

class AddressController extends Controller
{
    public function createAddress(Request $request , AddressService $service){
        

        $address = $service->createAddress($request);

        return response()->json([
            'data' => $address,
            'message' => 'add address successfully'
        ]);
    }
}
