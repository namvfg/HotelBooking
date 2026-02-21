<?php

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId("booking_id")->constrained("bookings")->cascadeOnDelete();
            
            $table->decimal("amount", 12, 2);
            $table->enum("method", array_column(PaymentMethod::cases(), "value"))->default(PaymentMethod::CASH->value);
            $table->enum("type", array_column(PaymentType::cases(), "value"))->default(PaymentType::PAYMENT->value);
            $table->enum("status", array_column(PaymentStatus::cases(), "value"))->default(PaymentStatus::PENDING->value);
            $table->timestamp("paid_at")->nullable();
            $table->string("transaction_code")->nullable()->unique();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
