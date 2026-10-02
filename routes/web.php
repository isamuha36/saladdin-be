<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CertificateWebController;
use App\Http\Controllers\Web\CourseWebController;
use App\Http\Controllers\Web\DashboardWebController;
use App\Http\Controllers\Web\LearnWebController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Inertia Fullstack)
|--------------------------------------------------------------------------
*/

// Public Catalog & Course Pages
Route::get('/', [CourseWebController::class, 'index'])->name('home');
Route::get('/courses', [CourseWebController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseWebController::class, 'show'])->name('courses.show');

// Public Certificate Verification (QR Code destination)
Route::get('/verify-certificate/{certificateNumber}', [CertificateWebController::class, 'verify'])->name('certificates.verify');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Demo 1-Click Login (useful for immediate preview/testing)
Route::get('/demo-login/{type?}', [AuthController::class, 'quickLogin'])->name('demo.login');

// Protected Student / User Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardWebController::class, 'index'])->name('dashboard');

    // Course Enrollment
    Route::post('/courses/{courseId}/enroll', [CourseWebController::class, 'enroll'])->name('courses.enroll');

    // Learning Player & Interactive Classroom
    Route::get('/learn/{courseSlug}/lesson/{lessonId}', [LearnWebController::class, 'show'])->name('learn.lesson');
    Route::post('/learn/lessons/{lessonId}/complete', [LearnWebController::class, 'complete'])->name('learn.complete');
    Route::post('/learn/lessons/{lessonId}/quiz', [LearnWebController::class, 'submitQuiz'])->name('learn.quiz.submit');

    // Certificates
    Route::get('/my-certificates', [CertificateWebController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{id}', [CertificateWebController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{id}/download', [CertificateWebController::class, 'download'])->name('certificates.download');
});
