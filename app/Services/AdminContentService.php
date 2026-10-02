<?php

namespace App\Services;

use App\Repositories\ContentRepository;
use App\Models\Section;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminContentService
{
    protected $contentRepo;

    public function __construct(ContentRepository $contentRepo)
    {
        $this->contentRepo = $contentRepo;
    }

    // ============ SECTION CRUD ============

    public function getSectionsByCourse($courseId)
    {
        return Section::where('course_id', $courseId)
            ->withCount('lessons')
            ->orderBy('sort_order')
            ->get();
    }

    public function getSectionDetail($sectionId)
    {
        return Section::with('lessons')->findOrFail($sectionId);
    }

    public function createSection($courseId, $title)
    {
        // Hitung urutan terakhir biar otomatis di paling bawah
        $maxOrder = Section::where('course_id', $courseId)->max('sort_order');

        return $this->contentRepo->createSection([
            'course_id' => $courseId,
            'title' => $title,
            'sort_order' => $maxOrder + 1
        ]);
    }

    public function updateSection($sectionId, array $data)
    {
        $section = Section::findOrFail($sectionId);
        $section->update($data);
        return $section->fresh();
    }

    public function deleteSection($sectionId)
    {
        $section = Section::findOrFail($sectionId);

        // Delete all lessons in section (cascade)
        foreach ($section->lessons as $lesson) {
            $this->deleteLesson($lesson->id);
        }

        $section->delete();
    }

    // ============ LESSON CRUD ============

    public function getLessonsBySection($sectionId)
    {
        return Lesson::where('section_id', $sectionId)
            ->withCount('questions')
            ->orderBy('sort_order')
            ->get();
    }

    public function getLessonDetail($lessonId)
    {
        return Lesson::with(['section', 'questions.options'])->findOrFail($lessonId);
    }

    public function createLesson($sectionId, array $data, $contentFile = null)
    {
        $data['section_id'] = $sectionId;
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);

        $maxOrder = Lesson::where('section_id', $sectionId)->max('sort_order');
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

    public function updateLesson($lessonId, array $data, $contentFile = null)
    {
        $lesson = Lesson::findOrFail($lessonId);

        // Update slug if title changed
        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);
        }

        // Handle file upload if provided
        if ($contentFile) {
            // Delete old file if exists
            if ($lesson->content_path) {
                Storage::disk('public')->delete($lesson->content_path);
            }

            $mime = $contentFile->getMimeType();
            $data['content_mime'] = $mime;

            if ($lesson->type === 'video') {
                $data['content_path'] = $contentFile->store('lessons/videos', 'public');
            } elseif ($lesson->type === 'document') {
                $data['content_path'] = $contentFile->store('lessons/documents', 'public');
            }
        }

        $lesson->update($data);
        return $lesson->fresh();
    }

    public function deleteLesson($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);

        // Delete uploaded file if exists
        if ($lesson->content_path) {
            Storage::disk('public')->delete($lesson->content_path);
        }

        // Delete all questions if quiz type
        if ($lesson->type === 'quiz') {
            foreach ($lesson->questions as $question) {
                $question->options()->delete();
                $question->delete();
            }
        }

        $lesson->delete();
    }

    // ============ QUIZ BUILDER (Save 1-1) ============

    public function getQuizWithQuestions($lessonId)
    {
        $lesson = Lesson::with(['questions' => function ($query) {
            $query->orderBy('sequence')->with('options');
        }])->findOrFail($lessonId);

        if ($lesson->type !== 'quiz') {
            throw new \Exception('This lesson is not a quiz');
        }

        return $lesson;
    }

    public function addQuestionToQuiz($lessonId, array $questionData, array $options)
    {
        $lesson = Lesson::findOrFail($lessonId);

        if ($lesson->type !== 'quiz') {
            throw new \Exception('This lesson is not a quiz');
        }

        return DB::transaction(function () use ($lesson, $questionData, $options) {
            // Get next sequence number
            $maxSequence = Question::where('lesson_id', $lesson->id)->max('sequence');
            $questionData['sequence'] = $questionData['sequence'] ?? ($maxSequence + 1);
            $questionData['lesson_id'] = $lesson->id;

            // Create question
            $question = Question::create($questionData);

            // Create options
            foreach ($options as $optionData) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $optionData['option_text'],
                    'is_correct' => $optionData['is_correct'] ?? false,
                ]);
            }

            return $question->load('options');
        });
    }

    public function updateQuestion($questionId, array $questionData, array $options = null)
    {
        $question = Question::findOrFail($questionId);

        return DB::transaction(function () use ($question, $questionData, $options) {
            // Update question
            $question->update($questionData);

            // Update options if provided
            if ($options !== null) {
                // Delete old options
                $question->options()->delete();

                // Create new options
                foreach ($options as $optionData) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $optionData['option_text'],
                        'is_correct' => $optionData['is_correct'] ?? false,
                    ]);
                }
            }

            return $question->load('options');
        });
    }

    public function deleteQuestion($questionId)
    {
        $question = Question::findOrFail($questionId);
        $question->options()->delete();
        $question->delete();
    }

    public function reorderQuestions($lessonId, array $questions)
    {
        foreach ($questions as $item) {
            Question::where('id', $item['id'])
                ->where('lesson_id', $lessonId)
                ->update(['sequence' => $item['sequence']]);
        }
    }
}
