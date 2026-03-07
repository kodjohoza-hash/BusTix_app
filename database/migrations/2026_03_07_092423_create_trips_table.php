<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration - Création de la table trips
 * 
 * Cette table stocke les voyages planifiés.
 * Un voyage est une instance d'un déplacement
 * à une date et heure précise.
 */
return new class extends Migration
{
    /**
     * Création de la table trips
     */
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            // Référence au déplacement (trajet fixe)
            $table->foreignId('displacement_id')
                  ->constrained('displacements')
                  ->onDelete('cascade');
            // Date et heure de départ du voyage
            $table->dateTime('living_date_time');
            // Prix du billet pour ce voyage
            $table->decimal('price', 20, 2);
            // Statut du voyage (planifié, en cours, terminé, annulé)
            $table->string('travel_status', 100);
            $table->timestamps();
        });
    }

    /**
     * Suppression de la table trips
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
