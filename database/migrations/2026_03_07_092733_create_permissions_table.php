<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration - Création de la table permissions
 * 
 * Cette table stocke toutes les permissions
 * disponibles dans l'application BusTix.
 */
return new class extends Migration
{
    /**
     * Création de la table permissions
     */
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            // Nom de la permission - unique pour éviter les doublons
            $table->string('permission_name')->unique();
            $table->timestamps();
        });
    }

    /**
     * Suppression de la table permissions
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
