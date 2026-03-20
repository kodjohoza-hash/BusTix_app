<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder - Seeder principal de BusTix
 * 
 * Appelle tous les seeders dans le bon ordre
 * pour respecter les dépendances entre les tables.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info(' Démarrage du seeding de BusTix...');

        $this->call([
            RoleSeeder::class,       // 1. Rôles en premier
            PermissionSeeder::class, // 2. Permissions + assignation aux rôles
            UserSeeder::class,       // 3. Utilisateurs (dépend des rôles)
            CustomerSeeder::class,   // 4. Clients (dépend des users)
            BusSeeder::class,        // 5. Bus + sièges
            DisplacementSeeder::class, // 6. Trajets (dépend des bus)
            TripSeeder::class,       // 7. Voyages (dépend des trajets)
        ]);

        $this->command->info('🎉 Seeding terminé avec succès !');
    }
}
