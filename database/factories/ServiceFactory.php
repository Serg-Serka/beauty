<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->name(),
            'working_days' => '1,2,3,4,5',
            'working_hours' => '09:00,09:30,10:00,10:30,11:00,11:30,12:00',
            'is_enabled' => 1,
        ];
    }
}
