<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('zones', function (Blueprint $table) {
        // current_lat et current_lng existent déjà → on les ignore
        // On ajoute seulement truck_updated_at si elle n'existe pas
        if (!Schema::hasColumn('zones', 'truck_updated_at')) {
            $table->timestamp('truck_updated_at')->nullable();
        }
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zones', function (Blueprint $table) {
            //
        });
    }
};
