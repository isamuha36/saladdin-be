<?php

namespace App\Services;

use App\Repositories\CourseRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminCourseService
{
    protected $courseRepo;

    // Dependency Injection: Service butuh Repository
    public function __construct(CourseRepository $courseRepo)
    {
        $this->courseRepo = $courseRepo;
    }

    /**
     * Get all courses with filtering and pagination
     */
    public function getAllCourses(array $filters = [], int $perPage = 15)
    {
        return $this->courseRepo->getAllWithFilters($filters, $perPage);
    }

    /**
     * Get course detail with sections and lessons
     */
    public function getCourseDetail($id)
    {
        return $this->courseRepo->findWithSections($id);
    }

    public function createCourse(array $data, $fileThumbnail = null)
    {
        // 1. Logic Slug
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);

        // 2. Logic Upload Gambar
        if ($fileThumbnail) {
            $path = $fileThumbnail->store('thumbnails', 'public');
            $data['thumbnail'] = $path;
        }

        // 3. Panggil Repo buat simpan
        return $this->courseRepo->create($data);
    }

    public function updateCourse($id, array $data, $fileThumbnail = null)
    {
        // Logic Slug (Jika judul berubah)
        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);
        }

        // Logic Ganti Gambar
        if ($fileThumbnail) {
            // Get old course to delete old thumbnail
            $course = $this->courseRepo->find($id);
            if ($course && $course->thumbnail) {
                // Extract path from URL or use directly if it's a path
                $oldPath = str_replace(asset('storage/'), '', $course->thumbnail);
                if (!filter_var($oldPath, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $path = $fileThumbnail->store('thumbnails', 'public');
            $data['thumbnail'] = $path; // Store path only, accessor will convert to URL
        }

        return $this->courseRepo->update($id, $data);
    }

    public function deleteCourse($id)
    {
        // Bisa tambah logic: Cek apakah kursus ada muridnya? Kalau ada, jangan hapus.
        return $this->courseRepo->delete($id);
    }
}
