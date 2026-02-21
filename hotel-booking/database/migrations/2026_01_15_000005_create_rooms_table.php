<?php

use App\Enums\RoomStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();

            $table->foreignId("hotel_id")->constrained("hotels")->cascadeOnDelete();
            $table->foreignId("room_type_id")->constrained("room_types")->cascadeOnDelete();

            $table->string("room_code", 10);
            $table->enum("status", array_column(RoomStatus::cases(), "value"))->default(RoomStatus::AVAILABLE->value);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(["hotel_id", "room_code"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
