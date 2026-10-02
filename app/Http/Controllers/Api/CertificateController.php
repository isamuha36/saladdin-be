<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    protected $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Get all user's certificates
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $certificates = $this->certificateService->getUserCertificates($user->id);

        return response()->json([
            'certificates' => $certificates->map(function ($cert) {
                return [
                    'id' => $cert->id,
                    'certificate_number' => $cert->certificate_number,
                    'course_title' => $cert->course->title,
                    'course_slug' => $cert->course->slug,
                    'instructor' => $cert->course->instructor_name,
                    'issued_at' => $cert->issued_at->format('d F Y'),
                    'verification_url' => route('api.certificates.verify', $cert->certificate_number),
                ];
            }),
        ]);
    }

    /**
     * Get certificate detail
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $certificate = $this->certificateService->getCertificateById($id, $user->id);

        if (!$certificate) {
            return response()->json(['message' => 'Certificate not found.'], 404);
        }

        return response()->json([
            'id' => $certificate->id,
            'certificate_number' => $certificate->certificate_number,
            'student_name' => $user->name,
            'course' => [
                'id' => $certificate->course->id,
                'title' => $certificate->course->title,
                'instructor' => $certificate->course->instructor_name,
            ],
            'issued_at' => $certificate->issued_at->format('d F Y'),
            'config' => $certificate->course->certificateConfig,
            'signatures' => $certificate->course->certificateSignatures->map(function ($sig) {
                return [
                    'name' => $sig->signatory_name,
                    'title' => $sig->signatory_title,
                    'signature_url' => $sig->signature_url,
                    'order' => $sig->order,
                ];
            }),
        ]);
    }

    /**
     * Verify certificate (public endpoint)
     */
    public function verify($certificateNumber)
    {
        $result = $this->certificateService->verifyCertificate($certificateNumber);

        if (!$result) {
            return response()->json([
                'valid' => false,
                'message' => 'Certificate not found or invalid.',
            ], 404);
        }

        return response()->json($result);
    }

    /**
     * Download certificate as PDF
     */
    public function download(Request $request, $id)
    {
        $user = $request->user();
        $certificate = $this->certificateService->getCertificateById($id, $user->id);

        if (!$certificate) {
            return response()->json(['message' => 'Certificate not found.'], 404);
        }

        // Check if PDF exists, if not generate it
        if (!$certificate->pdf_path || !Storage::exists($certificate->pdf_path)) {
            $this->certificateService->generateCertificatePdf($certificate);
            $certificate->refresh();
        }

        // Download the PDF
        return Storage::download($certificate->pdf_path, "{$certificate->certificate_number}.pdf");
    }
}
