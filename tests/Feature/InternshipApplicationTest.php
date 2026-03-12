<?php

namespace Tests\Feature;

use App\Models\Internship;
use App\Models\InternshipApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternshipApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_submit_application(): void
    {
        $student = User::factory()->student()->create();
        $internship = Internship::factory()->create();

        $response = $this->actingAs($student)->post("/internships/{$internship->id}/apply", [
            'motivation' => 'I want to learn and grow.',
            'cover_letter' => 'I am interested in this internship.',
            'phone' => '+371 20000000',
        ]);

        $response->assertRedirect(route('internships.show', $internship));
        $this->assertDatabaseHas('internship_applications', [
            'internship_id' => $internship->id,
            'user_id' => $student->id,
            'status' => 'pending',
        ]);
    }

    public function test_student_cannot_apply_twice_to_same_internship(): void
    {
        $student = User::factory()->student()->create();
        $internship = Internship::factory()->create();

        InternshipApplication::factory()->create([
            'internship_id' => $internship->id,
            'user_id' => $student->id,
            'status' => 'pending',
        ]);

        $this->expectException(\Illuminate\Database\UniqueConstraintViolationException::class);
        
        InternshipApplication::factory()->create([
            'internship_id' => $internship->id,
            'user_id' => $student->id,
            'status' => 'pending',
        ]);
    }

    public function test_student_can_view_their_application(): void
    {
        $student = User::factory()->student()->create();
        $internship = Internship::factory()->create();

        $application = InternshipApplication::factory()->create([
            'internship_id' => $internship->id,
            'user_id' => $student->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($student)->get("/internships/{$internship->id}/my-application");

        $response->assertOk();
        $response->assertViewHas('application', $application);
    }

    public function test_admin_can_view_all_applications(): void
    {
        $admin = User::factory()->admin()->create();
        $internship = Internship::factory()->create();

        InternshipApplication::factory()->count(3)->create(['internship_id' => $internship->id]);

        $response = $this->actingAs($admin)->get("/internships/{$internship->id}/applications");

        $response->assertOk();
        $response->assertViewHas('applications', function ($applications) {
            return $applications->count() === 3;
        });
    }

    public function test_admin_can_approve_application(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();
        $internship = Internship::factory()->create();

        $application = InternshipApplication::factory()->create([
            'internship_id' => $internship->id,
            'user_id' => $student->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post("/internships/{$internship->id}/applications/{$application->id}/approve");

        $response->assertRedirect();
        $this->assertDatabaseHas('internship_applications', [
            'id' => $application->id,
            'status' => 'approved',
        ]);
        $this->assertDatabaseHas('internship_user', [
            'internship_id' => $internship->id,
            'user_id' => $student->id,
        ]);
    }

    public function test_admin_can_reject_application(): void
    {
        $admin = User::factory()->admin()->create();
        $internship = Internship::factory()->create();

        $application = InternshipApplication::factory()->create([
            'internship_id' => $internship->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post("/internships/{$internship->id}/applications/{$application->id}/reject");

        $response->assertRedirect();
        $this->assertDatabaseHas('internship_applications', [
            'id' => $application->id,
            'status' => 'rejected',
        ]);
    }

    public function test_student_cannot_approve_application(): void
    {
        $student = User::factory()->student()->create();
        $internship = Internship::factory()->create();

        $application = InternshipApplication::factory()->create([
            'internship_id' => $internship->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($student)->post("/internships/{$internship->id}/applications/{$application->id}/approve");

        $response->assertStatus(403);
    }

    public function test_application_requires_validation(): void
    {
        $student = User::factory()->student()->create();
        $internship = Internship::factory()->create();

        $response = $this->actingAs($student)->post("/internships/{$internship->id}/apply", []);

        $response->assertSessionHasErrors('motivation');
    }
}
