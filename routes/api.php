<?php

use App\Http\Controllers\Api\Admin\AdminContentController;
use App\Http\Controllers\Api\Admin\AdminCourseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SocialAuthController;

Route::prefix('auth')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/google', [SocialAuthController::class, 'redirectToGoogle']);
    Route::get('/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
});

// Public
Route::get('/courses/{slug}', [CourseController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/lessons/{lessonId}', [CourseController::class, 'showLesson']);
    Route::get('/lessons/{lessonId}/questions/{seq}', [CourseController::class, 'getQuestion']);

    Route::post('/lessons/{lessonId}/submit-quiz', [CourseController::class, 'submitQuiz']);
    Route::post('/lessons/{lessonId}/complete', [CourseController::class, 'completeLesson']);

    // Quiz attempt review
    Route::get('/quiz-attempts/{attemptId}/review', [CourseController::class, 'reviewQuizAttempt']);

    Route::get('/courses', [CourseController::class, 'index']);

    // ENROLL COURSE (Gratis)
    Route::post('/courses/{courseId}/enroll', [CourseController::class, 'enroll']);

    // GET MY COURSES
    Route::get('/my-courses', [CourseController::class, 'myCourses']);

    // DASHBOARD
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/continue-learning', [DashboardController::class, 'continueLearning']);
});

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // --- CONTENT MANAGEMENT ROUTES ---
    // 1. Create Course
    Route::post('/courses', [AdminCourseController::class, 'storeCourse']);

    // 2. Create Section (Butuh ID Course)
    Route::post('/courses/{courseId}/sections', [AdminContentController::class, 'storeSection']);

    // 3. Create Lesson (Butuh ID Section)
    Route::post('/sections/{sectionId}/lessons', [AdminContentController::class, 'storeLesson']);
});
