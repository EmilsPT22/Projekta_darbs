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
        $student = User::factory()->student()->create();

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
        $student = User::factory()->student()->create();

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

    public function test_student_can_only_view_own_entries(): void
    {
        $student = User::factory()->student()->create();
        $otherStudent = User::factory()->student()->create();
        $internship = Internship::factory()->create();

        $internship->students()->attach($student);
        $internship->students()->attach($otherStudent);

        DailyEntry::factory()->count(3)->create([
            'user_id' => $otherStudent->id,
            'internship_id' => $internship->id,
        ]);
        DailyEntry::factory()->count(2)->create([
            'user_id' => $student->id,
            'internship_id' => $internship->id,
        ]);

        $response = $this->actingAs($student)->get("/internships/{$internship->id}/entries");

        $response->assertOk();
        $this->assertEquals(2, $response->viewData('entries')->count());
    }

    public function test_admin_can_edit_entry(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();
        $internship = Internship::factory()->create();
        $theme = Theme::factory()->create(['internship_id' => $internship->id]);

        $internship->students()->attach($student);

        $entry = DailyEntry::factory()->create([
            'user_id' => $student->id,
            'internship_id' => $internship->id,
            'theme_id' => $theme->id,
        ]);

        $response = $this->actingAs($admin)->patch("/internships/{$internship->id}/entries/{$entry->id}", [
            'admin_comment' => 'Good work!',
            'grade' => 8,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('daily_entries', [
            'id' => $entry->id,
            'admin_comment' => 'Good work!',
            'grade' => 8,
        ]);
    }

    public function test_student_cannot_edit_entry(): void
    {
        $student = User::factory()->student()->create();
        $internship = Internship::factory()->create();
        $theme = Theme::factory()->create(['internship_id' => $internship->id]);

        $internship->students()->attach($student);

        $entry = DailyEntry::factory()->create([
            'user_id' => $student->id,
            'internship_id' => $internship->id,
            'theme_id' => $theme->id,
        ]);

        $response = $this->actingAs($student)->patch("/internships/{$internship->id}/entries/{$entry->id}", [
            'credit_hours' => 5,
        ]);

        $response->assertStatus(403);
    }
}
