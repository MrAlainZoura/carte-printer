<?php

namespace Database\Seeders;

use App\Models\PostTravail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('0000'),
            ]
        );

        $admin->assignRole('admin');

        $central = PostTravail::query()->firstOrCreate(['libele' => 'Central']);
        if (! $admin->postTravails()->where('post_travails.id', $central->id)->exists()) {
            $admin->postTravails()->attach($central->id, ['is_default' => true]);
        }
    }
}
