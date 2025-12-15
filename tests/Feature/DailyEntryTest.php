<?php

namespace Tests\Feature;

use App\Models\DailyEntry;
use App\Models\Internship;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyEntryTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_add_entry_within_theme_hours()
    {
        $student = User::factory()->create(['role' => 'student']);
        $internship = Internship::factory()->create();
        $internship->students()->attach($student);

        $theme = Theme::factory()->create([
            'internship_id' => $internship->id,
            'max_hours' => 20,
        ]);

        $theme->users()->attach($student->id, [
            'assigned_hours' => 20,
            'used_hours' => 0,
        ]);

        $response = $this->actingAs($student)->post(
            "/internships/{$internship->id}/entries",
            [
                'theme_id' => $theme->id,
                'date' => now()->toDateString(),
                'location' => 'remote',
                'time_from' => '09:00',
                'time_to' => '12:00',
                'credit_hours' => 3,
            ]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('daily_entries', [
            'credit_hours' => 3,
        ]);
    }

    public function test_student_cannot_exceed_theme_hours()
    {
        $student = User::factory()->create(['role' => 'student']);
        $internship = Internship::factory()->create();
        $internship->students()->attach($student);

        $theme = Theme::factory()->create([
            'internship_id' => $internship->id,
            'max_hours' => 5,
        ]);

        $theme->users()->attach($student->id, [
            'assigned_hours' => 5,
            'used_hours' => 4,
        ]);

        $response = $this->actingAs($student)->post(
            "/internships/{$internship->id}/entries",
            [
                'theme_id' => $theme->id,
                'date' => now()->toDateString(),
                'location' => 'remote',
                'time_from' => '09:00',
                'time_to' => '12:00',
                'credit_hours' => 2,
            ]
        );

        $response->assertSessionHasErrors('credit_hours');
    }
}
