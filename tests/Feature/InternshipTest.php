<?php

namespace Tests\Feature;

use App\Models\Internship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_internship()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/internships', [
            'name' => 'Internship A',
            'description' => 'Desc',
            'length' => 3,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonths(3)->toDateString(),
        ]);

        $response->assertRedirect('/internships');
        $this->assertDatabaseHas('internships', [
            'name' => 'Internship A',
        ]);
    }

    public function test_student_cannot_create_internship()
    {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->post('/internships', []);

        $response->assertStatus(403);
    }

    public function test_admin_can_edit_internship(): void
    {
        $admin = User::factory()->admin()->create();
        $internship = Internship::factory()->create();

        $response = $this->actingAs($admin)->put("/internships/{$internship->id}", [
            'name' => 'Updated Name',
            'description' => 'Updated Description',
            'length' => 6,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonths(6)->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('internships', [
            'id' => $internship->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_admin_can_add_student_to_internship(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();
        $internship = Internship::factory()->create();

        $response = $this->actingAs($admin)->post("/internships/{$internship->id}/addStudent/{$student->id}");

        $response->assertRedirect();
        $this->assertDatabaseHas('internship_user', [
            'internship_id' => $internship->id,
            'user_id' => $student->id,
        ]);
    }

    public function test_student_cannot_add_student_to_internship(): void
    {
        $student = User::factory()->student()->create();
        $otherStudent = User::factory()->student()->create();
        $internship = Internship::factory()->create();

        $response = $this->actingAs($student)->post("/internships/{$internship->id}/addStudent/{$otherStudent->id}");

        $response->assertStatus(403);
    }
}
