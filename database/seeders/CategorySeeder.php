<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create([
            'name' => 'Jeux de plateau',
            'color' => 'bg-green-500',
        ]);

        Category::create([
            'name' => 'Jeux de cartes',
            'color' => 'bg-red-500',
        ]);

        Category::create([
            'name' => 'Jeux de rôles',
            'color' => 'bg-blue-500',
        ]);
    }
}
