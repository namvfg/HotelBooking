<?php

namespace App\Http\Controllers\User;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required|string",
        ]);
        $user = User::where("email", $request->email)->first();

        if (!$user) {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }

        if (!Hash::driver("bcrypt")->check($request->password, $user->password)) {
            return response()->json([
                "message" => "Password mismatch"
            ], 401);
        }

        if ($user->role == Role::ADMIN) {
            return response()->json([
                "message" => "You should not be here"
            ], 403);
        }

        $token = $user->createToken("user-token")->plainTextToken;

        return response()->json([
            "user" => new UserResource($user),
            "token" => $token,
        ]);
    }

    public function me(Request $request) {
        return response()->json([
            "user" => new UserResource($request->user())
        ]);
    }
}
