<?php

use App\Models\Car;
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
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'driver_id');
            $table->foreignIdFor(Car::class, 'car_id');
            $table->string('departure_location_lat');
            $table->string('departure_location_long');
            $table->string('arrive_location_lat');
            $table->string('arrive_location_long');
            $table->time('departure_time');
            $table->integer('capacity');
            $table->decimal('ride_fare', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
