<?php

namespace App\Services\Address;

use App\Models\Address;
use App\Repositories\Address\AddressRepo;
use Illuminate\Container\Attributes\auth;

class AddressService{
    public function __construct(private AddressRepo $repo){}

    public function createAddress($request){

        if ($request->is_default) {
        // Set all other user addresses to not default
        Address::where('user_id', auth()->id())->update(['is_default' => false]);
    }

        return $this->repo->createAddress([
            'user_id' =>auth()->id(),
            'address_name' => $request->address_name,
            'recipient_name' => $request->recipient_name,
            'phone_number' => $request->phone_number,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'is_default' => $request->is_default
        ]);
    }

    public function getMyAddress(){

        return $this->repo->getMyAddress(auth()->id());
    }

    public function updateAddress($id,$request){

        return $this->repo->updateAddress($id,[
            'user_id' =>auth()->id(),
            'address_name' => $request->address_name,
            'recipient_name' => $request->recipient_name,
            'phone_number' => $request->phone_number,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'is_default' => $request->is_default
        ]);
    }
}
