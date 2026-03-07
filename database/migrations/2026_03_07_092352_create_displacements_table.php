<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration - Création de la table displacements
 * 
 * Cette table stocke les trajets fixes entre deux villes.
 * Ex: Yaoundé → Douala avec prix et distance définis.
 */
return new class extends Migration
{
    /**
     * Création de la table displacements
     */
    public function up(): void
    {
        Schema::create('displacements', function (Blueprint $table) {
            $table->id();
            // Référence au bus qui effectue ce déplacement
            $table->foreignId('bus_id')
                  ->constrained('buses')
                  ->onDelete('cascade');
            // Point de départ du trajet
            $table->string('start_point', 20);
            // Point d'arrivée du trajet
            $table->string('destination_point', 20);
            // Prix du billet pour ce trajet
            $table->decimal('prix', 10, 2);
            // Distance en kilomètres entre les deux points
            $table->integer('distance_km');
            $table->timestamps();
        });
    }

    /**
     * Suppression de la table displacements
     */
    public function down(): void
    {
        Schema::dropIfExists('displacements');
    }
};
