<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Repositories\CourseRepository;

class LessonService
{
    protected $courseRepository;
    protected $enrollmentService;
    protected $lessonAccessService;

    public function __construct(
        CourseRepository $courseRepository,
        EnrollmentService $enrollmentService,
        LessonAccessService $lessonAccessService
    ) {
        $this->courseRepository = $courseRepository;
        $this->enrollmentService = $enrollmentService;
        $this->lessonAccessService = $lessonAccessService;
    }

    /**
     * Get lesson detail with access check
     */
    public function getLessonDetail($id, $user)
    {
        $lesson = $this->courseRepository->findLessonById($id);

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
        $lesson = $this->courseRepository->findLessonById($lessonId);

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
        $existing = LessonCompletion::where('user_id', $user->id)
            ->where('lesson_id', $lessonId)
            ->first();

        if ($existing) {
            return ['message' => 'Lesson sudah diselesaikan sebelumnya.'];
        }

        LessonCompletion::create([
            'user_id' => $user->id,
            'lesson_id' => $lessonId,
            'completed_at' => now(),
        ]);

        return ['message' => 'Lesson berhasil diselesaikan!'];
    }
}
