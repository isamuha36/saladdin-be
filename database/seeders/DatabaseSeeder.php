<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed in order: Users -> Courses -> Enrollments -> Certificates
        $this->call([
            UserSeeder::class,
            CourseSeeder::class,
            EnrollmentSeeder::class,
            CertificateSeeder::class,
        ]);
    }
}
