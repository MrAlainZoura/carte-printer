<?php

namespace Tests\Feature;

use App\Models\PostTravail;
use App\Models\User;
use App\Support\CurrentPostTravail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class InitialPostTravailSetupTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_is_taken_to_initial_setup_when_no_work_post_exists(): void
    {
        $this->withoutVite();
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        $this->get('/dashboard')
            ->assertRedirect(route('post-travails.initial-setup'));

        $this->get(route('post-travails.initial-setup'))
            ->assertOk()
            ->assertSee('Première étape : créer un poste de travail');
    }

    public function test_first_work_post_is_created_and_selected_for_admin(): void
    {
        $this->withoutVite();
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        $this->post(route('post-travails.initial-setup.store'), [
            'libele' => 'Poste Central',
            'use_cdf' => '1',
        ])->assertRedirect(route('dashboard'));

        $post = PostTravail::query()->sole();

        $this->assertSame('Poste Central', $post->libele);
        $this->assertTrue($admin->postTravails()
            ->where('post_travails.id', $post->id)
            ->wherePivot('is_default', true)
            ->exists());
        $this->assertSame($post->id, CurrentPostTravail::id());
    }

    private function createAdmin(): User
    {
        $role = Role::query()->firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $admin = User::factory()->create();
        $admin->assignRole($role);

        return $admin;
    }
}
