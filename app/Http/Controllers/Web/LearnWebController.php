<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\QuizAttempt;
use App\Services\EnrollmentService;
use App\Services\LessonService;
use App\Services\QuizService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LearnWebController extends Controller
{
    protected $enrollmentService;
    protected $lessonService;
    protected $quizService;

    public function __construct(
        EnrollmentService $enrollmentService,
        LessonService $lessonService,
        QuizService $quizService
    ) {
        $this->enrollmentService = $enrollmentService;
        $this->lessonService = $lessonService;
        $this->quizService = $quizService;
    }

    public function show(Request $request, $courseSlug, $lessonId)
    {
        $user = Auth::user();

        $course = Course::where('slug', $courseSlug)
            ->with(['sections.lessons'])
            ->firstOrFail();

        // Check enrollment
        $isEnrolled = ($user->role === 'admin') || $this->enrollmentService->isEnrolled($course->id, $user);
        if (!$isEnrolled) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'Silakan mendaftar kursus ini terlebih dahulu untuk mengakses materi.');
        }

        // Flatten all lessons in order
        $allLessons = collect();
        $sortedSections = $course->sections->sortBy('sort_order')->values();
        foreach ($sortedSections as $section) {
            $sortedLessons = $section->lessons->sortBy('sort_order')->values();
            foreach ($sortedLessons as $l) {
                $allLessons->push($l);
            }
        }

        // Current lesson
        $currentLesson = $allLessons->firstWhere('id', (int) $lessonId);
        if (!$currentLesson) {
            // Default to first lesson if not found
            $currentLesson = $allLessons->first();
            if (!$currentLesson) {
                abort(404, 'Materi pembelajaran belum tersedia.');
            }
            return redirect()->route('learn.lesson', [$course->slug, $currentLesson->id]);
        }

        // Find prev and next lessons
        $currentIndex = $allLessons->search(fn($l) => $l->id === $currentLesson->id);
        $prevLesson = $currentIndex > 0 ? $allLessons->get($currentIndex - 1) : null;
        $nextLesson = $currentIndex < ($allLessons->count() - 1) ? $allLessons->get($currentIndex + 1) : null;

        // Completed lesson IDs
        $completedLessonIds = LessonCompletion::where('user_id', $user->id)
            ->whereIn('lesson_id', $allLessons->pluck('id'))
            ->pluck('lesson_id')
            ->toArray();

        $isCurrentCompleted = in_array($currentLesson->id, $completedLessonIds);

        // Prepare lesson data
        $contentUrl = $currentLesson->content_url;
        // Transform YouTube URL to embed format if needed
        if ($contentUrl && (str_contains($contentUrl, 'youtu.be') || str_contains($contentUrl, 'youtube.com'))) {
            if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $contentUrl, $matches)) {
                $contentUrl = 'https://www.youtube-nocookie.com/embed/' . $matches[1];
            }
        }

        // If quiz, load questions and sanitize options
        $quizData = null;
        $latestAttempt = null;
        if ($currentLesson->type === 'quiz') {
            $currentLesson->load(['questions.options']);
            
            $quizData = [
                'passing_grade' => $currentLesson->passing_grade ?? 70,
                'duration_minutes' => $currentLesson->duration_minutes ?? 15,
                'questions' => $currentLesson->questions->sortBy('sequence')->values()->map(function ($q, $index) {
                    return [
                        'id' => $q->id,
                        'sequence' => $index + 1,
                        'question_text' => $q->question_text,
                        'points' => $q->points,
                        'options' => $q->options->map(function ($opt) {
                            return [
                                'id' => $opt->id,
                                'option_text' => $opt->option_text,
                                // DO NOT expose is_correct here
                            ];
                        }),
                    ];
                }),
            ];

            // Latest attempt by this user
            $latestAttempt = QuizAttempt::where('lesson_id', $currentLesson->id)
                ->where('user_id', $user->id)
                ->latest()
                ->first();
        }

        // Structure sections for sidebar
        $syllabus = $sortedSections->map(function ($sec) use ($completedLessonIds, $currentLesson) {
            return [
                'id' => $sec->id,
                'title' => $sec->title,
                'lessons' => $sec->lessons->sortBy('sort_order')->values()->map(function ($les) use ($completedLessonIds, $currentLesson) {
                    return [
                        'id' => $les->id,
                        'title' => $les->title,
                        'type' => $les->type,
                        'duration_minutes' => $les->duration_minutes,
                        'is_current' => $les->id === $currentLesson->id,
                        'is_completed' => in_array($les->id, $completedLessonIds),
                    ];
                }),
            ];
        });

        $progressPercentage = $allLessons->count() > 0 
            ? round((count($completedLessonIds) / $allLessons->count()) * 100) 
            : 0;

        return Inertia::render('Learn/Show', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'thumbnail' => $course->thumbnail,
                'instructor_name' => $course->instructor_name,
                'progress' => $progressPercentage,
                'total_lessons' => $allLessons->count(),
                'completed_count' => count($completedLessonIds),
            ],
            'syllabus' => $syllabus,
            'lesson' => [
                'id' => $currentLesson->id,
                'title' => $currentLesson->title,
                'type' => $currentLesson->type,
                'content_text' => $currentLesson->content_text,
                'content_url' => $contentUrl,
                'content_path' => $currentLesson->content_path,
                'duration_minutes' => $currentLesson->duration_minutes,
                'passing_grade' => $currentLesson->passing_grade,
                'is_completed' => $isCurrentCompleted,
            ],
            'quiz' => $quizData,
            'latestAttempt' => $latestAttempt ? [
                'id' => $latestAttempt->id,
                'score' => (float) $latestAttempt->score,
                'passed' => (bool) $latestAttempt->passed,
                'correct_answers' => $latestAttempt->correct_answers,
                'total_questions' => $latestAttempt->total_questions,
                'created_at' => $latestAttempt->created_at->format('d M Y H:i'),
            ] : null,
            'prevLesson' => $prevLesson ? [
                'id' => $prevLesson->id,
                'title' => $prevLesson->title,
            ] : null,
            'nextLesson' => $nextLesson ? [
                'id' => $nextLesson->id,
                'title' => $nextLesson->title,
            ] : null,
        ]);
    }

    public function complete(Request $request, $lessonId)
    {
        $user = Auth::user();
        $result = $this->lessonService->completeLesson($lessonId, $user);

        $lesson = Lesson::with('section.course')->findOrFail($lessonId);
        $course = $lesson->section->course;

        // Find next lesson
        $allLessons = $course->sections->sortBy('sort_order')
            ->flatMap(fn($s) => $s->lessons->sortBy('sort_order'))
            ->values();

        $currentIndex = $allLessons->search(fn($l) => $l->id === (int) $lessonId);
        $nextLesson = ($currentIndex !== false && $currentIndex < $allLessons->count() - 1)
            ? $allLessons->get($currentIndex + 1)
            : null;

        if ($result['certificate_issued'] ?? false) {
            return redirect()->route('certificates.index')
                ->with('success', 'Maa syaa Allah! Anda telah menyelesaikan seluruh kursus dan sertifikat telah diterbitkan!');
        }

        if ($nextLesson) {
            return redirect()->route('learn.lesson', [$course->slug, $nextLesson->id])
                ->with('success', 'Materi selesai! Melanjutkan ke materi berikutnya.');
        }

        return back()->with('success', 'Materi berhasil diselesaikan!');
    }

    public function submitQuiz(Request $request, $lessonId)
    {
        $user = Auth::user();

        $request->validate([
            'answers' => ['required', 'array'],
        ]);

        $result = $this->quizService->submitQuizAnswers($lessonId, $user, $request->answers);

        if ($result['passed']) {
            if ($result['certificate_issued'] ?? false) {
                return back()->with('success', "Maa syaa Allah! Nilai Anda {$result['score']} (Lulus). Selamat, sertifikat Anda telah diterbitkan!");
            }
            return back()->with('success', "Alhamdulillah! Anda lulus kuis dengan nilai {$result['score']}!");
        }

        return back()->with('error', "Nilai Anda {$result['score']} (KKM: {$result['passing_grade']}). Jangan berkecil hati, silakan coba lagi!");
    }
}
