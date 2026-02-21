<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserDetailResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\cloudinary\CloudinaryService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        return UserResource::collection(
            User::when($search, function ($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where("name", "like", "%{$search}%")
                    ->orWhere("email", "like", "%{$search}%")
                    ->orWhere("phone", "like", "%{$search}%");
                });
            })
            ->latest()
            ->paginate(config("pagination.per_page"))
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request, CloudinaryService $cloudinaryService)
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
        $data["avatar_url"] = $uploaded["url"];
        $data["avatar_public_id"] = $uploaded["public_id"];

        $user = User::create($data);
        return (new UserResource($user))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return (new UserDetailResource($user))->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user, CloudinaryService $cloudinaryService)
    {
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }
}
