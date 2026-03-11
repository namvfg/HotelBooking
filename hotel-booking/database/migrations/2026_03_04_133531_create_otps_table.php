<?php

use App\Enums\OtpType;
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
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string("email");
            $table->string("code");
            $table->enum("type", array_column(OtpType::cases(), "value"))->default(OtpType::REGISTER->value);
            $table->timestamp("expired_at");
            $table->boolean("is_verified")->default(false);
            $table->integer("attemps")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
