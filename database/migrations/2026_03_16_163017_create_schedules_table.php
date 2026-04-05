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
    Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        $table->foreignId('zone_id')->constrained()->onDelete('cascade');
        $table->string('day_of_week'); // Ex: Lundi, Mardi...
        $table->time('pickup_time');   // Ex: 08:30:00
        $table->string('truck_name')->nullable(); // Ex: Camion A-01
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
