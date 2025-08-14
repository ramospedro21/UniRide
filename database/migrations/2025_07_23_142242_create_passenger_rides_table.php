<?php

use App\Models\Ride;
use App\Models\User;
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
        Schema::create('passenger_rides', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Ride::class, 'ride_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(User::class, 'user_id')->constrained()->onDelete('cascade');
            $table->integer('evaluation')->nullable();
            $table->integer('status')->default(0); // 0: pending, 1: accepted, 2: completed, 3: cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passenger_rides');
    }
};
