<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@lms.com'],
            [
                'name' => 'Admin LMS',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@saladdin.com'],
            [
                'name' => 'Admin Saladdin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Student users
        User::updateOrCreate(
            ['email' => 'john@student.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'jane@student.com'],
            [
                'name' => 'Jane Smith',
                'password' => Hash::make('password'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'student@saladdin.com'],
            [
                'name' => 'Student Saladdin',
                'password' => Hash::make('password'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]
        );
    }
}
