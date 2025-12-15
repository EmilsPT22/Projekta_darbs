<?php

namespace Tests\Unit;

use App\Models\Theme;
use App\Models\User;
use App\Models\Internship;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_remaining_hours_for_user_is_calculated_correctly()
    {
        $internship = Internship::factory()->create();

        $theme = Theme::create([
            'internship_id' => $internship->id,
            'name' => 'Backend work',
            'max_hours' => 40,
        ]);

        $user = User::factory()->create();

        $theme->users()->attach($user->id, [
            'assigned_hours' => 40,
            'used_hours' => 15,
        ]);

        $this->assertEquals(
            25,
            $theme->remainingHoursForUser($user->id)
        );
    }
}
