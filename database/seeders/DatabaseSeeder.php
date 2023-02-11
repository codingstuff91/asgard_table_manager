<?php

namespace Database\Seeders;

use App\Models\Day;
use App\Models\Game;
use App\Models\User;
use App\Models\Table;
use App\Models\Category;
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
        User::factory()->create([
            'name' => 'mattou',
            'email' => 'mattou2812@gmail.com',
        ]);

        $days = Day::factory(4)->create();
        
        $categories = Category::factory(3)->create()->each(function(){
            Game::factory(3)->create();
        });
        
        Table::factory(3)
        ->has(User::factory(4))
        ->create([
            'organizer_id' => User::factory()->create()->id,
            'day_id' => $days->random(1)->first()->id
        ]);
    }
}
