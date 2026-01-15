<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;

class EnrollmentService
{
    /**
     * Check if user is enrolled in a course
     */
    public function isEnrolled($courseOrId, $user): bool
    {
        if (!$user) {
            return false;
        }

        $courseId = is_object($courseOrId) ? $courseOrId->id : $courseOrId;

        return Enrollment::where('course_id', $courseId)
            ->where('user_id', $user->id)
            ->whereIn('status', ['active', 'completed'])
            ->exists();
    }

    /**
     * Enroll user to a course
     */
    public function enrollUser($user, $courseId): array
    {
        $course = Course::findOrFail($courseId);

        // Check if already enrolled
        $alreadyEnrolled = Enrollment::where('course_id', $courseId)
            ->where('user_id', $user->id)
            ->whereIn('status', ['active', 'completed'])
            ->exists();

        if ($alreadyEnrolled) {
            return ['message' => 'Anda sudah terdaftar di kursus ini.'];
        }

        // Check existing enrollment status
        $existing = Enrollment::where('course_id', $courseId)
            ->where('user_id', $user->id)
            ->first();

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

        $enrollment = Enrollment::create([
            'course_id' => $courseId,
            'user_id' => $user->id,
            'enrolled_at' => now(),
            'status' => $status,
            'payment_proof' => null,
        ]);

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
        $courseIds = Enrollment::where('user_id', $user->id)
            ->whereIn('status', ['active', 'completed'])
            ->pluck('course_id')
            ->toArray();

        if (empty($courseIds)) {
            return collect([]);
        }

        return $courseIds;
    }
}
