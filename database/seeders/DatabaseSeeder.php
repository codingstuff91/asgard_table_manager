<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Day;
use App\Models\Event;
use App\Models\Game;
use App\Models\Table;
use App\Models\User;
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

        Table::factory()
            ->for(Game::factory())
            ->for(Day::factory())
            ->for(Category::all()->random())
            ->has(User::factory())
            ->create([
                'organizer_id' => User::first()->id,
                'start_hour' => '21:00',
            ]);

        Event::factory()
            ->has(User::factory())
            ->create([
                'day_id' => Day::first()->id,
            ]);
    }
}
