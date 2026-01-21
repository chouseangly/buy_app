<?php

namespace App\Repositories\Address;

use App\Models\Address;
use Pest\Support\Arr;

class AddressRepo{

    public function createAddress(array $data)
    {
        if($data['is_default'] ?? false){
            $this->unsetDefaults($data['is_default']);
        }
        return Address::create($data);
    }

    public function getMyAddress($userId)
    {
        return Address::where('user_id' , $userId)->get();
    }

    public function updateAddress($id,array $data){
        $address = Address::findOrFail($id);
        $address->update($data);
        return $address;
    }

    protected function unsetDefaults($userId)
    {
       Address::where('user_id', $userId)->update(['is_default' => false]);
    }


}
