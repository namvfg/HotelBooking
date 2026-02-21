<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Http\Resources\Review\ReviewDetailResource;
use App\Http\Resources\Review\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            "hotel_id" => "nullable|exists:hotels,id",
            "user_id" => "nullable|exists:users,id",
        ]);
        $search = $request->search;

        return ReviewResource::collection(
            Review::when($request->hotel_id, function ($q) use ($request) {
                $q->where("hotel_id", $request->hotel_id);
            })
                ->when($request->user_id, function ($q) use ($request) {
                    $q->where("user_id", $request->user_id);
                })
                ->when($search, function ($q) use ($search) {
                    $q->where(function ($sq) use ($search) {
                        $sq->where("comment", "like", "%{$search}%")
                            ->orWhereHas("hotel", function ($hq) use ($search) {
                                $hq->where("name", "like", "%{$search}%");
                            })
                            ->orWhereHas("user", function ($uq) use ($search) {
                                $uq->where("name", "like", "%{$search}%");
                            });
                    });
                })
                ->latest()
                ->paginate(config("pagination.per_page"))
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request)
    {
        $review = Review::create($request->validated());
        return (new ReviewResource($review))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        return (new ReviewDetailResource($review))->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        $review->update($request->validated());
        return (new ReviewDetailResource($review))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        $review->delete();
        return response()->noContent();
    }
}
