<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseDetailResource;
use App\Http\Resources\LessonResource;
use App\Services\PublicCourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(PublicCourseService $courseService)
    {
        $this->courseService = $courseService;
    }
    
    /**
     * 1. GET ALL COURSES (Catalog)
     * Endpoint: /api/courses
     */
    public function index(Request $request)
    {
        // Panggil Service
        $courses = $this->courseService->getCatalog($request->q);

        return response()->json([
            'status' => 'success',
            'data' => CourseResource::collection($courses),
        ]);
    }

    /**
     * 2. GET SINGLE COURSE (Detail Page)
     * Endpoint: /api/courses/{slug}
     */
    public function show($slug)
    {
        // Panggil Service
        $course = $this->courseService->getCourseDetail($slug);

        return response()->json([
            'status' => 'success',
            'data' => $course,
        ]);
    }

    public function showLesson($id)
    {
        // 1. Ambil Data
        $lesson = $this->courseService->getLessonDetail($id);

        // 2. Return pakai Resource (Agar URL Video & Quiz aman)
        return response()->json([
            'status' => 'success',
            'data' => new LessonResource($lesson),
        ]);
    }
}
