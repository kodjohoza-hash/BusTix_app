<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\Displacement;

/**
 * TripSeeder - Crée les voyages de test
 * 
 * Insère des voyages planifiés pour les
 * prochains jours dans BusTix.
 */
class TripSeeder extends Seeder
{
    public function run(): void
    {
        // Récupère tous les trajets
        $displacements = Displacement::all();

        // Création de voyages pour chaque trajet
        foreach ($displacements as $displacement) {
            // Voyage demain matin
            Trip::create([
                'displacement_id'  => $displacement->id,
                'living_date_time' => now()->addDay()->setTime(7, 0),
                'price'            => $displacement->prix,
                'travel_status'    => 'planifié',
            ]);

            // Voyage demain après-midi
            Trip::create([
                'displacement_id'  => $displacement->id,
                'living_date_time' => now()->addDay()->setTime(14, 0),
                'price'            => $displacement->prix,
                'travel_status'    => 'planifié',
            ]);

            // Voyage dans 3 jours
            Trip::create([
                'displacement_id'  => $displacement->id,
                'living_date_time' => now()->addDays(3)->setTime(8, 0),
                'price'            => $displacement->prix,
                'travel_status'    => 'planifié',
            ]);
        }

        $this->command->info('✅ Voyages créés avec succès !');
    }
}
