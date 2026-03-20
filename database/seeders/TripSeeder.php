<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\Displacement;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        $displacements = Displacement::all();

        // Horaires fixes pour chaque voyage
        $horaires = [
            ['hour' => 6,  'minute' => 0],
            ['hour' => 9,  'minute' => 0],
            ['hour' => 12, 'minute' => 0],
            ['hour' => 15, 'minute' => 0],
            ['hour' => 18, 'minute' => 0],
        ];

        // Générer des voyages pour les 60 prochains jours
        foreach ($displacements as $displacement) {
            for ($day = 1; $day <= 60; $day++) {
                // 2 voyages par jour par trajet
                foreach (array_slice($horaires, 0, 2) as $horaire) {
                    Trip::firstOrCreate(
                        [
                            'displacement_id'  => $displacement->id,
                            'living_date_time' => now()->addDays($day)->setTime($horaire['hour'], $horaire['minute']),
                        ],
                        [
                            'price'         => $displacement->prix,
                            'travel_status' => 'planifié',
                        ]
                    );
                }
            }
        }

        $this->command->info('✅ Voyages créés pour les 60 prochains jours !');
    }
}