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

        $response = $this->actingAs($admin)
            ->get('/internships/create');

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

        $this->actingAs($student)->get('/internships/create');

        $response = $this->actingAs($student)->post('/internships', []);

        $response->assertStatus(403);
    }
}
