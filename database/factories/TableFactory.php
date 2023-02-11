<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Table;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class TableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Table::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'players_number' => $this->faker->numberBetween(1, 10),
            'start_hour' => $this->faker->time,
        ];
    }
}
