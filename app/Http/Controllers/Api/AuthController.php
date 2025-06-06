<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->validated())) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        
        return response()->json([
            'message' => 'Logout successful'
        ]);
    }

    public function user()
    {
        return response()->json(auth()->user());
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'firstname' => $request->firstname,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password)
        ]);

        $token = auth()->login($user);

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user,
            'token' => $token
        ], 201);
    }
} 