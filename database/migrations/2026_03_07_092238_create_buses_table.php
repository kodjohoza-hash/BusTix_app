<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration - Création de la table buses
 * 
 * Cette table stocke tous les bus disponibles
 * dans la flotte de BusTix.
 */
return new class extends Migration
{
    /**
     * Création de la table buses
     */
    public function up(): void
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            // Numéro d'immatriculation du bus - unique
            $table->string('bus_number', 100)->unique();
            // Marque du bus (ex: Toyota, Mercedes...)
            $table->string('mack', 30);
            // Capacité maximale du bus (nombre de sièges)
            $table->string('capacity', 10);
            // Statut du bus (disponible, en service, en maintenance)
            $table->string('bus_status', 100);
            $table->timestamps();
        });
    }

    /**
     * Suppression de la table buses
     */
    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
