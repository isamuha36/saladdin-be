<?php

use App\Http\Controllers\Api\Admin\AdminContentController;
use App\Http\Controllers\Api\Admin\AdminCourseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\SocialAuthController;

Route::prefix('auth')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/google', [SocialAuthController::class, 'redirectToGoogle']);
    Route::get('/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::get('/lessons/{lessonId}', [CourseController::class, 'showLesson']);
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

    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{slug}', [CourseController::class, 'show']);
});
