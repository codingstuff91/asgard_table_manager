<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\association>
 */
class AssociationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'ASGARD',
            'slug' => 'asgard',
            'description' => $this->faker->text(30),
            'city' => $this->faker->city(),
            'department' => 91,
        ];
    }
}
