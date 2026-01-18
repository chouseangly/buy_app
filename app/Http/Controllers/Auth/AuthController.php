<?php

namespace App\Http\Controllers\Auth;

use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function register(Request $request,AuthService $service){
        $request->validate([
            'name' =>'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $response = $service->register($request->only('name','email','password'));

        return response()->json($response,201);

    }

    public function login(Request $request, AuthService $service)
    {
          $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $response = $service->login($request->only('email','password'));

        return response()->json($response,201);
    }

    public function logoutAllDevice(Request $request,AuthService $service){
        
        $response = $service->logout($request->user());
        return response()->json($response,200);
    }
}
