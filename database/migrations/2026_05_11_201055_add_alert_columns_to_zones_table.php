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
    Schema::table('zones', function (Blueprint $table) {
        if (!\Schema::hasColumn('zones', 'alerte_active')) {
            $table->boolean('alerte_active')->default(false);
        }
        if (!\Schema::hasColumn('zones', 'current_lat')) {
            $table->decimal('current_lat', 10, 7)->nullable();
        }
        if (!\Schema::hasColumn('zones', 'current_lng')) {
            $table->decimal('current_lng', 10, 7)->nullable();
        }
    });
}

public function down()
{
    Schema::table('zones', function (Blueprint $table) {
        $table->dropColumn(['alerte_active', 'current_lat', 'current_lng']);
    });
}
};
