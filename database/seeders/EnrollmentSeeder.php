<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@lms.com')->first();
        $john = User::where('email', 'john@student.com')->first();
        $jane = User::where('email', 'jane@student.com')->first();

        $courses = Course::all();

        // John enrolls in all free courses (auto-active)
        foreach ($courses as $course) {
            if ($course->price == 0) {
                Enrollment::create([
                    'user_id' => $john->id,
                    'course_id' => $course->id,
                    'status' => 'active',
                    'enrolled_at' => now(),
                ]);
            }
        }

        // Jane enrolls in paid course (pending approval)
        $paidCourse = $courses->where('price', '>', 0)->first();
        if ($paidCourse) {
            Enrollment::create([
                'user_id' => $jane->id,
                'course_id' => $paidCourse->id,
                'status' => 'pending',
                'enrolled_at' => now(),
            ]);
        }

        // Jane also enrolls in one free course
        $freeCourse = $courses->where('price', 0)->first();
        if ($freeCourse) {
            Enrollment::create([
                'user_id' => $jane->id,
                'course_id' => $freeCourse->id,
                'status' => 'active',
                'enrolled_at' => now(),
            ]);
        }
    }
}
