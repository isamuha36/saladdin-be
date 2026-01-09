<?php

namespace App\Repositories;

use App\Models\Section;
use App\Models\Lesson;

class ContentRepository
{
    // --- SECTION (BAB) ---
    public function createSection(array $data)
    {
        return Section::create($data);
    }

    public function updateSection($id, array $data)
    {
        $section = Section::findOrFail($id);
        $section->update($data);
        return $section;
    }

    public function deleteSection($id)
    {
        return Section::destroy($id);
    }

    // --- LESSON (MATERI) ---
    public function createLesson(array $data)
    {
        return Lesson::create($data);
    }

    public function updateLesson($id, array $data)
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->update($data);
        return $lesson;
    }

    public function deleteLesson($id)
    {
        return Lesson::destroy($id);
    }
}
