<?php

namespace Database\Factories;


use App\Models\Theme;
use App\Models\Internship;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Theme>
 */
class ThemeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    protected $model = Theme::class;

    public function definition(): array
    {
        return [
            'internship_id' => Internship::factory(),
            'name' => $this->faker->sentence(3),
            'max_hours' => 40,
            'description' => $this->faker->sentence(),
        ];
    }
}
