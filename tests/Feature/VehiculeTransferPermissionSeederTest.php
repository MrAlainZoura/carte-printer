<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VehiculeTransferPermissionSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_transfer_permission_is_granted_to_admin_and_transfer_roles(): void
    {
        $this->seed([
            \Database\Seeders\RolePermissionSeeder::class,
            \Database\Seeders\UpdateVehiculeTransferPermissionSeeder::class,
        ]);

        $admin = Role::findByName('admin', 'web');
        $this->assertTrue($admin->hasDirectPermission('vehicule.transfer'));
        $this->assertTrue($admin->hasDirectPermission('vehicule.update'));

        foreach (['chef_poste', 'agent'] as $roleName) {
            $role = Role::findByName($roleName, 'web');
            $this->assertTrue($role->hasDirectPermission('vehicule.transfer'));
            $this->assertFalse($role->hasDirectPermission('vehicule.update'));
        }
    }
}
