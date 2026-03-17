<?php

namespace Tests\Feature;

use App\Models\ClassGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentGradeAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_student_grade_on_user_show_page(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();

        $response = $this->actingAs($admin)->get("/admin/users/{$student->id}");

        $response->assertStatus(200);
        $response->assertSee('Student Grade');
    }

    public function test_admin_can_view_edit_grade_page(): void
    {
        $admin = User::factory()->admin()->create();
        $classGroup = ClassGroup::factory()->create(['grade_level' => '1', 'name' => '1st Grade - A']);
        $student = User::factory()->student()->create(['class_group_id' => null]);

        $response = $this->actingAs($admin)->get("/admin/users/{$student->id}/edit-grade");

        $response->assertStatus(200);
        $response->assertSee('Save Grade');
        $response->assertSee('1st Grade - A');
    }

    public function test_student_cannot_access_grade_management(): void
    {
        $student = User::factory()->student()->create();
        $otherStudent = User::factory()->student()->create();

        $response = $this->actingAs($student)->get('/admin/students-grade');
        $response->assertStatus(403);

        $response = $this->actingAs($student)->get("/admin/users/{$otherStudent->id}/edit-grade");
        $response->assertStatus(403);
    }
}
