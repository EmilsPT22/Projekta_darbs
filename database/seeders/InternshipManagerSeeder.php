<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class InternshipManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'manager@gmail.com'],
            [
                'name' => 'Internship Manager',
                'password' => Hash::make('123456789'),
            ]
        );

        $user->assignRole('internship_manager');
    }
}
