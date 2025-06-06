<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(RegisterRequest $request)
    {
        $input = $request->validated();
        $input['password'] = bcrypt($input['password']);
        $input['role'] = Role::Admin;
        User::create($input);

        if (! $token = auth()->attempt([
            'email'    => $input['email'],
            'password' => $request->input('password')
        ])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return new UserResource(auth()->user());
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60
        ]);
    }
}
