<?php

namespace Database\Seeders;

use App\Models\Association;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create([
            'association_id' => Association::first()->id,
            'name' => 'Jeux de cartes',
            'color' => 'bg-green-500',
            'icon' => '/img/cards.png',
        ]);

        Category::create([
            'association_id' => Association::first()->id,
            'name' => 'Jeux de plateau',
            'color' => 'bg-red-500',
            'icon' => '/img/boardgame.png',
        ]);

        Category::create([
            'association_id' => Association::first()->id,
            'name' => 'Jeux de rôles',
            'color' => 'bg-blue-500',
            'icon' => '/img/magicien.png',
        ]);

        Category::create([
            'association_id' => Association::first()->id,
            'name' => 'Jeux wargame',
            'color' => 'bg-blue-500',
            'icon' => '/img/soldat.png',
        ]);
    }
}
