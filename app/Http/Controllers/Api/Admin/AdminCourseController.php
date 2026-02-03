<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminCourseService;
use Illuminate\Http\Request;

class AdminCourseController extends Controller
{
    protected $courseService;

    // Inject Service ke Controller
    public function __construct(AdminCourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Get all courses (with filtering, pagination)
     */
    public function index(Request $request)
    {
        $filters = [
            'status' => $request->input('status'),
            'search' => $request->input('search'),
            'instructor' => $request->input('instructor'),
        ];

        $perPage = $request->input('per_page', 15);

        $courses = $this->courseService->getAllCourses($filters, $perPage);

        return response()->json([
            'status' => 'success',
            'data' => $courses
        ]);
    }

    /**
     * Get single course detail with sections and lessons
     */
    public function show($id)
    {
        $course = $this->courseService->getCourseDetail($id);

        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    /**
     * Create new course
     */
    public function store(Request $request)
    {
        // Controller cuma ngurus Validasi Request
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'instructor_name' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        // Lempar data ke Service untuk diproses
        $course = $this->courseService->createCourse(
            $request->except('thumbnail'), // Data teks
            $request->file('thumbnail')    // File gambar
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Course created successfully',
            'data' => $course
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'string|max:255',
            'status' => 'in:draft,published'
        ]);

        $course = $this->courseService->updateCourse(
            $id,
            $request->except('thumbnail'),
            $request->file('thumbnail')
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Course updated successfully',
            'data' => $course
        ]);
    }

    public function destroy($id)
    {
        $this->courseService->deleteCourse($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Course deleted successfully'
        ]);
    }
}
