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

    // Method baru untuk Detail (Silabus)
    public function getDetailCourseBySlug($slug)
    {
        return Course::select(
            'id',
            'title',
            'slug',
            'thumbnail',
            'price',
            'description',
            'instructor_name'
        )
            ->with([
                'sections' => function ($query) {
                    // Pilih kolom section
                    $query->select('id', 'course_id', 'title', 'sort_order');
                },
                // Pilih kolom lesson
                'sections.lessons:id,section_id,title,slug,type'
            ])
            ->where('slug', $slug)
            ->where('status', 'published')
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
        // Ambil Lesson beserta Pertanyaan & Opsi (untuk jaga-jaga kalau dia Quiz)
        return \App\Models\Lesson::with(['questions.options'])
            ->findOrFail($id);
    }

    public function checkEnrollment($userId, $courseId)
    {
        return \App\Models\Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('status', 'active') // Hanya yang statusnya 'active' (sudah bayar)
            ->exists();
    }
}
