<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

/**
 * PermissionSeeder - Crée les permissions et les assigne aux rôles
 * 
 * Insère toutes les permissions de BusTix et les
 * assigne aux rôles correspondants.
 */
class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Permissions Admin
        $adminPermissions = [
            Permission::MANAGE_BUSES,
            Permission::CREATE_BUS,
            Permission::EDIT_BUS,
            Permission::DELETE_BUS,
            Permission::MANAGE_TRIPS,
            Permission::CREATE_TRIP,
            Permission::EDIT_TRIP,
            Permission::DELETE_TRIP,
            Permission::MANAGE_CUSTOMERS,
            Permission::VIEW_CUSTOMERS,
            Permission::DELETE_CUSTOMER,
            Permission::MANAGE_RESERVATIONS,
            Permission::VIEW_RESERVATIONS,
            Permission::CANCEL_RESERVATION,
        ];

        // Permissions Client
        $clientPermissions = [
            Permission::MAKE_RESERVATION,
            Permission::VIEW_OWN_RESERVATION,
            Permission::MAKE_PAYMENT,
            Permission::VIEW_OWN_PAYMENT,
        ];

        // Création des permissions Admin
        foreach ($adminPermissions as $permissionName) {
            Permission::create(['permission_name' => $permissionName]);
        }

        // Création des permissions Client
        foreach ($clientPermissions as $permissionName) {
            Permission::create(['permission_name' => $permissionName]);
        }

        // Assignation des permissions au rôle Admin
        $adminRole = Role::where('role_name', 'admin')->first();
        $adminRole->permissions()->attach(
            Permission::whereIn('permission_name', $adminPermissions)->pluck('id')
        );

        // Assignation des permissions au rôle Client
        $clientRole = Role::where('role_name', 'client')->first();
        $clientRole->permissions()->attach(
            Permission::whereIn('permission_name', $clientPermissions)->pluck('id')
        );

        $this->command->info('✅ Permissions créées et assignées avec succès !');
    }
}
