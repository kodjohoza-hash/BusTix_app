<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bus;

/**
 * BusSeeder - Crée les bus de test
 * 
 * Insère quelques bus dans la flotte BusTix
 * avec leurs sièges générés automatiquement.
 */
class BusSeeder extends Seeder
{
    public function run(): void
    {
        // Liste des bus à créer
        $buses = [
            [
                'bus_number' => 'LT-001-YDE',
                'mack'       => 'Toyota Coaster',
                'capacity'   => '30',
                'bus_status' => 'disponible',
            ],
            [
                'bus_number' => 'LT-002-DLA',
                'mack'       => 'Mercedes Sprinter',
                'capacity'   => '20',
                'bus_status' => 'disponible',
            ],
            [
                'bus_number' => 'LT-003-BFM',
                'mack'       => 'Toyota Hiace',
                'capacity'   => '15',
                'bus_status' => 'disponible',
            ],
        ];

        foreach ($buses as $busData) {
            // Création du bus
            $bus = Bus::create($busData);

            // Génération automatique des sièges
            $capacity = (int) $busData['capacity'];
            for ($i = 1; $i <= $capacity; $i++) {
                $bus->seats()->create([
                    'seat_number' => 'S' . str_pad($i, 2, '0', STR_PAD_LEFT)
                ]);
            }
        }

        $this->command->info('✅ Bus et sièges créés avec succès !');
    }
}
