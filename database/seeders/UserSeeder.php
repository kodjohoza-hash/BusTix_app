<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole  = Role::where('role_name', 'admin')->first();
        $clientRole = Role::where('role_name', 'client')->first();

        // ===== SUPER ADMIN =====
        User::create([
            'role_id'      => $adminRole->id,
            'name'         => 'Super',
            'user_surname' => 'Admin',
            'email'        => 'admin@bustix.com',
            'telephone'    => '6999999999',
            'password'     => Hash::make('admin123'),
            'role'         => 'admin',
            'admin_type'   => 'super_admin',
            'actif'        => true,
        ]);

        // ===== ADMIN GUICHET =====
        User::create([
            'role_id'      => $adminRole->id,
            'name'         => 'Guichet',
            'user_surname' => 'Admin',
            'email'        => 'guichet@bustix.com',
            'telephone'    => '6988888888',
            'password'     => Hash::make('guichet123'),
            'role'         => 'admin',
            'admin_type'   => 'guichet',
            'actif'        => true,
        ]);

        // ===== CLIENTS =====
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
                'role'         => 'client',
                'admin_type'   => null,
                'actif'        => true,
            ]);
        }

        $this->command->info('✅ Utilisateurs créés avec succès !');
    }
}