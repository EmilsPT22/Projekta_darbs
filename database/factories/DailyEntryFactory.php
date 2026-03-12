<?php

namespace Database\Factories;

use App\Models\DailyEntry;
use App\Models\Internship;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DailyEntry>
 */
class DailyEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->student(),
            'internship_id' => Internship::factory(),
            'theme_id' => Theme::factory(),
            'date' => now()->subDays(rand(1, 30))->toDateString(),
            'location' => $this->faker->randomElement(['remote', 'office', 'hybrid']),
            'time_from' => '09:00',
            'time_to' => '17:00',
            'duration' => 8,
            'credit_hours' => 4,
            'intern_comment' => null,
            'org_supervisor_comment' => null,
            'admin_comment' => null,
            'grade' => null,
        ];
    }
}
