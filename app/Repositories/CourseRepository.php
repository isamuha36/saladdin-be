<?php

namespace App\Repositories;

use App\Models\Course;

class CourseRepository
{
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
}
