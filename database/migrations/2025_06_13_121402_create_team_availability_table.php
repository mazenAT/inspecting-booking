<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->integer('day_of_week'); // 0-6 for Sunday-Saturday
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            // Ensure no duplicate day entries for a team
            $table->unique(['team_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_availability');
    }
}; 