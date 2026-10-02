<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseDetailResource;
use App\Http\Resources\LessonResource;
use App\Http\Resources\QuestionResource;
use App\Services\PublicCourseService;
use App\Services\EnrollmentService;
use App\Services\LessonService;
use App\Services\QuizService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    protected $courseService;
    protected $enrollmentService;
    protected $lessonService;
    protected $quizService;

    public function __construct(
        PublicCourseService $courseService,
        EnrollmentService $enrollmentService,
        LessonService $lessonService,
        QuizService $quizService
    ) {
        $this->courseService = $courseService;
        $this->enrollmentService = $enrollmentService;
        $this->lessonService = $lessonService;
        $this->quizService = $quizService;
    }

    /**
     * 1. GET ALL COURSES (Catalog)
     * Endpoint: /api/courses
     */
    public function index(Request $request)
    {
        $courses = $this->courseService->getCatalog($request->q);

        // Inject is_enrolled status for authenticated user
        $user = $request->user();
        if ($user) {
            $isAdmin = isset($user->role) && $user->role === 'admin';
            foreach ($courses as $course) {
                // Admin always has access to all courses without enrollment
                $course->is_enrolled = $isAdmin || $this->enrollmentService->isEnrolled($course->id, $user);
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => CourseResource::collection($courses),
        ]);
    }

    /**
     * 2. GET SINGLE COURSE (Detail Page)
     * Endpoint: /api/courses/{slug}
     */
    public function show(Request $request, $slug)
    {
        // Route is public, so $request->user() always returns null (uses 'web' guard).
        // Resolve the sanctum guard directly to support optional Bearer token auth.
        $user = Auth::guard('sanctum')->user();
        $course = $this->courseService->getCourseDetail($slug, $user);

        return response()->json([
            'status' => 'success',
            'data' => new CourseDetailResource($course),
        ]);
    }

    public function showLesson(Request $request, $id)
    {
        $lesson = $this->lessonService->getLessonDetail($id, $request->user());

        return response()->json([
            'status' => 'success',
            'data' => new LessonResource($lesson),
        ]);
    }

    public function enroll(Request $request, $courseId)
    {
        // Validasi courseId harus integer positif
        if (!ctype_digit((string) $courseId) || (int) $courseId <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course ID tidak valid.',
            ], 400);
        }
        $courseId = (int) $courseId;

        $user = $request->user();

        // Admin tidak perlu enroll
        if (isset($user->role) && $user->role === 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Admin memiliki akses penuh tanpa perlu enrollment.',
            ], 400);
        }

        $result = $this->enrollmentService->enrollUser($user, $courseId);

        // is_enrolled hanya true jika status active atau completed
        $isEnrolled = in_array($result['status'] ?? null, ['active', 'completed']);

        return response()->json([
            'status' => 'success',
            'message' => $result['message'],
            'data' => [
                'enrollment_id' => $result['enrollment_id'] ?? null,
                'enrollment_status' => $result['status'] ?? null,
                'is_enrolled' => $isEnrolled
            ]
        ]);
    }

    /**
     * 3. GET MY COURSES (user enrolled)
     * Endpoint: /api/my-courses
     */
    public function myCourses(Request $request)
    {
        $user = $request->user();
        $courses = $this->courseService->getUserCourses($user);

        return response()->json([
            'status' => 'success',
            'data' => CourseResource::collection($courses),
        ]);
    }

    public function getQuestion(Request $request, $lessonId, $seq = 1)
    {
        $user = $request->user();
        $seq = max(1, (int) $seq);

        $result = $this->quizService->getQuizQuestion($lessonId, $seq, $user);

        return response()->json([
            'status' => 'success',
            'data' => new QuestionResource($result['question']),
            'total_questions' => $result['total'],
            'current_seq' => $seq,
        ]);
    }

    /**
     * Submit Quiz Answers
     * POST /api/lessons/{lessonId}/submit-quiz
     * Body: { "answers": { "1": 2, "2": 5, ... } } // seq => option_id
     */
    public function submitQuiz(Request $request, $lessonId)
    {
        $user = $request->user();

        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|integer', // option_id
        ]);

        $result = $this->quizService->submitQuizAnswers($lessonId, $user, $request->answers);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    /**
     * Complete Lesson (video, text, document)
     * POST /api/lessons/{lessonId}/complete
     */
    public function completeLesson(Request $request, $lessonId)
    {
        $user = $request->user();

        $result = $this->lessonService->completeLesson($lessonId, $user);

        return response()->json([
            'status' => 'success',
            'message' => $result['message'],
            'data' => [
                'is_completed' => true,
                'lesson_id' => (int) $lessonId,
                'certificate_issued' => $result['certificate_issued'] ?? false,
                'certificate_number' => $result['certificate_number'] ?? null,
            ]
        ]);
    }

    /**
     * Review Quiz Attempt with detailed answers
     * GET /api/quiz-attempts/{attemptId}/review
     */
    public function reviewQuizAttempt(Request $request, $attemptId)
    {
        $user = $request->user();

        $result = $this->quizService->getAttemptReview($attemptId, $user);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }
}
