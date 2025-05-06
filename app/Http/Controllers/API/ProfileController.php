<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
}
