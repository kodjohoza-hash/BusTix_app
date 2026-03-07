<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration - Création de la table payments
 * 
 * Cette table stocke tous les paiements effectués
 * pour les réservations de billets dans BusTix.
 */
return new class extends Migration
{
    /**
     * Création de la table payments
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // Référence à la réservation liée au paiement
            $table->foreignId('reservation_id')
                  ->constrained('ticket_reservations')
                  ->onDelete('cascade');
            // Montant payé
            $table->decimal('amount', 10, 2);
            // Mode de paiement (espèces, mobile money, carte bancaire)
            $table->enum('payment_mode', ['espèces', 'mobile_money', 'carte_bancaire']);
            // Référence unique de la transaction
            $table->string('transaction_reference')->unique();
            // Date et heure du paiement
            $table->dateTime('payment_date');
            $table->timestamps();
        });
    }

    /**
     * Suppression de la table payments
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
