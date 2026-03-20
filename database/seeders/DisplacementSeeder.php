<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Displacement;
use App\Models\Bus;

class DisplacementSeeder extends Seeder
{
    public function run(): void
    {
        $buses = Bus::all();

        $displacements = [
            // Bus 1
            ['bus_id' => $buses[0]->id, 'start_point' => 'Yaoundé',   'destination_point' => 'Douala',      'prix' => 3500, 'distance_km' => 250],
            ['bus_id' => $buses[0]->id, 'start_point' => 'Yaoundé',   'destination_point' => 'Bertoua',     'prix' => 4500, 'distance_km' => 355],
            ['bus_id' => $buses[0]->id, 'start_point' => 'Yaoundé',   'destination_point' => 'Ebolowa',     'prix' => 3000, 'distance_km' => 155],
            ['bus_id' => $buses[0]->id, 'start_point' => 'Yaoundé',   'destination_point' => 'Mbalmayo',    'prix' => 1500, 'distance_km' => 45],

            // Bus 2
            ['bus_id' => $buses[1]->id, 'start_point' => 'Douala',    'destination_point' => 'Bafoussam',   'prix' => 4000, 'distance_km' => 190],
            ['bus_id' => $buses[1]->id, 'start_point' => 'Douala',    'destination_point' => 'Limbe',       'prix' => 1500, 'distance_km' => 70],
            ['bus_id' => $buses[1]->id, 'start_point' => 'Douala',    'destination_point' => 'Kribi',       'prix' => 3500, 'distance_km' => 180],
            ['bus_id' => $buses[1]->id, 'start_point' => 'Douala',    'destination_point' => 'Buea',        'prix' => 2000, 'distance_km' => 80],

            // Bus 3
            ['bus_id' => $buses[2]->id, 'start_point' => 'Bafoussam', 'destination_point' => 'Yaoundé',     'prix' => 5000, 'distance_km' => 320],
            ['bus_id' => $buses[2]->id, 'start_point' => 'Bafoussam', 'destination_point' => 'Bamenda',     'prix' => 2500, 'distance_km' => 90],
            ['bus_id' => $buses[2]->id, 'start_point' => 'Ngaoundéré','destination_point' => 'Yaoundé',     'prix' => 8000, 'distance_km' => 620],
            ['bus_id' => $buses[2]->id, 'start_point' => 'Garoua',    'destination_point' => 'Ngaoundéré',  'prix' => 5000, 'distance_km' => 220],
        ];

        foreach ($displacements as $displacement) {
            Displacement::create($displacement);
        }

        $this->command->info('✅ ' . count($displacements) . ' trajets créés avec succès !');
    }
}