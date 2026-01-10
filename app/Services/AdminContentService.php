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
    public function createLesson($sectionId, array $data, $contentFile = null)
    {
        $data['section_id'] = $sectionId;
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);

        $maxOrder = \App\Models\Lesson::where('section_id', $sectionId)->max('sort_order');
        $data['sort_order'] = ($maxOrder ?? 0) + 1;

        // reset field konten utama
        $data['content_path'] = null;
        $data['content_url']  = $data['content_url'] ?? null;
        $data['content_mime'] = null;

        // UPLOAD file (video/pdf)
        if (($data['content_source'] ?? null) === 'upload' && $contentFile) {
            $mime = $contentFile->getMimeType();
            $data['content_mime'] = $mime;

            if (($data['type'] ?? null) === 'video') {
                $data['content_path'] = $contentFile->store('lessons/videos', 'public');
                $data['content_url'] = null;
            }

            if (($data['type'] ?? null) === 'document') {
                $data['content_path'] = $contentFile->store('lessons/documents', 'public');
                $data['content_url'] = null;
            }
        }

        // EXTERNAL url
        if (($data['content_source'] ?? null) === 'external') {
            $data['content_path'] = null;
            $data['content_mime'] = null;
            // content_url sudah ada dari request
        }

        return $this->contentRepo->createLesson($data);
    }
}
