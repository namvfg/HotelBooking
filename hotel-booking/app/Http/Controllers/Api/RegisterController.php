<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Enums\RequestStatus;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Models\ManagerRequest;
use App\Services\cloudinary\CloudinaryService;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request, CloudinaryService $cloudinaryService)
    {
        $request->validate([
            "avatar" => config("image.image_validate_config"),
        ]);

        $uploaded = $cloudinaryService->upload(
            $request->file("avatar"),
            "avatars"
        );

        $data = $request->validated();
        $data["password"] = bcrypt($data["password"]);
        $data["role"] = Role::USER->value;
        $data["avatar_url"] = $uploaded["url"];
        $data["avatar_public_id"] = $uploaded["public_id"];

        $user = User::create($data);

        if ($request->boolean('is_manager', false)) {
            ManagerRequest::create([
                'user_id' => $user->id,
                'note' => $request->note,
                'status' => RequestStatus::PENDING,
            ]);
        }

        return (new UserResource($user))->response()->setStatusCode(201);
    }
}
