<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PublicRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_is_available_with_only_the_seeded_admin(): void
    {
        $this->withoutVite();
        $this->createAdmin('admin@gmail.com');

        $this->get('/register')
            ->assertOk()
            ->assertSee('Créer mon compte');
    }

    public function test_public_registration_is_disabled_when_a_second_admin_exists(): void
    {
        $this->withoutVite();
        $this->createAdmin('admin@gmail.com');
        $this->createAdmin('second-admin@example.com');

        $this->get('/register')
            ->assertRedirect(route('login'));

        $this->followingRedirects()
            ->get('/register')
            ->assertOk()
            ->assertSee('Veuillez contacter le deuxième administrateur')
            ->assertDontSee('Créer un compte');

        $userCount = User::query()->count();
        $this->post('/auth/register', [])
            ->assertRedirect(route('login'));

        $this->assertSame($userCount, User::query()->count());
    }

    private function createAdmin(string $email): User
    {
        $role = Role::query()->firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create(['email' => $email]);
        $user->assignRole($role);

        return $user;
    }
}
