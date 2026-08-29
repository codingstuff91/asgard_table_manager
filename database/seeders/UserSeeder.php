<?php

namespace Database\Seeders;

use App\Models\Association;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $association = Association::first() ?? Association::factory()->create();

        User::factory()
            ->has(Association::factory())
            ->create([
                'name' => 'mattou',
                'email' => 'mattou2812@gmail.com',
                'admin' => true,
            ]);
    }
}
