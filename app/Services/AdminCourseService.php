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

    public function getAllCourses()
    {
        return $this->courseRepo->getAll();
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
            // (Opsional: Hapus gambar lama di sini kalau mau)

            $path = $fileThumbnail->store('thumbnails', 'public');
            $data['thumbnail'] = url('storage/' . $path);
        }

        return $this->courseRepo->update($id, $data);
    }

    public function deleteCourse($id)
    {
        // Bisa tambah logic: Cek apakah kursus ada muridnya? Kalau ada, jangan hapus.
        return $this->courseRepo->delete($id);
    }
}
