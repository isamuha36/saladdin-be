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

    // Ambil semua data (bisa dipaginate kalau mau)
    public function getAll()
    {
        return Course::withCount('students')->latest()->get();
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
