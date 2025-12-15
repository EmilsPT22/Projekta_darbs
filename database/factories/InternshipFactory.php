<?php

namespace Database\Factories;

use App\Models\Internship;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Internship>
 */
class InternshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Internship::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Internship',
            'description' => $this->faker->sentence,
            'length' => 3,
            'start_date' => now()->subWeek(),
            'end_date' => now()->addMonths(3),
        ];
    }
}
