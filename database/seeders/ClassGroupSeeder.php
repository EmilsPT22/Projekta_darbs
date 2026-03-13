<?php

namespace Database\Seeders;

use App\Models\ClassGroup;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ClassGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if they don't exist
        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create class groups
        $class1A = ClassGroup::create([
            'name' => '1st Grade - Class A',
            'grade_level' => '1',
            'description' => 'First year students - Class A',
        ]);

        $class1B = ClassGroup::create([
            'name' => '1st Grade - Class B',
            'grade_level' => '1',
            'description' => 'First year students - Class B',
        ]);

        $class2A = ClassGroup::create([
            'name' => '2nd Grade - Class A',
            'grade_level' => '2',
            'description' => 'Second year students - Class A',
        ]);

        $class2B = ClassGroup::create([
            'name' => '2nd Grade - Class B',
            'grade_level' => '2',
            'description' => 'Second year students - Class B',
        ]);

        $class3A = ClassGroup::create([
            'name' => '3rd Grade - Class A',
            'grade_level' => '3',
            'description' => 'Third year students - Class A',
        ]);

        $class3B = ClassGroup::create([
            'name' => '3rd Grade - Class B',
            'grade_level' => '3',
            'description' => 'Third year students - Class B',
        ]);

        // Create students for each class (10 students per class)
        $this->createStudentsForClass($class1A, 10);
        $this->createStudentsForClass($class1B, 10);
        $this->createStudentsForClass($class2A, 10);
        $this->createStudentsForClass($class2B, 10);
        $this->createStudentsForClass($class3A, 10);
        $this->createStudentsForClass($class3B, 10);

        $this->command->info('Created 60 students across 6 class groups!');
    }

    /**
     * Create students for a specific class group.
     */
    private function createStudentsForClass(ClassGroup $classGroup, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $student = User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => bcrypt('password'), // Default password: 'password'
                'remember_token' => \Str::random(10),
                'class_group_id' => $classGroup->id,
            ]);

            $student->assignRole('student');
        }
    }
}
