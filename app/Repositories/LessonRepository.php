<?php

namespace App\Repositories;

use App\Models\Lesson;
use App\Models\LessonCompletion;

class LessonRepository
{
    /**
     * Get lesson by ID with relations
     */
    public function findById(int $lessonId)
    {
        return Lesson::with(['section.course'])->find($lessonId);
    }

    /**
     * Get lesson by ID with quiz questions
     */
    public function findWithQuestions(int $lessonId)
    {
        return Lesson::with(['questions.options'])->find($lessonId);
    }

    /**
     * Get lesson with section and course
     */
    public function findWithCourse(int $lessonId)
    {
        return Lesson::with(['section.course'])->find($lessonId);
    }

    /**
     * Check if lesson is completed by user
     */
    public function isCompleted(int $lessonId, int $userId): bool
    {
        return LessonCompletion::where('lesson_id', $lessonId)
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Mark lesson as completed
     */
    public function markAsCompleted(int $lessonId, int $userId): void
    {
        LessonCompletion::firstOrCreate([
            'lesson_id' => $lessonId,
            'user_id' => $userId,
        ], [
            'completed_at' => now(),
        ]);
    }

    /**
     * Get all completed lessons for a user
     */
    public function getCompletedLessons(int $userId)
    {
        return LessonCompletion::where('user_id', $userId)
            ->with('lesson')
            ->get();
    }

    /**
     * Get completed lessons count for a course
     */
    public function getCompletedLessonsCount(int $userId, int $courseId): int
    {
        return LessonCompletion::where('user_id', $userId)
            ->whereHas('lesson.section', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->count();
    }

    /**
     * Get all lessons in a section
     */
    public function getLessonsBySection(int $sectionId)
    {
        return Lesson::where('section_id', $sectionId)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get previous lesson in same section
     */
    public function getPreviousLesson(int $sectionId, int $sortOrder): ?Lesson
    {
        return Lesson::where('section_id', $sectionId)
            ->where('sort_order', '<', $sortOrder)
            ->orderBy('sort_order', 'desc')
            ->first();
    }

    /**
     * Get next lesson in same section
     */
    public function getNextLesson(int $sectionId, int $sortOrder): ?Lesson
    {
        return Lesson::where('section_id', $sectionId)
            ->where('sort_order', '>', $sortOrder)
            ->orderBy('sort_order', 'asc')
            ->first();
    }

    /**
     * Get last accessed lesson for a user across all courses
     */
    public function getLastAccessedLesson(int $userId): ?LessonCompletion
    {
        return LessonCompletion::where('user_id', $userId)
            ->with(['lesson.section.course'])
            ->latest('completed_at')
            ->first();
    }
}
