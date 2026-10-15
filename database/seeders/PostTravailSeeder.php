<?php

namespace Database\Seeders;

use App\Models\PostTravail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostTravailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = PostTravail::firstOrCreate(
            ['libele' => 'Central']
        );

    }
}
