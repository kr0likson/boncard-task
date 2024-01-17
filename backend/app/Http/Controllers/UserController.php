<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login', 'register']]);
    }
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]);
        } catch (ValidationException) {
            return response()->json([
                'success'=> false,
                'message' => 'Invalid data'
            ],422);
        }

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = User::find(Auth::user()->id);

            $user_token['token'] = $user->createToken('appToken')->accessToken;

            return response()->json([
                'success' => true,
                'token' => $user_token,
                'user' => $user,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to authenticate.',
            ], 401);
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'success'=> false,
                'message' => 'Invalid data'
            ], 422);
        }
        $request['password']=Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $userData = $request->toArray();
        unset($userData['password_confirmation']);
        $user = User::factory()->make($userData);
        $user->save();
        $token = $user->createToken('BoncardToken')->accessToken;
        $response = ['token' => $token];
        return response($response, 200);
    }
}
