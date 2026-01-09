<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminContentService;
use Illuminate\Http\Request;

class AdminContentController extends Controller
{
    protected $contentService;

    public function __construct(AdminContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    // 1. TAMBAH SECTION KE KURSUS
    public function storeSection(Request $request, $courseId)
    {
        $request->validate(['title' => 'required|string']);

        $section = $this->contentService->createSection($courseId, $request->title);

        return response()->json(['status' => 'success', 'data' => $section]);
    }

    // 2. TAMBAH LESSON KE SECTION
    public function storeLesson(Request $request, $sectionId)
    {
        // Validasi Kompleks sesuai Tipe Lesson
        $request->validate([
            'title' => 'required|string',
            'type' => 'required|in:video,text,quiz',
            'video_source' => 'required_if:type,video|in:upload,youtube,vimeo',
            // Jika source upload, wajib ada file video. Jika youtube, wajib url.
            'video_file' => 'required_if:video_source,upload|mimes:mp4,mov,avi|max:50000', // 50MB limit
            'video_url' => 'required_if:video_source,youtube|url',
            'content_text' => 'nullable|string',
        ]);

        $lesson = $this->contentService->createLesson(
            $sectionId,
            $request->except(['video_file', 'attachment_file']), // Data Text
            $request->file('video_file'),      // File Video
            $request->file('attachment_file')  // File PDF
        );

        return response()->json(['status' => 'success', 'data' => $lesson]);
    }

    // Nanti Update & Delete bisa menyusul...
}
