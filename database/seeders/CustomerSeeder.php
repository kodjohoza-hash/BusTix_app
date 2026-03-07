<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\User;

/**
 * CustomerSeeder - Crée les profils clients de test
 * 
 * Crée les profils clients associés aux
 * comptes utilisateurs clients créés dans UserSeeder.
 */
class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // Récupère tous les utilisateurs clients
        $clients = User::where('role', 'client')->get();

        // Données supplémentaires pour chaque client
        $customerData = [
            [
                'id_card' => 123456789,
            ],
            [
                'id_card' => 987654321,
            ],
            [
                'id_card' => 456789123,
            ],
        ];

        // Création du profil client pour chaque utilisateur
        foreach ($clients as $index => $user) {
            Customer::create([
                'user_id'   => $user->id,
                'name'      => $user->name,
                'surname'   => $user->user_surname,
                'telephone' => $user->telephone,
                'email'     => $user->email,
                'id_card'   => $customerData[$index]['id_card'],
            ]);
        }

        $this->command->info('✅ Profils clients créés avec succès !');
    }
}
