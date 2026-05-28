<?php

namespace Database\Factories;

use App\Enum\BudgetType;
use App\Models\Budget;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Budget>
 */
class BudgetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'amount' => $this->faker->numberBetween(1000, 50000),
            'type' => $this->faker->randomElement([BudgetType::General, BudgetType::Goal]),
            'user_id' => User::factory(),
        ];
    }
}
