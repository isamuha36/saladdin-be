<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminContentService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function storeLesson(Request $request, $sectionId)
    {
        $messages = [
            'content_url.regex' => 'Link video harus berasal dari YouTube (youtube.com / youtu.be).',
        ];
        $youtubeRegex = '/^(https?:\/\/)?(www\.)?(youtube\.com\/(watch\?v=|embed\/|shorts\/)|youtu\.be\/)[A-Za-z0-9_-]{6,}([&?].*)?$/i';
        $request->validate([
            'title' => 'required|string|max:255',
            'type'  => ['required', Rule::in(['video', 'document', 'text', 'quiz'])],

            // untuk video & document wajib ada sumber konten
            'content_source' => [
                Rule::requiredIf(in_array($request->type, ['video', 'document'])),
                Rule::in(['upload', 'external']),
            ],

            // file utama kalau upload (video/document)
            'content_file' => [
                Rule::requiredIf(($request->content_source === 'upload') && in_array($request->type, ['video', 'document'])),
                'file',
                'max:512000', // KB -> ~500MB
                Rule::when($request->type === 'video', ['mimetypes:video/mp4,video/quicktime,video/x-msvideo']),
                Rule::when($request->type === 'document', ['mimetypes:application/pdf']),
            ],

            // url utama kalau external (video/document)
            'content_url' => [
                Rule::requiredIf(($request->content_source === 'external') && in_array($request->type, ['video', 'document'])),
                'url',
                Rule::when(
                    ($request->type === 'video' && $request->content_source === 'external'),
                    ['regex:' . $youtubeRegex],
                ),
            ],


            // text wajib punya konten text
            'content_text' => [
                Rule::requiredIf($request->type === 'text'),
                'nullable',
                'string',
            ],
        ], $messages);

        $lesson = $this->contentService->createLesson(
            $sectionId,
            $request->except(['content_file']),
            $request->file('content_file')
        );

        return response()->json(['status' => 'success', 'data' => $lesson]);
    }


    // Nanti Update & Delete bisa menyusul...
}
