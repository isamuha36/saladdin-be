<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonCompletion;
use App\Models\Lesson;
use App\Models\Certificate;

class DashboardService
{
    protected $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    /**
     * Get dashboard statistics for student
     */
    public function getDashboardStats($user): array
    {
        // Total enrolled courses
        $totalEnrolled = Enrollment::where('user_id', $user->id)
            ->whereIn('status', ['active', 'completed'])
            ->count();

        // In progress courses (enrolled but progress < 100%)
        $enrolledCourseIds = Enrollment::where('user_id', $user->id)
            ->whereIn('status', ['active', 'completed'])
            ->pluck('course_id');

        $inProgress = 0;
        $completed = 0;

        foreach ($enrolledCourseIds as $courseId) {
            $course = Course::with(['sections.lessons'])->find($courseId);
            if (!$course) continue;

            $progress = $this->progressService->calculateProgress($course, $user);

            if ($progress >= 100) {
                $completed++;
            } elseif ($progress > 0) {
                $inProgress++;
            }
        }

        // Get actual certificate count from database
        $certificates = Certificate::where('user_id', $user->id)->count();

        return [
            'total_enrolled' => $totalEnrolled,
            'in_progress' => $inProgress,
            'completed' => $completed,
            'certificates' => $certificates,
        ];
    }

    /**
     * Get last accessed lesson for continue learning
     */
    public function getLastAccessedLesson($user): ?array
    {
        // Get last completed lesson
        $lastCompletion = LessonCompletion::where('user_id', $user->id)
            ->with(['lesson.section.course'])
            ->orderBy('completed_at', 'desc')
            ->first();

        if (!$lastCompletion) {
            return null;
        }

        $lesson = $lastCompletion->lesson;
        $section = $lesson->section;
        $course = $section->course;

        // Find next lesson (if any)
        $nextLesson = Lesson::where('section_id', $section->id)
            ->where('sort_order', '>', $lesson->sort_order)
            ->orderBy('sort_order')
            ->first();

        // If no next lesson in current section, find first lesson of next section
        if (!$nextLesson) {
            $nextSection = $course->sections()
                ->where('sort_order', '>', $section->sort_order)
                ->orderBy('sort_order')
                ->first();

            if ($nextSection) {
                $nextLesson = $nextSection->lessons()->orderBy('sort_order')->first();
            }
        }

        return [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'thumbnail' => $course->thumbnail,
            ],
            'last_lesson' => [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'type' => $lesson->type,
                'completed_at' => $lastCompletion->completed_at,
            ],
            'next_lesson' => $nextLesson ? [
                'id' => $nextLesson->id,
                'title' => $nextLesson->title,
                'slug' => $nextLesson->slug,
                'type' => $nextLesson->type,
            ] : null,
            'section' => [
                'id' => $section->id,
                'title' => $section->title,
            ],
        ];
    }
}
