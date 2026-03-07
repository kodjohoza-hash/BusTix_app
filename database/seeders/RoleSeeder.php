<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

/**
 * RoleSeeder - Crée les rôles de base de l'application
 * 
 * Insère les deux rôles principaux de BusTix :
 * - Admin : accès complet à l'application
 * - Client : accès limité aux fonctionnalités client
 */
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Création du rôle Administrateur
        Role::create([
            'role_name' => 'admin',
        ]);

        // Création du rôle Client
        Role::create([
            'role_name' => 'client',
        ]);

        $this->command->info('✅ Rôles créés avec succès !');
    }
}
