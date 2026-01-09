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

    public function index()
    {
        $courses = $this->courseService->getAllCourses();
        return response()->json(['status' => 'success', 'data' => $courses]);
    }

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
