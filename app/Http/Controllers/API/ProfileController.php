<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

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
        return response()->json(['message' => 'User not found'], 404);

    }

    public function update(Request $request)
{
    $user = Auth::user();

    $validator = Validator::make($request->all(), [
        'name' => 'nullable|string|max:255',
        'email' => 'nullable|string|email:dns|max:255|unique:users,email,' . $user->id,
        'password' => 'nullable|min:8|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation Error',
            'errors' => $validator->errors()->all(),
        ], 422);
    }

    $updateData = [
        'name' => $request->input('name', $user->name),
        'email' => $request->input('email', $user->email),
    ];

    // Solo actualiza el password si se envía, y siempre hasheado
    if ($request->filled('password')) {
        $updateData['password'] = bcrypt($request->input('password'));
    }

    /** @var \App\Models\User $user */

    $user->update($updateData);

    return response()->json([
        'message' => 'usuario actualizado con éxito!',
        'user' => $user,
    ], 200);
}

    public function destroy()
    {
        $user = Auth::user();

        if ($user) {

            /** @var \App\Models\User $user */

            $user->delete();
            return response()->json(['message' => 'Usuario eliminado con éxito!'], 200);
        }
        return response()->json(['message' => 'User not found'], 404);
    }

    public function destroyOther(User $userToDelete)
    {
        if (!Gate::allows('delete', $userToDelete)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $userToDelete->delete();
        return response()->json(['message' => 'Usuario eliminado con éxito!'], 200);
    }

 
}
