<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    private $colors = ['bg-red-500', 'bg-green-500', 'bg-teal-500'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory(3)->create([
            'color' => Arr::random($this->colors, 1)[0],
        ]);
    }
}
