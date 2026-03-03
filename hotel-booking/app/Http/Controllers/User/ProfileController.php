<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\User\UserDetailResource;
use App\Http\Resources\User\UserResource;
use App\Services\cloudinary\CloudinaryService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return new UserDetailResource($request->user());
    }

    public function update(UpdateProfileRequest $request, CloudinaryService $cloudinaryService)
    {
        $user = $request->user();

        $uploaded = null;

        if ($request->hasFile("avatar")) {
            $request->validate([
                "avatar" => config("image.image_validate_config"),
            ]);
            if ($user->avatar_public_id) {
                $cloudinaryService->delete($user->avatar_public_id);
            }
            $uploaded = $cloudinaryService->upload($request->file("avatar"), "avatars");
        }

        $data = $request->validated();
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        if ($uploaded !== null) {
            $data["avatar_url"] = $uploaded["url"];
            $data["avatar_public_id"] = $uploaded["public_id"];
        }


        $user->update($data);
        return (new UserResource($user))->response()->setStatusCode(200);
    }
}
