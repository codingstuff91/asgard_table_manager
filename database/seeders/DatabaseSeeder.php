<?php

namespace Database\Seeders;

use App\Models\Day;
use App\Models\Game;
use App\Models\User;
use App\Models\Table;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategorySeeder::class);

        $this->call(GameSeeder::class);

        $this->call(UserSeeder::class);

        $this->call(DaySeeder::class);
        
        Table::factory(3)
        ->for(Game::factory())
        ->for(Day::factory())
        ->has(User::factory()->count(rand(1,4)))
        ->create([
            'organizer_id' => User::inRandomOrder()->first()->id,
        ]);
    }
}
