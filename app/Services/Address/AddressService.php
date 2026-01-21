<?php

namespace App\Services\Address;

use App\Repositories\Address\AddressRepo;
use Illuminate\Container\Attributes\Auth;

class AddressService{
    public function __construct(private AddressRepo $repo){}

    public function createAddress($request){
        $data = $request->all();
        $data['user_id'] = auth()->id();

        return $this->repo->createAddress($data);
    }
}
