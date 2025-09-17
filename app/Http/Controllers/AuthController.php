<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create($data);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    public function login(LoginRequest $request){
        $credentials = $request->only('email','password');

        if(!$token = JWTAuth::attempt($credentials)){
            return response()->json(['error'=>'Invalid Credentials'],401);
        }

        return response()->json([
            'message'=>'Login Successful',
            'token'=>$token,
            'user'=>Auth::user()
        ]);
    }

    public function me(){
        return response()->json(Auth::user());
    }

    public function refresh(){
        return response()->json([
            'token'=>JWTAuth::refresh(),
            'user'=>Auth::user()
        ]);
    }

    public function logout(){
        Auth::logout();
        return response()->json([
            'message'=>'Successfully logged out',            
        ]);
    }
}