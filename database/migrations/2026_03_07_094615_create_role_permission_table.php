<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration - Création de la table pivot role_permission
 * 
 * Cette table fait le lien entre les rôles et les permissions.
 * C'est une relation Many-to-Many entre Role et Permission.
 */
return new class extends Migration
{
    /**
     * Création de la table pivot role_permission
     */
    public function up(): void
    {
        Schema::create('role_permission', function (Blueprint $table) {
            $table->id();
            // Référence au rôle - suppression en cascade
            $table->foreignId('role_id')
                  ->constrained('roles')
                  ->onDelete('cascade');
            // Référence à la permission - suppression en cascade
            $table->foreignId('permission_id')
                  ->constrained('permissions')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Suppression de la table pivot role_permission
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permission');
    }
};
