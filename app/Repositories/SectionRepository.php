<?php

namespace App\Repositories;

use App\Models\Section;

class SectionRepository
{
    /**
     * Get section by ID with relations
     */
    public function findById(int $sectionId)
    {
        return Section::with(['course', 'lessons'])->find($sectionId);
    }

    /**
     * Get all sections for a course
     */
    public function getByCourseId(int $courseId)
    {
        return Section::where('course_id', $courseId)
            ->with('lessons')
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get section with completed lessons for a user
     */
    public function getSectionWithProgress(int $sectionId, int $userId)
    {
        return Section::with(['lessons' => function ($query) use ($userId) {
            $query->with(['completions' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }]);
        }])->find($sectionId);
    }

    /**
     * Get previous section in same course
     */
    public function getPreviousSection(int $courseId, int $sortOrder): ?Section
    {
        return Section::where('course_id', $courseId)
            ->where('sort_order', '<', $sortOrder)
            ->orderBy('sort_order', 'desc')
            ->first();
    }

    /**
     * Get next section in same course
     */
    public function getNextSection(int $courseId, int $sortOrder): ?Section
    {
        return Section::where('course_id', $courseId)
            ->where('sort_order', '>', $sortOrder)
            ->orderBy('sort_order', 'asc')
            ->first();
    }

    /**
     * Check if all lessons in section are completed by user
     */
    public function isSectionCompleted(int $sectionId, int $userId): bool
    {
        $section = $this->findById($sectionId);

        if (!$section || $section->lessons->isEmpty()) {
            return false;
        }

        foreach ($section->lessons as $lesson) {
            $isCompleted = $lesson->completions()
                ->where('user_id', $userId)
                ->exists();

            if (!$isCompleted) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get total lessons count in a section
     */
    public function getTotalLessons(int $sectionId): int
    {
        return Section::find($sectionId)?->lessons()->count() ?? 0;
    }

    /**
     * Get completed lessons count in a section for a user
     */
    public function getCompletedLessonsCount(int $sectionId, int $userId): int
    {
        $section = $this->findById($sectionId);

        if (!$section) {
            return 0;
        }

        $completedCount = 0;
        foreach ($section->lessons as $lesson) {
            if ($lesson->completions()->where('user_id', $userId)->exists()) {
                $completedCount++;
            }
        }

        return $completedCount;
    }
}
