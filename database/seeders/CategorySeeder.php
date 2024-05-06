<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create([
            'name' => 'Jeux de cartes',
            'color' => 'bg-green-500',
            'icon' => '/img/cards.png',
        ]);

        Category::create([
            'name' => 'Jeux de plateau',
            'color' => 'bg-red-500',
            'icon' => '/img/boardgame.png',
        ]);

        Category::create([
            'name' => 'Jeux de rôles',
            'color' => 'bg-blue-500',
            'icon' => '/img/magicien.png',
        ]);

        Category::create([
            'name' => 'Jeux wargame',
            'color' => 'bg-blue-500',
            'icon' => '/img/soldat.png',
        ]);
    }
}
