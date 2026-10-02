<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\Section;
use App\Models\LessonCompletion;

class LessonAccessService
{
    protected $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    /**
     * Check if user can access a lesson (sequential access)
     */
    public function canAccessLesson($lesson, $user): array
    {
        // Admin can access everything
        if (isset($user->role) && $user->role === 'admin') {
            return ['can_access' => true];
        }

        // Check enrollment
        $courseId = $lesson->section->course_id;
        if (!$this->enrollmentService->isEnrolled($courseId, $user)) {
            return [
                'can_access' => false,
                'reason' => 'not_enrolled',
                'message' => 'Anda belum terdaftar di kursus ini.',
            ];
        }

        $section = $lesson->section;
        $course = $section->course;

        // Check all previous sections are completed
        $previousSections = $course->sections()
            ->where('sort_order', '<', $section->sort_order)
            ->with('lessons')
            ->orderBy('sort_order')
            ->get();

        foreach ($previousSections as $prevSection) {
            $allLessonsCompleted = $this->isSectionCompleted($prevSection, $user);
            if (!$allLessonsCompleted) {
                return [
                    'can_access' => false,
                    'reason' => 'previous_section_incomplete',
                    'message' => "Selesaikan semua lesson di section '{$prevSection->title}' terlebih dahulu.",
                    'blocking_section' => $prevSection->title,
                ];
            }
        }

        // Check previous lessons in current section
        $previousLessons = $section->lessons()
            ->where('sort_order', '<', $lesson->sort_order)
            ->orderBy('sort_order')
            ->get();

        foreach ($previousLessons as $prevLesson) {
            $isCompleted = LessonCompletion::where('user_id', $user->id)
                ->where('lesson_id', $prevLesson->id)
                ->exists();

            if (!$isCompleted) {
                return [
                    'can_access' => false,
                    'reason' => 'previous_lesson_incomplete',
                    'message' => "Selesaikan lesson '{$prevLesson->title}' terlebih dahulu.",
                    'blocking_lesson' => $prevLesson->title,
                ];
            }
        }

        return ['can_access' => true];
    }

    /**
     * Check if all lessons in a section are completed
     */
    protected function isSectionCompleted($section, $user): bool
    {
        $totalLessons = $section->lessons->count();

        if ($totalLessons === 0) {
            return true;
        }

        $completedLessons = LessonCompletion::where('user_id', $user->id)
            ->whereIn('lesson_id', $section->lessons->pluck('id'))
            ->count();

        return $completedLessons === $totalLessons;
    }
}
