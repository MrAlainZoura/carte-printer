<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Rôles / autorisations (UML ROLE + AUTORISATION via Spatie).
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'vehicule.view',
            'vehicule.create',
            'vehicule.update',
            'vehicule.delete',
            'carte_rose.view',
            'carte_rose.create',
            'carte_rose.print',
            'carte_rose.cancel',
            'plaque.view',
            'plaque.create',
            'plaque.assign',
            'post_travail.view',
            'post_travail.manage',
            'proprietaire.view',
            'proprietaire.manage',
            'user.manage',
            'role.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $admin = Role::findOrCreate('admin');
        $admin->syncPermissions(Permission::all());

        $chefPoste = Role::findOrCreate('chef_poste');
        $chefPoste->syncPermissions([
            'vehicule.view',
            'vehicule.create',
            'vehicule.update',
            'carte_rose.view',
            'carte_rose.create',
            'carte_rose.print',
            'carte_rose.cancel',
            'plaque.view',
            'plaque.create',
            'plaque.assign',
            'post_travail.view',
            'proprietaire.view',
            'proprietaire.manage',
        ]);

        $agent = Role::findOrCreate('agent');
        $agent->syncPermissions([
            'vehicule.view',
            'vehicule.create',
            'carte_rose.view',
            'carte_rose.create',
            'carte_rose.print',
            'plaque.view',
            'plaque.assign',
            'post_travail.view',
            'proprietaire.view',
        ]);

        $lecteur = Role::findOrCreate('lecteur');
        $lecteur->syncPermissions([
            'vehicule.view',
            'carte_rose.view',
            'plaque.view',
            'post_travail.view',
            'proprietaire.view',
        ]);
    }
}
