<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_all_users(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertOk();
    }

    public function test_admin_can_assign_roles_to_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        Role::firstOrCreate(['name' => 'student']);

        $response = $this->actingAs($admin)->post(route('admin.users.update-roles', $user), [
            'roles' => ['student'],
        ]);

        $response->assertRedirect(route('admin.users.show', $user));
        $this->assertTrue($user->fresh()->hasRole('student'));
    }

    public function test_admin_can_remove_role_from_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->student()->create();

        $this->assertTrue($user->hasRole('student'));

        $response = $this->actingAs($admin)->post(route('admin.users.remove-role', $user), [
            'role' => 'student',
        ]);

        $response->assertRedirect();
        $this->assertFalse($user->fresh()->hasRole('student'));
    }

    public function test_student_cannot_access_admin_users_index(): void
    {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get(route('admin.users.index'));

        $response->assertStatus(403);
    }

    public function test_student_cannot_assign_roles(): void
    {
        $student = User::factory()->student()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($student)->post(route('admin.users.update-roles', $user), [
            'roles' => ['admin'],
        ]);

        $response->assertStatus(403);
    }
}
