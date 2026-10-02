<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CertificateWebController extends Controller
{
    protected $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $certificates = $this->certificateService->getUserCertificates($user->id)->map(function ($cert) {
            return [
                'id' => $cert->id,
                'certificate_number' => $cert->certificate_number,
                'course_id' => $cert->course->id,
                'course_title' => $cert->course->title,
                'course_slug' => $cert->course->slug,
                'instructor' => $cert->course->instructor_name,
                'issued_at' => $cert->issued_at ? $cert->issued_at->format('d F Y') : null,
                'has_pdf' => (bool) $cert->pdf_path,
            ];
        });

        return Inertia::render('Certificates/Index', [
            'certificates' => $certificates,
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = Auth::user();
        $certificate = $this->certificateService->getCertificateById($id, $user->id);

        if (!$certificate) {
            abort(404, 'Sertifikat tidak ditemukan.');
        }

        return Inertia::render('Certificates/Show', [
            'certificate' => [
                'id' => $certificate->id,
                'certificate_number' => $certificate->certificate_number,
                'student_name' => $user->name,
                'course' => [
                    'id' => $certificate->course->id,
                    'title' => $certificate->course->title,
                    'slug' => $certificate->course->slug,
                    'instructor' => $certificate->course->instructor_name,
                ],
                'issued_at' => $certificate->issued_at ? $certificate->issued_at->format('d F Y') : null,
                'config' => $certificate->course->certificateConfig,
                'signatures' => $certificate->course->certificateSignatures->map(function ($sig) {
                    return [
                        'name' => $sig->signatory_name,
                        'title' => $sig->signatory_title,
                        'signature_url' => $sig->signature_url,
                        'order' => $sig->order,
                    ];
                }),
                'has_pdf' => (bool) $certificate->pdf_path,
            ],
        ]);
    }

    public function verify($certificateNumber)
    {
        $result = $this->certificateService->verifyCertificate($certificateNumber);

        return Inertia::render('Certificates/Verify', [
            'verification' => $result,
            'certificateNumber' => $certificateNumber,
        ]);
    }

    public function download(Request $request, $id)
    {
        $user = Auth::user();
        $certificate = $this->certificateService->getCertificateById($id, $user->id);

        if (!$certificate) {
            abort(404, 'Sertifikat tidak ditemukan.');
        }

        // Generate PDF if not yet generated
        if (!$certificate->pdf_path || !Storage::exists($certificate->pdf_path)) {
            $this->certificateService->generateCertificatePdf($certificate);
            $certificate->refresh();
        }

        if (!Storage::exists($certificate->pdf_path)) {
            return back()->with('error', 'Gagal memproses file PDF sertifikat.');
        }

        return Storage::download($certificate->pdf_path, "{$certificate->certificate_number}.pdf");
    }
}
