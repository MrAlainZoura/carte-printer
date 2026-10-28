<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UpdateVehiculeTransferPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $registrar = app(PermissionRegistrar::class);
        $registrar->forgetCachedPermissions();

        $transferPermission = Permission::findOrCreate('vehicule.transfer', 'web');

        foreach (['chef_poste', 'agent'] as $roleName) {
            $role = Role::findByName($roleName, 'web');
            $role->givePermissionTo($transferPermission);
        }

        Role::findByName('admin', 'web')->givePermissionTo($transferPermission);

        $registrar->forgetCachedPermissions();
    }
}
