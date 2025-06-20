<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->words(3, true),
            'brand' => $this->faker->optional()->company(),
            'description' => $this->faker->paragraph(),
            'condition_id' => Condition::factory(),
            'price' => $this->faker->numberBetween(100, 100000),
            'is_sold' => false,
        ];
    }
}
