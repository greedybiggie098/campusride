<?php

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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('license_number', 50)->nullable();
            $table->string('vehicle_type', 50)->nullable();
            $table->string('vehicle_model', 100)->nullable();
            $table->string('plate_number', 20)->nullable();
            $table->string('address')->nullable();
            $table->string('profile_photo')->nullable();
            $table->enum('availability', ['available', 'on_trip', 'off_duty'])->default('available');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
