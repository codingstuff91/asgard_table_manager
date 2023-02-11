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
        ->has(Game::factory())
        ->create([
            'organizer_id' => User::inRandomOrder()->first()->id,
            'day_id' => Day::inRandomOrder()->first()->id
        ])->each(function($table){
            $table->users()->attach(User::first());
        });
    }
}
