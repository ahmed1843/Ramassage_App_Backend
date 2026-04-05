<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Ajoute user_id s'il n'existe pas encore
            if (!Schema::hasColumn('reports', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained();
            }
            // Ajoute zone_id s'il n'existe pas encore
            if (!Schema::hasColumn('reports', 'zone_id')) {
                $table->foreignId('zone_id')->nullable()->after('user_id')->constrained();
            }
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['zone_id']);
            $table->dropColumn(['user_id', 'zone_id']);
        });
    }
};
