<?php

namespace App\Services;

use App\Models\Course;
use App\Repositories\EnrollmentRepository;

class EnrollmentService
{
    protected $enrollmentRepo;

    public function __construct(EnrollmentRepository $enrollmentRepo)
    {
        $this->enrollmentRepo = $enrollmentRepo;
    }

    /**
     * Check if user is enrolled in a course
     */
    public function isEnrolled($courseOrId, $user): bool
    {
        if (!$user) {
            return false;
        }

        $courseId = is_object($courseOrId) ? $courseOrId->id : $courseOrId;

        return $this->enrollmentRepo->isEnrolled($user->id, $courseId);
    }

    /**
     * Enroll user to a course
     */
    public function enrollUser($user, $courseId): array
    {
        $course = Course::findOrFail($courseId);

        // Check if already enrolled
        if ($this->enrollmentRepo->isEnrolled($user->id, $courseId)) {
            return ['message' => 'Anda sudah terdaftar di kursus ini.'];
        }

        // Check existing enrollment
        $existing = $this->enrollmentRepo->getUserEnrollment($user->id, $courseId);

        if ($existing) {
            if ($existing->status === 'pending') {
                return ['message' => 'Enrollment Anda sedang diproses.'];
            }
            if ($existing->status === 'blocked') {
                return ['message' => 'Enrollment Anda diblokir. Hubungi admin.'];
            }
        }

        // Free course -> active, paid -> pending
        $status = ($course->price == 0) ? 'active' : 'pending';

        $enrollment = $this->enrollmentRepo->enroll($user->id, $courseId, $status);

        $message = ($status === 'active')
            ? 'Berhasil mendaftar ke kursus ini!'
            : 'Enrollment diajukan, tunggu approval.';

        return ['message' => $message, 'enrollment_id' => $enrollment->id];
    }

    /**
     * Get user's enrolled courses
     */
    public function getUserCourses($user)
    {
        $enrollments = $this->enrollmentRepo->getUserEnrollments($user->id);

        return $enrollments->pluck('course_id')->toArray();
    }
}
