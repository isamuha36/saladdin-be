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

    // ============ SECTION CRUD ============

    /**
     * Get all sections for a course
     */
    public function indexSections($courseId)
    {
        $sections = $this->contentService->getSectionsByCourse($courseId);
        return response()->json(['status' => 'success', 'data' => $sections]);
    }

    /**
     * Get single section with lessons
     */
    public function showSection($sectionId)
    {
        $section = $this->contentService->getSectionDetail($sectionId);
        return response()->json(['status' => 'success', 'data' => $section]);
    }

    /**
     * Create section
     */
    public function storeSection(Request $request, $courseId)
    {
        $request->validate(['title' => 'required|string']);

        $section = $this->contentService->createSection($courseId, $request->title);

        return response()->json(['status' => 'success', 'data' => $section]);
    }

    /**
     * Update section
     */
    public function updateSection(Request $request, $sectionId)
    {
        $request->validate(['title' => 'required|string']);

        $section = $this->contentService->updateSection($sectionId, $request->only('title', 'order'));

        return response()->json(['status' => 'success', 'data' => $section]);
    }

    /**
     * Delete section
     */
    public function destroySection($sectionId)
    {
        $this->contentService->deleteSection($sectionId);

        return response()->json(['status' => 'success', 'message' => 'Section deleted successfully']);
    }

    // ============ LESSON CRUD ============

    /**
     * Get all lessons in a section
     */
    public function indexLessons($sectionId)
    {
        $lessons = $this->contentService->getLessonsBySection($sectionId);
        return response()->json(['status' => 'success', 'data' => $lessons]);
    }

    /**
     * Get single lesson detail
     */
    public function showLesson($lessonId)
    {
        try {
            $lesson = $this->contentService->getLessonDetail($lessonId);
            return response()->json(['status' => 'success', 'data' => $lesson]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lesson not found with ID: ' . $lessonId
            ], 404);
        }
    }

    /**
     * Create lesson (video, document, text, or quiz)
     */
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

        return response()->json(['status' => 'success', 'data' => $lesson], 201);
    }

    /**
     * Update lesson
     */
    public function updateLesson(Request $request, $lessonId)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'type' => ['nullable', Rule::in(['video', 'document', 'text', 'quiz'])],
            'content_text' => 'nullable|string',
            'content_url' => 'nullable|url',
        ]);

        $lesson = $this->contentService->updateLesson(
            $lessonId,
            $request->except(['content_file']),
            $request->file('content_file')
        );

        return response()->json(['status' => 'success', 'data' => $lesson]);
    }

    /**
     * Delete lesson
     */
    public function destroyLesson($lessonId)
    {
        $this->contentService->deleteLesson($lessonId);

        return response()->json(['status' => 'success', 'message' => 'Lesson deleted successfully']);
    }

    // ============ QUIZ BUILDER (Save Questions 1-1) ============

    /**
     * Get quiz with all questions for editing
     */
    public function getQuizBuilder($lessonId)
    {
        $quiz = $this->contentService->getQuizWithQuestions($lessonId);
        return response()->json(['status' => 'success', 'data' => $quiz]);
    }

    /**
     * Add single question to quiz (save 1-1)
     */
    public function storeQuestion(Request $request, $lessonId)
    {
        $request->validate([
            'question_text' => 'required|string',
            'points' => 'required|integer|min:1',
            'sequence' => 'nullable|integer',
            'options' => 'required|array|min:2',
            'options.*.option_text' => 'required|string',
            'options.*.is_correct' => 'required|boolean',
        ]);

        $question = $this->contentService->addQuestionToQuiz(
            $lessonId,
            $request->only(['question_text', 'points', 'sequence']),
            $request->input('options')
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Question added successfully',
            'data' => $question
        ], 201);
    }

    /**
     * Update single question
     */
    public function updateQuestion(Request $request, $questionId)
    {
        $request->validate([
            'question_text' => 'nullable|string',
            'points' => 'nullable|integer|min:1',
            'sequence' => 'nullable|integer',
            'options' => 'nullable|array|min:2',
            'options.*.id' => 'nullable|integer',
            'options.*.option_text' => 'required_with:options|string',
            'options.*.is_correct' => 'required_with:options|boolean',
        ]);

        $question = $this->contentService->updateQuestion(
            $questionId,
            $request->only(['question_text', 'points', 'sequence']),
            $request->input('options')
        );

        return response()->json(['status' => 'success', 'data' => $question]);
    }

    /**
     * Delete single question
     */
    public function destroyQuestion($questionId)
    {
        $this->contentService->deleteQuestion($questionId);

        return response()->json(['status' => 'success', 'message' => 'Question deleted successfully']);
    }

    /**
     * Reorder questions in quiz
     */
    public function reorderQuestions(Request $request, $lessonId)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*.id' => 'required|integer',
            'questions.*.sequence' => 'required|integer',
        ]);

        $this->contentService->reorderQuestions($lessonId, $request->input('questions'));

        return response()->json(['status' => 'success', 'message' => 'Questions reordered successfully']);
    }
}
