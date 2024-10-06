<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\api\LoginRequest;
use App\Http\Requests\api\RegisterRequest;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $token = $user->createToken('auth-token')->plainTextToken;
            return response()->json([
                'data' => new UserResource($user),
                'token' => $token,
                'message' => "Register successful 😉",
            ]);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['message' => "Somthing went wrong !"],400);
        }
    }
    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Invalid credentials'],400);
            }
            $token = $user->createToken('auth-token')->plainTextToken;
            return response()->json([
                'data' => new UserResource($user),
                'token' => $token,
                'message' => 'Login successful 🫡',
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Somthing went wrong !"],400);
        }
    }

    public function logout(Request $request) {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => "Sign Out Successfully, will miss you 🥲"]);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Somthing went wrong !"],400);
        }
    }
}
