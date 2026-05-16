<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            
            // On définit location et photo_path UNE SEULE FOIS ici
            $table->string('location')->nullable(); 
            $table->string('photo_path')->nullable();
            
            $table->enum('status', ['pending', 'in_progress', 'resolved'])->default('pending');
            
            // La clé étrangère pour l'utilisateur qui fait le rapport
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};