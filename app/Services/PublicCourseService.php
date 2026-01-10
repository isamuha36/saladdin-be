<?php
namespace App\Services;

use App\Repositories\CourseRepository;
use Illuminate\Support\Facades\Auth;

class PublicCourseService
{
    protected $courseRepo;

    public function __construct(CourseRepository $courseRepo)
    {
        $this->courseRepo = $courseRepo;
    }

    public function getCatalog($search)
    {
        return $this->courseRepo->getPublishedCourses($search);
    }

    public function getCourseDetail($slug)
    {
        return $this->courseRepo->getDetailCourseBySlug($slug);
    }


    public function getLessonDetail($id)
    {
        // 1. Ambil data Lesson
        $lesson = $this->courseRepo->findLessonById($id);

        $user = Auth::user();

        // B. Kalau Admin, lolos (bebas akses semua).
        if ($user->role === 'admin') {
            return $lesson;
        }

        // C. Cek Enrollment (Apakah sudah beli?)
        // Kita cari Course ID dari relasi Lesson -> Section -> Course
        $courseId = $lesson->section->course_id;

        $hasAccess = $this->courseRepo->checkEnrollment($user->id, $courseId);

        if (!$hasAccess) {
            // Stop proses dan lempar Error 403 Forbidden
            abort(403, 'Anda belum membeli kursus ini.');
        }

        // 3. Kalau lolos semua pengecekan, kembalikan data
        return $lesson;
    }
}