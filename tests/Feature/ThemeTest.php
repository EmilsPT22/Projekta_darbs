<?php

namespace Tests\Feature;

use App\Models\Internship;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_add_theme()
    {
        $admin = User::factory()->admin()->create();
        $internship = Internship::factory()->create();

        $response = $this->actingAs($admin)->post(
            "/internships/{$internship->id}/themes",
            [
                'name' => 'Theme 1',
                'max_hours' => 40,
            ]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('themes', [
            'name' => 'Theme 1',
        ]);
    }

    public function test_admin_can_edit_theme(): void
    {
        $admin = User::factory()->admin()->create();
        $internship = Internship::factory()->create();

        $theme = Theme::factory()->create([
            'internship_id' => $internship->id,
            'name' => 'Original Theme',
            'max_hours' => 20,
        ]);

        $response = $this->actingAs($admin)->put("/internships/{$internship->id}/themes/{$theme->id}", [
            'name' => 'Updated Theme',
            'max_hours' => 50,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('themes', [
            'id' => $theme->id,
            'name' => 'Updated Theme',
        ]);
    }

    public function test_student_cannot_create_theme(): void
    {
        $student = User::factory()->student()->create();
        $internship = Internship::factory()->create();

        $response = $this->actingAs($student)->post("/internships/{$internship->id}/themes", [
            'name' => 'Unauthorized Theme',
            'max_hours' => 10,
        ]);

        $response->assertStatus(403);
    }

    public function test_theme_requires_valid_max_hours(): void
    {
        $admin = User::factory()->admin()->create();
        $internship = Internship::factory()->create();

        $response = $this->actingAs($admin)->post("/internships/{$internship->id}/themes", [
            'name' => 'Invalid Theme',
            'max_hours' => -5,
        ]);

        $response->assertSessionHasErrors('max_hours');
    }
}
