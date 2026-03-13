<?php

namespace Tests\Feature;

use App\Models\ClassGroup;
use App\Models\Internship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClassGroupFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_internship_with_class_groups(): void
    {
        $admin = User::factory()->admin()->create();
        $internship = Internship::factory()->create();
        $classGroup = ClassGroup::factory()->create(['name' => '1st Grade - A', 'grade_level' => '1']);

        $response = $this->actingAs($admin)->get("/internships/{$internship->id}");

        $response->assertStatus(200);
        $response->assertViewHas('classGroups');
    }

    public function test_students_without_class_are_not_shown(): void
    {
        $admin = User::factory()->admin()->create();
        $studentWithClass = User::factory()->student()->create([
            'class_group_id' => ClassGroup::factory()->create()->id,
        ]);
        $studentWithoutClass = User::factory()->student()->create([
            'class_group_id' => null,
        ]);
        $internship = Internship::factory()->create();

        $response = $this->actingAs($admin)->get("/internships/{$internship->id}");

        $response->assertStatus(200);
        $response->assertSee($studentWithClass->name);
        $response->assertDontSee($studentWithoutClass->name);
    }

    public function test_only_students_with_assigned_class_are_listed(): void
    {
        $admin = User::factory()->admin()->create();
        $classGroup = ClassGroup::factory()->create(['grade_level' => '1']);
        
        $student1 = User::factory()->student()->create([
            'class_group_id' => $classGroup->id,
        ]);
        $student2 = User::factory()->student()->create([
            'class_group_id' => null,
        ]);
        
        $internship = Internship::factory()->create();

        $response = $this->actingAs($admin)->get("/internships/{$internship->id}");

        $response->assertStatus(200);
        $users = $response->viewData('users');
        
        $this->assertTrue($users->contains($student1));
        $this->assertFalse($users->contains($student2));
    }
}
