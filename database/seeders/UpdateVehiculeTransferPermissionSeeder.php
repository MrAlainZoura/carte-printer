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
        $updatePermission = Permission::query()
            ->where('name', 'vehicule.update')
            ->where('guard_name', 'web')
            ->first();

        foreach (['chef_poste', 'agent'] as $roleName) {
            $role = Role::findByName($roleName, 'web');

            if ($updatePermission && $role->hasDirectPermission($updatePermission)) {
                $role->revokePermissionTo($updatePermission);
            }

            $role->givePermissionTo($transferPermission);
        }

        $registrar->forgetCachedPermissions();
    }
}
