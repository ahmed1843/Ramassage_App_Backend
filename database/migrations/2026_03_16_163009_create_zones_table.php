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
    Schema::create('zones', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('status')->default('clean');
        $table->text('description')->nullable();
        
        // --- Ajoute ces lignes ---
        $table->boolean('alerte_active')->default(false); // Pour savoir si le camion est là
        $table->decimal('current_lat', 10, 8)->nullable(); // Latitude précise
        $table->decimal('current_lng', 11, 8)->nullable(); // Longitude précise
        // -------------------------

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zones');
    }
};
