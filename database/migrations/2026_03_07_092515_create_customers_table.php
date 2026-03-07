<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration - Création de la table customers
 * 
 * Cette table stocke les informations des clients
 * qui effectuent des réservations dans BusTix.
 */
return new class extends Migration
{
    /**
     * Création de la table customers
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            // Référence au compte utilisateur du client
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            // Informations personnelles du client
            $table->string('name', 100);
            $table->string('surname', 100);
            $table->string('telephone', 10);
            $table->string('email', 100)->unique();
            // Numéro de carte d'identité (donnée sensible)
            $table->integer('id_card');
            $table->timestamps();
        });
    }

    /**
     * Suppression de la table customers
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
