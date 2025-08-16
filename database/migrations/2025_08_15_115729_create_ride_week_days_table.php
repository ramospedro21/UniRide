<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ride_week_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ride_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->tinyInteger('day_of_week')->unsigned();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ride_week_days');
    }
};
