<?php

namespace App\Repositories;

use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;

class EnrollmentRepository
{
    /**
     * Check if user is enrolled in a course
     */
    public function isEnrolled(int $userId, int $courseId): bool
    {
        return Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->whereIn('status', ['active', 'completed'])
            ->exists();
    }

    /**
     * Get user's enrollment for a specific course
     */
    public function getUserEnrollment(int $userId, int $courseId): ?Enrollment
    {
        return Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();
    }

    /**
     * Get all active enrollments for a user
     */
    public function getUserEnrollments(int $userId)
    {
        return Enrollment::where('user_id', $userId)
            ->whereIn('status', ['active', 'completed'])
            ->with('course')
            ->get();
    }

    /**
     * Create new enrollment
     */
    public function enroll(int $userId, int $courseId, string $status = 'active'): Enrollment
    {
        return Enrollment::create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'status' => $status,
            'enrolled_at' => now(),
        ]);
    }

    /**
     * Update enrollment status
     */
    public function updateStatus(int $enrollmentId, string $status): bool
    {
        return Enrollment::where('id', $enrollmentId)->update(['status' => $status]);
    }

    /**
     * Count enrollments by status for a user
     */
    public function countByStatus(int $userId, string $status): int
    {
        return Enrollment::where('user_id', $userId)
            ->where('status', $status)
            ->count();
    }

    /**
     * Get enrollment with course and progress
     */
    public function getEnrollmentWithProgress(int $userId, int $courseId)
    {
        return Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->with(['course.sections.lessons'])
            ->first();
    }
}
