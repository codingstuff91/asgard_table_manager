<?php

namespace Database\Seeders;

use App\Models\Association;
use Illuminate\Database\Seeder;

class AssociationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Association::factory()->count(3)->create();
    }
}
