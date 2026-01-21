<?php

namespace App\Http\Controllers\Address;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Address\AddressService;

class AddressController extends Controller
{
    public function createAddress(Request $request, AddressService $service)
    {


        $request->validate([
            'address_name'   => 'required|string|max:50', // e.g., Home, Office
            'recipient_name' => 'required|string|max:255',
            'phone_number'   => 'required|string|min:8|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city'           => 'required|string|max:100',
            'state'          => 'nullable|string|max:100',
            'postal_code'    => 'required|string|max:20',
            'country'        => 'required|string|max:100',
            'is_default'     => 'boolean',
        ]);
        $address = $service->createAddress($request);

        return response()->json([
            'data' => $address,
            'message' => 'add address successfully'
        ], 201);
    }

    public function getAddress(AddressService $service)
    {

        $address = $service->getMyAddress();

        return response()->json([
            'data' => $address,
            'message' => 'get address successfully'
        ],200);
    }

    public function updateAddress(Request $request, $id, AddressService $service)
    {
        $request->validate([
            'address_name'   => 'sometimes|string|max:50', // e.g., Home, Office
            'recipient_name' => 'sometimes|string|max:255',
            'phone_number'   => 'sometimes|string|min:8|max:20',
            'address_line_1' => 'sometimes|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city'           => 'sometimes|string|max:100',
            'state'          => 'nullable|string|max:100',
            'postal_code'    => 'sometimes|string|max:20',
            'country'        => 'sometimes|string|max:100',
            'is_default'     => 'boolean',
        ]);
        

        $address = $service->updateAddress($id,$request);

        return response()->json([
            'data' => $address,
            'message' => 'update address successfully'
        ],201);
    }
}
