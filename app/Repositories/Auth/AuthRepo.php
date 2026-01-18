<?php
namespace App\Repositories\Auth;

use App\Models\User;

class AuthRepo{

    public function register(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email',$email)->first();
    }
}
