<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Admin : tous les droits.
     * Autres rôles : lecture + création uniquement.
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
            'carte_rose.update',
            'carte_rose.print',
            'carte_rose.cancel',
            'plaque.view',
            'plaque.create',
            'plaque.update',
            'plaque.assign',
            'post_travail.view',
            'post_travail.create',
            'post_travail.manage',
            'proprietaire.view',
            'proprietaire.create',
            'proprietaire.manage',
            'produit.view',
            'produit.create',
            'produit.manage',
            'post_produit.view',
            'post_produit.create',
            'post_produit.manage',
            'approvisionnement.view',
            'approvisionnement.create',
            'approvisionnement.manage',
            'approvisionnement.confirm',
            'user.view',
            'user.create',
            'user.manage',
            'role.manage',
            'dashboard.global',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $admin = Role::findOrCreate('admin', 'web');
        $admin->syncPermissions(Permission::all());

        $readAndCreate = Permission::query()
            ->where(function ($query) {
                $query->where('name', 'like', '%.view')
                    ->orWhere('name', 'like', '%.create');
            })
            ->pluck('name')
            ->all();

        foreach (['chef_poste', 'agent', 'lecteur'] as $roleName) {
            $role = Role::findOrCreate($roleName, 'web');
            $role->syncPermissions($readAndCreate);
        }

        Role::findByName('chef_poste', 'web')->givePermissionTo('approvisionnement.confirm');

        //foreach (['chef_poste', 'agent'] as $roleName) {
        //    Role::findByName($roleName, 'web')->givePermissionTo('vehicule.update');
        //}

        // Impression carte rose : tous sauf lecteur
        foreach (['chef_poste', 'agent'] as $roleName) {
            Role::findByName($roleName, 'web')->givePermissionTo('carte_rose.print');
        }
    }
}
