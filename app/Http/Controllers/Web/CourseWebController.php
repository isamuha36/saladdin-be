<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\PublicCourseService;
use App\Services\EnrollmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CourseWebController extends Controller
{
    protected $courseService;
    protected $enrollmentService;

    public function __construct(
        PublicCourseService $courseService,
        EnrollmentService $enrollmentService
    ) {
        $this->courseService = $courseService;
        $this->enrollmentService = $enrollmentService;
    }

    public function index(Request $request)
    {
        $search = $request->query('q', '');
        $user = Auth::user();

        $coursesQuery = Course::query()
            ->where('status', 'published')
            ->withCount(['sections', 'enrollments'])
            ->with(['sections.lessons']);

        if ($search) {
            $coursesQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('instructor_name', 'like', "%{$search}%");
            });
        }

        $courses = $coursesQuery->latest()->get()->map(function ($course) use ($user) {
            $lessonsCount = $course->sections->flatMap->lessons->count();
            $isEnrolled = false;
            $progress = 0;

            if ($user) {
                $isEnrolled = ($user->role === 'admin') || $this->enrollmentService->isEnrolled($course->id, $user);
                if ($isEnrolled) {
                    $completedCount = \App\Models\LessonCompletion::where('user_id', $user->id)
                        ->whereIn('lesson_id', $course->sections->flatMap->lessons->pluck('id'))
                        ->count();
                    $progress = $lessonsCount > 0 ? round(($completedCount / $lessonsCount) * 100) : 0;
                }
            }

            return [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'description' => $course->description,
                'thumbnail' => $course->thumbnail,
                'instructor_name' => $course->instructor_name,
                'price' => (float) $course->price,
                'sections_count' => $course->sections_count,
                'lessons_count' => $lessonsCount,
                'enrollments_count' => $course->enrollments_count,
                'is_enrolled' => $isEnrolled,
                'progress' => $progress,
            ];
        });

        return Inertia::render('Courses/Index', [
            'courses' => $courses,
            'filters' => [
                'q' => $search,
            ],
        ]);
    }

    public function show(Request $request, $slug)
    {
        $user = Auth::user();
        $course = $this->courseService->getCourseDetail($slug, $user);

        if (!$course) {
            abort(404, 'Kursus tidak ditemukan');
        }

        $sections = $course->sections->sortBy('sort_order')->values()->map(function ($section) use ($course) {
            return [
                'id' => $section->id,
                'title' => $section->title,
                'sort_order' => $section->sort_order,
                'lessons' => $section->lessons->sortBy('sort_order')->values()->map(function ($lesson) use ($course) {
                    return [
                        'id' => $lesson->id,
                        'title' => $lesson->title,
                        'slug' => $lesson->slug,
                        'type' => $lesson->type,
                        'duration_minutes' => $lesson->duration_minutes,
                        'is_preview' => (bool) $lesson->is_preview,
                        'is_completed' => in_array($lesson->id, $course->completed_lesson_ids ?? []),
                    ];
                }),
            ];
        });

        // Find first lesson for "Mulai Belajar"
        $firstLesson = $course->sections->sortBy('sort_order')->first()?->lessons->sortBy('sort_order')->first();

        return Inertia::render('Courses/Show', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'description' => $course->description,
                'thumbnail' => $course->thumbnail,
                'instructor_name' => $course->instructor_name,
                'price' => (float) $course->price,
                'is_enrolled' => (bool) $course->is_enrolled,
                'progress' => $course->progress ? round($course->progress['percentage'] ?? 0) : 0,
                'sections' => $sections,
                'total_lessons' => $sections->flatMap(fn($s) => $s['lessons'])->count(),
                'first_lesson_id' => $firstLesson?->id,
            ],
        ]);
    }

    public function enroll(Request $request, $courseId)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mendaftar kursus.');
        }

        if ($user->role === 'admin') {
            return back()->with('info', 'Admin memiliki akses penuh ke seluruh materi kursus.');
        }

        $result = $this->enrollmentService->enrollUser($user, $courseId);

        return back()->with('success', $result['message']);
    }
}
