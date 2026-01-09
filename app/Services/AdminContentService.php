<?php

namespace App\Services;

use App\Repositories\ContentRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminContentService
{
    protected $contentRepo;

    public function __construct(ContentRepository $contentRepo)
    {
        $this->contentRepo = $contentRepo;
    }

    // 1. BUAT BAB BARU
    public function createSection($courseId, $title)
    {
        // Hitung urutan terakhir biar otomatis di paling bawah
        $maxOrder = \App\Models\Section::where('course_id', $courseId)->max('sort_order');

        return $this->contentRepo->createSection([
            'course_id' => $courseId,
            'title' => $title,
            'sort_order' => $maxOrder + 1
        ]);
    }

    // 2. BUAT MATERI BARU (VIDEO/TEXT/QUIZ)
    public function createLesson($sectionId, array $data, $fileVideo = null, $fileAttachment = null)
    {
        $data['section_id'] = $sectionId;
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);

        // Hitung urutan
        $maxOrder = \App\Models\Lesson::where('section_id', $sectionId)->max('sort_order');
        $data['sort_order'] = $maxOrder + 1;

        // A. Handle Video Upload
        if ($fileVideo && $data['video_source'] === 'upload') {
            $path = $fileVideo->store('lessons/videos', 'public');
            $data['video_path'] = $path; // Simpan path saja
        }

        // B. Handle Attachment (PDF)
        if ($fileAttachment) {
            $path = $fileAttachment->store('lessons/attachments', 'public');
            $data['attachment_path'] = $path;
        }

        return $this->contentRepo->createLesson($data);
    }
}
