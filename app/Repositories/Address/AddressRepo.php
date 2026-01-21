<?php

namespace App\Repositories\Address;

use App\Models\Address;

class AddressRepo{

    public function createAddress(array $data)
    {
        if($data['is_default'] ?? false){
            $this->unsetDefaults($data['is_default']);
        }
        return Address::create($data);
    }

    protected function unsetDefaults($userId)
    {
       Address::where('user_id', $userId)->update(['is_default' => false]);
    }
}
