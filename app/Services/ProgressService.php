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
