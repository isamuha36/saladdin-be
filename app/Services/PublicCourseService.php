<?php

namespace App\Services;

use App\Models\Course;
use App\Repositories\CourseRepository;

class PublicCourseService
{
    protected $courseRepository;
    protected $enrollmentService;
    protected $progressService;

    public function __construct(
        CourseRepository $courseRepository,
        EnrollmentService $enrollmentService,
        ProgressService $progressService
    ) {
        $this->courseRepository = $courseRepository;
        $this->enrollmentService = $enrollmentService;
        $this->progressService = $progressService;
    }

    /**
     * Get course catalog
     */
    public function getCatalog($search)
    {
        return $this->courseRepository->getPublishedCourses($search);
    }

    /**
     * Get course detail with enrollment status and progress
     */
    public function getCourseDetail($slug, $user = null)
    {
        $course = $this->courseRepository->getDetailCourseBySlug($slug);

        $progress = null;
        $isEnrolled = false;
        $completedLessonIds = [];

        if ($user) {
            // Admin always has access — calculate actual progress in case they've completed lessons
            if (isset($user->role) && $user->role === 'admin') {
                $isEnrolled = true;
                $progress = $this->progressService->calculateProgress($course, $user);
                $completedLessonIds = \App\Models\LessonCompletion::where('user_id', $user->id)
                    ->whereIn('lesson_id', $course->sections->flatMap->lessons->pluck('id'))
                    ->pluck('lesson_id')
                    ->toArray();
            } else {
                // Check enrollment
                $isEnrolled = $this->enrollmentService->isEnrolled($course, $user);

                if ($isEnrolled) {
                    $progress = $this->progressService->calculateProgress($course, $user);

                    // Get completed lesson IDs for this user
                    $completedLessonIds = \App\Models\LessonCompletion::where('user_id', $user->id)
                        ->whereIn('lesson_id', $course->sections->flatMap->lessons->pluck('id'))
                        ->pluck('lesson_id')
                        ->toArray();
                }
            }
        }

        // Attach progress & enrollment status
        $course->progress = $progress;
        $course->is_enrolled = $isEnrolled;
        $course->completed_lesson_ids = $completedLessonIds;

        return $course;
    }

    /**
     * Get user's enrolled courses with progress
     */
    public function getUserCourses($user)
    {
        $courseIds = $this->enrollmentService->getUserCourses($user);

        if (empty($courseIds)) {
            return collect([]);
        }

        $courses = Course::with(['sections.lessons'])
            ->whereIn('id', $courseIds)
            ->get();

        // Attach progress for each course
        foreach ($courses as $course) {
            $course->is_enrolled = true;
            $course->progress = $this->progressService->calculateProgress($course, $user);
        }

        return $courses;
    }
}
