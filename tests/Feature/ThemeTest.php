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
        $admin = User::factory()->create(['role' => 'admin']);
        $internship = Internship::factory()->create();

        $response = $this->actingAs($admin)->post(
            "/internships/{$internship->id}/themes",
            [
                'name' => 'Theme 1',
                'max_hours' => 40,
            ]
        );

        $this->assertDatabaseHas('themes', [
            'name' => 'Theme 1',
        ]);
    }
}
