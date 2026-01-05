<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. USER ADMIN
        User::create([
            'name' => 'Saladin Admin',
            'email' => 'admin@saladdin.com',
            'password' => bcrypt('password'), // Password: password
            'role' => 'admin',
            'bio' => 'Administrator Utama',
        ]);

        // 2. USER STUDENT
        User::create([
            'name' => 'Ahmed Student',
            'email' => 'student@saladdin.com',
            'password' => bcrypt('password'), // Password: password
            'role' => 'student',
            'bio' => 'Penuntut Ilmu',
        ]);

        // 3. PANGGIL COURSE SEEDER
        $this->call(CourseSeeder::class);
    }
}
