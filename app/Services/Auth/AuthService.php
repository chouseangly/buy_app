<?php
namespace App\Services\Auth;


use Pest\Support\Arr;
use App\Repositories\Auth\AuthRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService{
    public function __construct(private AuthRepo $repo){} // injection

    public function register(array $data): array
    {

        $user = $this->repo->register([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => 'customer',
            'password' => Hash::make($data['password'])
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'data' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ];
    }

    public function login(array $data): array
    {

        if (!Auth::attempt($data)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid login credentials.'],
            ]);
        }

        $user = $this->repo->findByEmail($data['email']);
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'message' => 'login successfully',
            'token' => $token,
            'token_type' => 'Bearer'
        ];
    }

    public function logout($user): array
    {
        $user->tokens()->delete();
        return [
            'message' => 'logout successfully'
        ];
    }
}
