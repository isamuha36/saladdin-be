<?php

namespace App\Services;

use App\Models\Course;

class ProgressService
{
    /**
     * Calculate course progress for a user
     */
    public function calculateProgress($course, $user): float
    {
        if (!$user) {
            return 0;
        }

        // Support both course object and courseId integer
        if (is_numeric($course)) {
            $course = \App\Models\Course::with('sections.lessons')->find($course);
            if (!$course) return 0;
        }

        // Support both user object and userId integer
        if (is_numeric($user)) {
            $user = \App\Models\User::find($user);
            if (!$user) return 0;
        }

        // Make sure sections/lessons are loaded
        if (!$course->relationLoaded('sections') || $course->sections->first() && !$course->sections->first()->relationLoaded('lessons')) {
            $course->load('sections.lessons');
        }

        // Calculate total lessons
        $totalLessons = $course->sections->sum(function ($section) {
            return $section->lessons->count();
        });

        if ($totalLessons === 0) {
            return 0;
        }

        // Calculate completed lessons using user->completedLessons relation
        $completed = $user->completedLessons()
            ->whereHas('section', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->count();

        return round(($completed / $totalLessons) * 100, 2);
    }
}
