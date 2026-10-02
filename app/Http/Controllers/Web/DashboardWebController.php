<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonCompletion;
use App\Models\User;
use App\Services\DashboardService;
use App\Services\PublicCourseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardWebController extends Controller
{
    protected $dashboardService;
    protected $courseService;

    public function __construct(
        DashboardService $dashboardService,
        PublicCourseService $courseService
    ) {
        $this->dashboardService = $dashboardService;
        $this->courseService = $courseService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Admin overview metrics if admin
        $adminStats = null;
        if ($user->role === 'admin') {
            $adminStats = [
                'total_courses' => Course::count(),
                'total_students' => User::where('role', 'student')->count(),
                'total_enrollments' => Enrollment::count(),
            ];
        }

        $stats = $this->dashboardService->getDashboardStats($user);
        $continueLearning = $this->dashboardService->getLastAccessedLesson($user);

        // If user hasn't completed any lessons yet, but is enrolled in courses, point to first lesson of first enrolled course
        if (!$continueLearning) {
            $firstEnrollment = Enrollment::where('user_id', $user->id)
                ->whereIn('status', ['active', 'completed'])
                ->with(['course.sections.lessons'])
                ->first();

            if ($firstEnrollment && $firstEnrollment->course) {
                $firstCourse = $firstEnrollment->course;
                $firstSection = $firstCourse->sections->sortBy('sort_order')->first();
                $firstLesson = $firstSection?->lessons->sortBy('sort_order')->first();

                if ($firstLesson) {
                    $continueLearning = [
                        'course' => [
                            'id' => $firstCourse->id,
                            'title' => $firstCourse->title,
                            'slug' => $firstCourse->slug,
                            'thumbnail' => $firstCourse->thumbnail,
                        ],
                        'next_lesson' => [
                            'id' => $firstLesson->id,
                            'title' => $firstLesson->title,
                            'slug' => $firstLesson->slug,
                            'type' => $firstLesson->type,
                        ],
                        'section' => [
                            'id' => $firstSection->id,
                            'title' => $firstSection->title,
                        ],
                    ];
                }
            }
        }

        // Get enrolled courses with progress
        $enrolledCourseIds = ($user->role === 'admin')
            ? Course::pluck('id')
            : Enrollment::where('user_id', $user->id)
                ->whereIn('status', ['active', 'completed'])
                ->pluck('course_id');

        $enrolledCourses = Course::whereIn('id', $enrolledCourseIds)
            ->with(['sections.lessons'])
            ->get()
            ->map(function ($course) use ($user) {
                $allLessons = $course->sections->flatMap->lessons;
                $totalLessons = $allLessons->count();
                $completedCount = LessonCompletion::where('user_id', $user->id)
                    ->whereIn('lesson_id', $allLessons->pluck('id'))
                    ->count();

                $percentage = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;
                $firstLesson = $allLessons->first();

                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'thumbnail' => $course->thumbnail,
                    'instructor_name' => $course->instructor_name,
                    'total_lessons' => $totalLessons,
                    'completed_lessons' => $completedCount,
                    'progress' => $percentage,
                    'first_lesson_id' => $firstLesson?->id,
                ];
            });

        return Inertia::render('Dashboard/Index', [
            'stats' => $stats,
            'adminStats' => $adminStats,
            'continueLearning' => $continueLearning,
            'enrolledCourses' => $enrolledCourses,
        ]);
    }
}
