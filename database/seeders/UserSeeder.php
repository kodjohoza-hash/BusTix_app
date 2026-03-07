<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

/**
 * UserSeeder - Crée les utilisateurs de test
 * 
 * Insère un administrateur et quelques clients
 * de test dans l'application BusTix.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Récupère les rôles
        $adminRole  = Role::where('role_name', 'admin')->first();
        $clientRole = Role::where('role_name', 'client')->first();

        // Création du compte Administrateur principal
        User::create([
            'role_id'      => $adminRole->id,
            'name'         => 'Super',
            'user_surname' => 'Admin',
            'email'        => 'admin@bustix.com',
            'telephone'    => '6999999999',
            'password'     => Hash::make('admin123'),
            'role'         => User::ROLE_ADMIN,
            'actif'        => true,
        ]);

        // Création de clients de test
        $clients = [
            [
                'name'         => 'Jean',
                'user_surname' => 'Dupont',
                'email'        => 'jean.dupont@gmail.com',
                'telephone'    => '6911111111',
                'password'     => Hash::make('client123'),
            ],
            [
                'name'         => 'Marie',
                'user_surname' => 'Ngono',
                'email'        => 'marie.ngono@gmail.com',
                'telephone'    => '6922222222',
                'password'     => Hash::make('client123'),
            ],
            [
                'name'         => 'Paul',
                'user_surname' => 'Mbarga',
                'email'        => 'paul.mbarga@gmail.com',
                'telephone'    => '6933333333',
                'password'     => Hash::make('client123'),
            ],
        ];

        foreach ($clients as $client) {
            User::create([
                'role_id'      => $clientRole->id,
                'name'         => $client['name'],
                'user_surname' => $client['user_surname'],
                'email'        => $client['email'],
                'telephone'    => $client['telephone'],
                'password'     => $client['password'],
                'role'         => User::ROLE_CLIENT,
                'actif'        => true,
            ]);
        }

        $this->command->info('✅ Utilisateurs créés avec succès !');
    }
}
