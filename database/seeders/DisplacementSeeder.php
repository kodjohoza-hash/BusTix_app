<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Displacement;
use App\Models\Bus;

/**
 * DisplacementSeeder - Crée les trajets de test
 * 
 * Insère les trajets principaux entre les
 * grandes villes du Cameroun dans BusTix.
 */
class DisplacementSeeder extends Seeder
{
    public function run(): void
    {
        // Récupère les bus disponibles
        $buses = Bus::all();

        // Liste des trajets principaux au Cameroun
        $displacements = [
            [
                'bus_id'            => $buses[0]->id,
                'start_point'       => 'Yaoundé',
                'destination_point' => 'Douala',
                'prix'              => 3500,
                'distance_km'       => 250,
            ],
            [
                'bus_id'            => $buses[1]->id,
                'start_point'       => 'Douala',
                'destination_point' => 'Bafoussam',
                'prix'              => 4000,
                'distance_km'       => 190,
            ],
            [
                'bus_id'            => $buses[2]->id,
                'start_point'       => 'Yaoundé',
                'destination_point' => 'Bafoussam',
                'prix'              => 5000,
                'distance_km'       => 320,
            ],
        ];

        foreach ($displacements as $displacement) {
            Displacement::create($displacement);
        }

        $this->command->info('✅ Trajets créés avec succès !');
    }
}
