<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            // Ajoute les colonnes manquantes
            $table->string('location')->nullable()->after('description');
            $table->string('photo_path')->nullable()->after('location');
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['location', 'photo_path']);
        });
    }
};