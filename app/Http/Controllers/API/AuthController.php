<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole('user');

        $token = $user->createToken('AuthToken')->accessToken; 

        return response()->json([
            'message' => 'Usuario registrado con éxito!',
            'user' => $user,
            'access_token' => $token
        ], 201);
    }

    public function login(Request $request)
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();

            /** @var \App\Models\User $user */
            
            $token = $user->createToken('AuthToken')->accessToken;

            return response()->json([
                'message' => 'Login successful!',
                'user' => $user,
                'access_token' => $token
            ], 200);

        } else {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }
    }
}
