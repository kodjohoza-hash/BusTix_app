<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration - Création de la table ticket_reservations
 * 
 * Table centrale de BusTix. Elle relie le client,
 * le voyage, le siège et génère un ticket unique.
 */
return new class extends Migration
{
    /**
     * Création de la table ticket_reservations
     */
    public function up(): void
    {
        Schema::create('ticket_reservations', function (Blueprint $table) {
            $table->id();
            // Référence au client qui effectue la réservation
            $table->foreignId('customer_id')
                  ->constrained('customers')
                  ->onDelete('cascade');
            // Référence au voyage réservé
            $table->foreignId('trip_id')
                  ->constrained('trips')
                  ->onDelete('cascade');
            // Référence au siège sélectionné
            $table->foreignId('seat_id')
                  ->constrained('seats')
                  ->onDelete('cascade');
            // Date et heure de la réservation
            $table->dateTime('reservation_date');
            // Statut de la réservation
            $table->enum('status', ['en_attente', 'confirmée', 'annulée'])
                  ->default('en_attente');
            // Code unique du billet généré automatiquement
            $table->string('ticket_code', 100)->unique();
            $table->timestamps();
        });
    }

    /**
     * Suppression de la table ticket_reservations
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_reservations');
    }
};
