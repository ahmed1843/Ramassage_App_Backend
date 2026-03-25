<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Important pour le fallback

return new class extends Migration
{
    public function up(): void
    {
        // On change le type ENUM pour inclure 'in_progress'
        Schema::table('reports', function (Blueprint $table) {
            $table->enum('status', ['pending', 'in_progress', 'resolved'])
                  ->default('pending')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->enum('status', ['pending', 'resolved'])
                  ->default('pending')
                  ->change();
        });
    }
};
