<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if ($user) {
            return response()->json([
            'status' => 'success',
            'message' => 'User profile retrieved successfully',
            'data' => $user,
        ], 200);
        }

    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email:dns|max:255|unique:users',
            'password' => 'nullable|min:8|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()->all(),
            ], 422);
        }
    
        /** @var \App\Models\User $user */
        $updateData = [
        'name' => $request->input('name', $user->name),
        'email' => $request->input('email', $user->email),
        'password' => $request->input('password', $user->password),
    ];
    
        $user->update($updateData);
    
        return response()->json([
            'message' => 'usero actualizado con éxito!',
            'user' => $user,
        ], 200);
        }
}
