<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseDetailResource;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * 1. GET ALL COURSES (Catalog)
     * Endpoint: /api/courses
     */
    public function index(Request $request)
    {
        // Ambil hanya yang statusnya 'published'
        $query = Course::where('status', 'published');

        // Fitur Pencarian Sederhana (?q=sejarah)
        if ($request->has('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        // Urutkan dari yang terbaru
        $courses = $query->latest()->get();

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
        // Cari kursus berdasarkan Slug
        // Gunakan 'with' (Eager Loading) untuk mengambil Section & Lesson sekaligus
        // Ini MENCEGAH query berulang-ulang (N+1 Problem) -> PENTING UNTUK PERFORMA
        $course = Course::with(['sections.lessons.questions.options'])
            ->where('slug', $slug)
            ->where('status', 'published') // Pastikan cuma yang published
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => new CourseDetailResource($course),
        ]);
    }
}
