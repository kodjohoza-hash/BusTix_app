<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration - Création de la table roles
 * 
 * Cette table stocke les différents rôles
 * disponibles dans l'application BusTix.
 * Ex: admin, client
 */
return new class extends Migration
{
    /**
     * Création de la table roles
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            // Nom du rôle (admin, client) - unique pour éviter les doublons
            $table->string('role_name')->unique();
            $table->timestamps();
        });
    }

    /**
     * Suppression de la table roles
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
