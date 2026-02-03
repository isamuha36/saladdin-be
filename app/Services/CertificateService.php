<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use App\Repositories\LessonRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CertificateService
{
    protected $lessonRepository;
    protected $progressService;

    public function __construct(
        LessonRepository $lessonRepository,
        ProgressService $progressService
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->progressService = $progressService;
    }

    /**
     * Check if user can receive certificate for a course
     */
    public function canIssueCertificate(int $userId, int $courseId): bool
    {
        $progress = $this->progressService->calculateProgress($userId, $courseId);
        return $progress >= 100.00;
    }

    /**
     * Check if certificate already issued
     */
    public function hasCertificate(int $userId, int $courseId): bool
    {
        return Certificate::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();
    }

    /**
     * Issue certificate to user for completing a course
     */
    public function issueCertificate(int $userId, int $courseId): ?Certificate
    {
        // Check if already has certificate
        if ($this->hasCertificate($userId, $courseId)) {
            return Certificate::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->first();
        }

        // Check if eligible (100% completion)
        if (!$this->canIssueCertificate($userId, $courseId)) {
            return null;
        }

        // Generate certificate number
        $certificateNumber = $this->generateCertificateNumber();

        // Create certificate record
        $certificate = Certificate::create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'certificate_number' => $certificateNumber,
            'issued_at' => now(),
        ]);

        // Generate PDF immediately
        $this->generateCertificatePdf($certificate);

        return $certificate;
    }

    /**
     * Generate certificate PDF
     */
    public function generateCertificatePdf(Certificate $certificate): void
    {
        $certificate->load(['user', 'course.certificateConfig', 'course.certificateSignatures']);

        $user = $certificate->user;
        $course = $certificate->course;
        $config = $course->certificateConfig;
        $signatures = $course->certificateSignatures()->orderBy('order')->get();

        // Generate QR code if enabled
        $qrCode = null;
        if ($config && $config->show_qr_code) {
            $verificationUrl = route('certificates.verify', $certificate->certificate_number);
            $qrCode = base64_encode(QrCode::format('png')->size(200)->generate($verificationUrl));
        }

        // Generate PDF
        $pdf = Pdf::loadView('certificates.template', [
            'certificate' => $certificate,
            'user' => $user,
            'course' => $course,
            'config' => $config,
            'signatures' => $signatures,
            'qrCode' => $qrCode,
        ]);

        $pdf->setPaper('a4', 'landscape');

        // Save PDF
        $filename = "certificates/{$certificate->certificate_number}.pdf";
        Storage::put($filename, $pdf->output());

        // Update certificate with PDF path
        $certificate->update([
            'pdf_path' => $filename,
        ]);
    }

    /**
     * Generate unique certificate number
     * Format: CERT-YYYY-XXXXX (e.g., CERT-2026-00001)
     */
    protected function generateCertificateNumber(): string
    {
        $year = date('Y');
        $prefix = "CERT-{$year}-";

        // Get last certificate number for this year
        $lastCert = Certificate::where('certificate_number', 'LIKE', "{$prefix}%")
            ->orderBy('certificate_number', 'desc')
            ->first();

        if ($lastCert) {
            // Extract number and increment
            $lastNumber = (int) substr($lastCert->certificate_number, -5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get all certificates for a user
     */
    public function getUserCertificates(int $userId)
    {
        return Certificate::where('user_id', $userId)
            ->with(['course.certificateConfig', 'course.certificateSignatures'])
            ->orderBy('issued_at', 'desc')
            ->get();
    }

    /**
     * Verify certificate by certificate number
     */
    public function verifyCertificate(string $certificateNumber): ?array
    {
        $certificate = Certificate::where('certificate_number', $certificateNumber)
            ->with(['user', 'course'])
            ->first();

        if (!$certificate) {
            return null;
        }

        return [
            'valid' => true,
            'certificate_number' => $certificate->certificate_number,
            'student_name' => $certificate->user->name,
            'course_title' => $certificate->course->title,
            'issued_at' => $certificate->issued_at->format('d F Y'),
            'instructor' => $certificate->course->instructor_name,
        ];
    }

    /**
     * Get certificate by ID (with authorization check)
     */
    public function getCertificateById(int $certificateId, int $userId): ?Certificate
    {
        return Certificate::where('id', $certificateId)
            ->where('user_id', $userId)
            ->with(['course.certificateConfig', 'course.certificateSignatures'])
            ->first();
    }
}
