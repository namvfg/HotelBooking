<?php

use App\Enums\BookingStatus;
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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();
            $table->foreignId("room_id")->constrained("rooms")->cascadeOnDelete();

            $table->date("checkin_date");
            $table->date("checkout_date");
            $table->decimal("total_price", 12, 2);
            $table->text("note")->nullable();
            $table->enum("status", array_column(BookingStatus::cases(), "value"))->default(BookingStatus::PENDING->value);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
