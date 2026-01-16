<?php

namespace App\Services;

use App\Repositories\LessonRepository;
use App\Repositories\CourseRepository;

class LessonService
{
    protected $lessonRepository;
    protected $courseRepository;
    protected $enrollmentService;
    protected $lessonAccessService;

    public function __construct(
        LessonRepository $lessonRepository,
        CourseRepository $courseRepository,
        EnrollmentService $enrollmentService,
        LessonAccessService $lessonAccessService
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->courseRepository = $courseRepository;
        $this->enrollmentService = $enrollmentService;
        $this->lessonAccessService = $lessonAccessService;
    }

    /**
     * Get lesson detail with access check
     */
    public function getLessonDetail($id, $user)
    {
        $lesson = $this->lessonRepository->findWithCourse($id);

        if (!$lesson) {
            abort(404, 'Lesson not found.');
        }

        // Check access (includes enrollment & sequential check)
        $accessCheck = $this->lessonAccessService->canAccessLesson($lesson, $user);

        if (!$accessCheck['can_access']) {
            abort(403, $accessCheck['message']);
        }

        return $lesson;
    }

    /**
     * Complete a non-quiz lesson
     */
    public function completeLesson($lessonId, $user): array
    {
        $lesson = $this->lessonRepository->findById($lessonId);

        if (!$lesson) {
            abort(404, 'Lesson not found.');
        }

        if ($lesson->type === 'quiz') {
            return [
                'message' => 'Lesson ini adalah quiz. Silakan submit jawaban melalui endpoint /api/lessons/' . $lessonId . '/submit-quiz. Quiz akan otomatis ditandai selesai jika nilai Anda mencapai passing grade (' . $lesson->passing_grade . ').',
                'type' => 'quiz',
                'passing_grade' => $lesson->passing_grade,
            ];
        }

        // Check access (includes enrollment & sequential check)
        $accessCheck = $this->lessonAccessService->canAccessLesson($lesson, $user);

        if (!$accessCheck['can_access']) {
            abort(403, $accessCheck['message']);
        }

        // Check if already completed
        if ($this->lessonRepository->isCompleted($lessonId, $user->id)) {
            return ['message' => 'Lesson sudah diselesaikan sebelumnya.'];
        }

        $this->lessonRepository->markAsCompleted($lessonId, $user->id);

        return ['message' => 'Lesson berhasil diselesaikan!'];
    }
}
