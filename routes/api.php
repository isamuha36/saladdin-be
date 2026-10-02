<?php

use App\Http\Controllers\Api\Admin\AdminContentController;
use App\Http\Controllers\Api\Admin\AdminCourseController;
use App\Http\Controllers\Api\Admin\AdminCertificateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SocialAuthController;
use App\Http\Controllers\Api\CertificateController;

Route::prefix('auth')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/google', [SocialAuthController::class, 'redirectToGoogle']);
    Route::get('/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
});

// Public
Route::get('/courses/{slug}', [CourseController::class, 'show']);

// Public certificate verification
Route::get('/certificates/verify/{certificateNumber}', [CertificateController::class, 'verify'])->name('api.certificates.verify');

// Public certificate preview (for demo/testing)
Route::get('/certificate-preview/{courseId}', [AdminCertificateController::class, 'previewCertificate'])->name('api.certificate.preview');

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

    // CERTIFICATES
    Route::get('/my-certificates', [CertificateController::class, 'index']);
    Route::get('/certificates/{id}', [CertificateController::class, 'show']);
    Route::get('/certificates/{id}/download', [CertificateController::class, 'download'])->name('api.certificates.download');
});

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // ============ COURSE CRUD ============
    Route::get('/courses', [AdminCourseController::class, 'index']);                    // List all courses
    Route::get('/courses/{id}', [AdminCourseController::class, 'show']);                // Get course detail
    Route::post('/courses', [AdminCourseController::class, 'store']);                   // Create course
    Route::put('/courses/{id}', [AdminCourseController::class, 'update']);              // Update course
    Route::delete('/courses/{id}', [AdminCourseController::class, 'destroy']);          // Delete course

    // ============ SECTION CRUD ============
    Route::get('/courses/{courseId}/sections', [AdminContentController::class, 'indexSections']);           // List sections
    Route::get('/sections/{sectionId}', [AdminContentController::class, 'showSection']);                    // Get section detail
    Route::post('/courses/{courseId}/sections', [AdminContentController::class, 'storeSection']);           // Create section
    Route::put('/sections/{sectionId}', [AdminContentController::class, 'updateSection']);                  // Update section
    Route::delete('/sections/{sectionId}', [AdminContentController::class, 'destroySection']);              // Delete section

    // ============ LESSON CRUD ============
    Route::get('/sections/{sectionId}/lessons', [AdminContentController::class, 'indexLessons']);           // List lessons
    Route::get('/lessons/{lessonId}', [AdminContentController::class, 'showLesson']);                       // Get lesson detail
    Route::post('/sections/{sectionId}/lessons', [AdminContentController::class, 'storeLesson']);           // Create lesson
    Route::put('/lessons/{lessonId}', [AdminContentController::class, 'updateLesson']);                     // Update lesson
    Route::delete('/lessons/{lessonId}', [AdminContentController::class, 'destroyLesson']);                 // Delete lesson

    // ============ QUIZ BUILDER (Save 1-1) ============
    Route::get('/lessons/{lessonId}/quiz', [AdminContentController::class, 'getQuizBuilder']);              // Get quiz with questions
    Route::post('/lessons/{lessonId}/questions', [AdminContentController::class, 'storeQuestion']);         // Add question (1-1)
    Route::put('/questions/{questionId}', [AdminContentController::class, 'updateQuestion']);               // Update question
    Route::delete('/questions/{questionId}', [AdminContentController::class, 'destroyQuestion']);           // Delete question
    Route::post('/lessons/{lessonId}/questions/reorder', [AdminContentController::class, 'reorderQuestions']); // Reorder questions

    // --- CERTIFICATE MANAGEMENT ROUTES ---
    // Get certificate config & signatures
    Route::get('/courses/{courseId}/certificate', [AdminCertificateController::class, 'getConfig']);

    // Preview certificate (for testing)
    Route::get('/courses/{courseId}/certificate/preview', [AdminCertificateController::class, 'previewCertificate']);

    // Certificate Configuration
    Route::post('/courses/{courseId}/certificate-config', [AdminCertificateController::class, 'storeOrUpdateConfig']);
    Route::put('/courses/{courseId}/certificate-config', [AdminCertificateController::class, 'updateConfig']);
    Route::delete('/courses/{courseId}/certificate-config', [AdminCertificateController::class, 'deleteConfig']);

    // Certificate Signatures
    Route::get('/courses/{courseId}/signatures', [AdminCertificateController::class, 'listSignatures']);
    Route::post('/courses/{courseId}/signatures', [AdminCertificateController::class, 'storeSignature']);
    Route::put('/courses/{courseId}/signatures/{signatureId}', [AdminCertificateController::class, 'updateSignature']);
    Route::delete('/courses/{courseId}/signatures/{signatureId}', [AdminCertificateController::class, 'deleteSignature']);
    Route::post('/courses/{courseId}/signatures/reorder', [AdminCertificateController::class, 'reorderSignatures']);
});
