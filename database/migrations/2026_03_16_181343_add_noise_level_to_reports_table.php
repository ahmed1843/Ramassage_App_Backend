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
    Schema::table('reports', function (Blueprint $table) {
        // On ajoute un entier de 1 à 5 pour le niveau de bruit
        $table->integer('noise_level')->nullable()->after('description'); 
    });
}

    /**
     * Reverse the migrations.
     */
public function down(): void
{
    Schema::table('reports', function (Blueprint $table) {
        $table->dropColumn('noise_level');
    });
}
};
