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
    protected $certificateService;
    protected $progressService;

    public function __construct(
        LessonRepository $lessonRepository,
        CourseRepository $courseRepository,
        EnrollmentService $enrollmentService,
        LessonAccessService $lessonAccessService,
        CertificateService $certificateService,
        ProgressService $progressService
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->courseRepository = $courseRepository;
        $this->enrollmentService = $enrollmentService;
        $this->lessonAccessService = $lessonAccessService;
        $this->certificateService = $certificateService;
        $this->progressService = $progressService;
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
            return [
                'message' => 'Lesson sudah diselesaikan sebelumnya.',
                'is_completed' => true,
            ];
        }

        $this->lessonRepository->markAsCompleted($lessonId, $user->id);

        // Check if course is now 100% complete and issue certificate
        $lesson = $this->lessonRepository->findWithCourse($lessonId);
        $courseId = $lesson->section->course_id;

        $certificate = $this->checkAndIssueCertificate($user->id, $courseId);

        $response = ['message' => 'Lesson berhasil diselesaikan!'];

        if ($certificate) {
            $response['certificate_issued'] = true;
            $response['certificate_number'] = $certificate->certificate_number;
            $response['message'] = 'Selamat! Anda telah menyelesaikan course ini dan mendapatkan sertifikat!';
        }

        return $response;
    }

    /**
     * Check course completion and issue certificate if 100%
     */
    protected function checkAndIssueCertificate(int $userId, int $courseId)
    {
        // Check if progress is 100%
        $progress = $this->progressService->calculateProgress($userId, $courseId);

        if ($progress >= 100.00) {
            // Issue certificate if not already issued
            return $this->certificateService->issueCertificate($userId, $courseId);
        }

        return null;
    }
}
