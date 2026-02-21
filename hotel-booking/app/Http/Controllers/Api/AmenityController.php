<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Amenity\StoreAmenityRequest;
use App\Http\Requests\Amenity\UpdateAmenityRequest;
use App\Http\Resources\Amenity\AmenityResource;
use App\Models\Amenity;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query("search");

        return AmenityResource::collection(
            Amenity::
            when($search, function ($q) use ($search) {
                $q->where("name", "like", "%{$search}%")
                    ->orWhere("slug", "like", "%{$search}%");
            })
            ->latest()
            ->paginate(config("pagination.per_page")),
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAmenityRequest $request)
    {
        $amenity = Amenity::create($request->validated());
        return (new AmenityResource($amenity))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Amenity $amenity)
    {
        return (new AmenityResource($amenity))->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAmenityRequest $request, Amenity $amenity)
    {
        $amenity->update($request->validated());
        return (new AmenityResource($amenity))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Amenity $amenity)
    {
        $amenity->delete();
        return response()->noContent();
    }

    
}
