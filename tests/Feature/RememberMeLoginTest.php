<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RememberMeLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_remember_me_sets_a_persistent_remember_token(): void
    {
        $user = User::factory()->create(['password' => 'secret']);

        $this->post(route('auth.login'), [
            'identifiant' => $user->email,
            'password' => 'secret',
            'remember' => '1',
        ])->assertRedirect(route('postes.select'));

        $this->assertNotNull($user->fresh()->getRememberToken());
    }

    public function test_login_without_remember_me_does_not_set_a_remember_token(): void
    {
        $user = User::factory()->create(['password' => 'secret']);

        $this->post(route('auth.login'), [
            'identifiant' => $user->email,
            'password' => 'secret',
        ])->assertRedirect(route('postes.select'));

        $this->assertNull($user->fresh()->getRememberToken());
    }
}
