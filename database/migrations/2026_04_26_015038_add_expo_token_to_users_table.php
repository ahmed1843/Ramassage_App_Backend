<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// php artisan make:migration add_expo_push_token_to_users_table
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('expo_push_token')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // On supprime la colonne si on annule la migration
            $table->dropColumn('expo_token');
        });
    }
};
