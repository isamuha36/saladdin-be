<?php

namespace App\Repositories;

use App\Models\Course;

class CourseRepository
{
    // Public (Catalog)
    public function getPublishedCourses($search = null)
    {
        $query = Course::where('status', 'published');

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        return $query->latest()->get();
    }

    // Detail (Silabus)
    public function getDetailCourseBySlug($slug)
    {
        return Course::with(['sections.lessons'])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function findBySlug($slug)
    {
        return Course::with(['sections.lessons.questions.options'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
    }

    // Ambil semua data dengan filter dan pagination
    public function getAllWithFilters(array $filters = [], int $perPage = 15)
    {
        $query = Course::withCount('students')->with('sections');

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Search by title or instructor
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('instructor_name', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Filter by instructor
        if (!empty($filters['instructor'])) {
            $query->where('instructor_name', 'like', '%' . $filters['instructor'] . '%');
        }

        return $query->latest()->paginate($perPage);
    }

    public function getAll()
    {
        return Course::withCount('students')->latest()->get();
    }

    public function findWithSections($id)
    {
        return Course::with(['sections.lessons', 'certificateConfig', 'certificateSignatures'])
            ->withCount('students')
            ->findOrFail($id);
    }

    public function findById($id)
    {
        return Course::findOrFail($id);
    }

    public function create(array $data)
    {
        return Course::create($data);
    }

    public function update($id, array $data)
    {
        $course = $this->findById($id);
        $course->update($data);
        return $course;
    }

    public function delete($id)
    {
        $course = $this->findById($id);
        return $course->delete();
    }

    public function findLessonById($id)
    {
        return \App\Models\Lesson::with(['questions'])
            ->findOrFail($id);
    }

    public function checkEnrollment($userId, $courseId)
    {
        return \App\Models\Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('status', 'active') // Hanya yang statusnya 'active' (sudah bayar)
            ->exists();
    }

    public function findCourseById($courseId)
    {
        return Course::findOrFail($courseId);
    }
}
