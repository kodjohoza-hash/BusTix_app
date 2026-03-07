<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration - Création de la table seats
 * 
 * Cette table stocke les sièges de chaque bus.
 * Chaque siège est identifié par un numéro unique
 * dans son bus.
 */
return new class extends Migration
{
    /**
     * Création de la table seats
     */
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            // Référence au bus auquel appartient le siège
            $table->foreignId('bus_id')
                  ->constrained('buses')
                  ->onDelete('cascade');
            // Numéro du siège (ex: A1, B2, C3...)
            $table->string('seat_number', 10);
            $table->timestamps();
        });
    }

    /**
     * Suppression de la table seats
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
