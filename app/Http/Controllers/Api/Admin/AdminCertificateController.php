<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCertificateConfigRequest;
use App\Http\Requests\UpdateCertificateConfigRequest;
use App\Http\Requests\StoreCertificateSignatureRequest;
use App\Http\Requests\UpdateCertificateSignatureRequest;
use App\Models\Course;
use App\Models\CourseCertificateConfig;
use App\Models\CertificateSignature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCertificateController extends Controller
{
    /**
     * Get certificate configuration for a course
     */
    public function getConfig($courseId)
    {
        $course = Course::findOrFail($courseId);
        $config = $course->certificateConfig;
        $signatures = $course->certificateSignatures()->orderBy('order')->get();

        return response()->json([
            'config' => $config ? [
                'id' => $config->id,
                'template_type' => $config->template_type,
                'logo' => $config->logo,
                'logo_url' => $config->logo_url,
                'background_image' => $config->background_image,
                'background_url' => $config->background_url,
                'primary_color' => $config->primary_color,
                'secondary_color' => $config->secondary_color,
                'certificate_text' => $config->certificate_text,
                'certificate_title' => $config->certificate_title,
                'show_qr_code' => $config->show_qr_code,
            ] : null,
            'signatures' => $signatures->map(function ($signature) {
                return [
                    'id' => $signature->id,
                    'signatory_name' => $signature->signatory_name,
                    'signatory_title' => $signature->signatory_title,
                    'signature_image' => $signature->signature_image,
                    'signature_url' => $signature->signature_url,
                    'order' => $signature->order,
                ];
            }),
        ]);
    }

    /**
     * Create or update certificate configuration
     */
    public function storeOrUpdateConfig(StoreCertificateConfigRequest $request, $courseId)
    {
        $course = Course::findOrFail($courseId);

        $data = [
            'template_type' => $request->input('template_type', 'classic'),
            'primary_color' => $request->input('primary_color', '#1e3a8a'),
            'secondary_color' => $request->input('secondary_color', '#d4af37'),
            'certificate_text' => $request->input('certificate_text'),
            'certificate_title' => $request->input('certificate_title', 'SERTIFIKAT'),
            'show_qr_code' => $request->input('show_qr_code', true),
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $logoPath;

            // Delete old logo if exists
            if ($course->certificateConfig && $course->certificateConfig->logo) {
                Storage::disk('public')->delete($course->certificateConfig->logo);
            }
        }

        // Handle background image upload
        if ($request->hasFile('background_image')) {
            $backgroundPath = $request->file('background_image')->store('backgrounds', 'public');
            $data['background_image'] = $backgroundPath;

            // Delete old background if exists
            if ($course->certificateConfig && $course->certificateConfig->background_image) {
                Storage::disk('public')->delete($course->certificateConfig->background_image);
            }
        }

        // Create or update config
        $config = CourseCertificateConfig::updateOrCreate(
            ['course_id' => $courseId],
            $data
        );

        return response()->json([
            'message' => 'Certificate configuration saved successfully.',
            'config' => [
                'id' => $config->id,
                'template_type' => $config->template_type,
                'logo' => $config->logo,
                'logo_url' => $config->logo_url,
                'background_image' => $config->background_image,
                'background_url' => $config->background_url,
                'primary_color' => $config->primary_color,
                'secondary_color' => $config->secondary_color,
                'certificate_text' => $config->certificate_text,
                'certificate_title' => $config->certificate_title,
                'show_qr_code' => $config->show_qr_code,
            ],
        ], 200);
    }

    /**
     * Update certificate configuration
     */
    public function updateConfig(UpdateCertificateConfigRequest $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $config = $course->certificateConfig;

        if (!$config) {
            return response()->json([
                'message' => 'Certificate configuration not found. Please create one first.',
            ], 404);
        }

        $data = [];

        // Update only provided fields
        if ($request->has('template_type')) {
            $data['template_type'] = $request->input('template_type');
        }
        if ($request->has('primary_color')) {
            $data['primary_color'] = $request->input('primary_color');
        }
        if ($request->has('secondary_color')) {
            $data['secondary_color'] = $request->input('secondary_color');
        }
        if ($request->has('certificate_text')) {
            $data['certificate_text'] = $request->input('certificate_text');
        }
        if ($request->has('certificate_title')) {
            $data['certificate_title'] = $request->input('certificate_title');
        }
        if ($request->has('show_qr_code')) {
            $data['show_qr_code'] = $request->input('show_qr_code');
        }

        // Handle logo removal
        if ($request->input('remove_logo') && $config->logo) {
            Storage::disk('public')->delete($config->logo);
            $data['logo'] = null;
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($config->logo) {
                Storage::disk('public')->delete($config->logo);
            }
            $logoPath = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $logoPath;
        }

        // Handle background removal
        if ($request->input('remove_background') && $config->background_image) {
            Storage::disk('public')->delete($config->background_image);
            $data['background_image'] = null;
        }

        // Handle background image upload
        if ($request->hasFile('background_image')) {
            // Delete old background
            if ($config->background_image) {
                Storage::disk('public')->delete($config->background_image);
            }
            $backgroundPath = $request->file('background_image')->store('backgrounds', 'public');
            $data['background_image'] = $backgroundPath;
        }

        $config->update($data);

        return response()->json([
            'message' => 'Certificate configuration updated successfully.',
            'config' => [
                'id' => $config->id,
                'template_type' => $config->template_type,
                'logo' => $config->logo,
                'logo_url' => $config->logo_url,
                'background_image' => $config->background_image,
                'background_url' => $config->background_url,
                'primary_color' => $config->primary_color,
                'secondary_color' => $config->secondary_color,
                'certificate_text' => $config->certificate_text,
                'certificate_title' => $config->certificate_title,
                'show_qr_code' => $config->show_qr_code,
            ],
        ]);
    }

    /**
     * Delete certificate configuration
     */
    public function deleteConfig($courseId)
    {
        $course = Course::findOrFail($courseId);
        $config = $course->certificateConfig;

        if (!$config) {
            return response()->json([
                'message' => 'Certificate configuration not found.',
            ], 404);
        }

        // Delete associated files
        if ($config->logo) {
            Storage::disk('public')->delete($config->logo);
        }
        if ($config->background_image) {
            Storage::disk('public')->delete($config->background_image);
        }

        $config->delete();

        return response()->json([
            'message' => 'Certificate configuration deleted successfully.',
        ]);
    }

    /**
     * List all signatures for a course
     */
    public function listSignatures($courseId)
    {
        $course = Course::findOrFail($courseId);
        $signatures = $course->certificateSignatures()->orderBy('order')->get();

        return response()->json([
            'signatures' => $signatures->map(function ($signature) {
                return [
                    'id' => $signature->id,
                    'signatory_name' => $signature->signatory_name,
                    'signatory_title' => $signature->signatory_title,
                    'signature_image' => $signature->signature_image,
                    'signature_url' => $signature->signature_url,
                    'order' => $signature->order,
                ];
            }),
        ]);
    }

    /**
     * Create a new signature
     */
    public function storeSignature(StoreCertificateSignatureRequest $request, $courseId)
    {
        $course = Course::findOrFail($courseId);

        // Get next order number if not provided
        $order = $request->input('order', $course->certificateSignatures()->max('order') + 1);

        $data = [
            'course_id' => $courseId,
            'signatory_name' => $request->input('signatory_name'),
            'signatory_title' => $request->input('signatory_title'),
            'order' => $order,
        ];

        // Handle signature image upload
        if ($request->hasFile('signature_image')) {
            $signaturePath = $request->file('signature_image')->store('signatures', 'public');
            $data['signature_image'] = $signaturePath;
        }

        $signature = CertificateSignature::create($data);

        return response()->json([
            'message' => 'Signature added successfully.',
            'signature' => [
                'id' => $signature->id,
                'signatory_name' => $signature->signatory_name,
                'signatory_title' => $signature->signatory_title,
                'signature_image' => $signature->signature_image,
                'signature_url' => $signature->signature_url,
                'order' => $signature->order,
            ],
        ], 201);
    }

    /**
     * Update a signature
     */
    public function updateSignature(UpdateCertificateSignatureRequest $request, $courseId, $signatureId)
    {
        $course = Course::findOrFail($courseId);
        $signature = CertificateSignature::where('course_id', $courseId)
            ->where('id', $signatureId)
            ->firstOrFail();

        $data = [];

        // Update only provided fields
        if ($request->has('signatory_name')) {
            $data['signatory_name'] = $request->input('signatory_name');
        }
        if ($request->has('signatory_title')) {
            $data['signatory_title'] = $request->input('signatory_title');
        }
        if ($request->has('order')) {
            $data['order'] = $request->input('order');
        }

        // Handle signature removal
        if ($request->input('remove_signature') && $signature->signature_image) {
            Storage::disk('public')->delete($signature->signature_image);
            $data['signature_image'] = null;
        }

        // Handle signature image upload
        if ($request->hasFile('signature_image')) {
            // Delete old signature image
            if ($signature->signature_image) {
                Storage::disk('public')->delete($signature->signature_image);
            }
            $signaturePath = $request->file('signature_image')->store('signatures', 'public');
            $data['signature_image'] = $signaturePath;
        }

        $signature->update($data);

        return response()->json([
            'message' => 'Signature updated successfully.',
            'signature' => [
                'id' => $signature->id,
                'signatory_name' => $signature->signatory_name,
                'signatory_title' => $signature->signatory_title,
                'signature_image' => $signature->signature_image,
                'signature_url' => $signature->signature_url,
                'order' => $signature->order,
            ],
        ]);
    }

    /**
     * Delete a signature
     */
    public function deleteSignature($courseId, $signatureId)
    {
        $course = Course::findOrFail($courseId);
        $signature = CertificateSignature::where('course_id', $courseId)
            ->where('id', $signatureId)
            ->firstOrFail();

        // Delete signature image file
        if ($signature->signature_image) {
            Storage::disk('public')->delete($signature->signature_image);
        }

        $signature->delete();

        return response()->json([
            'message' => 'Signature deleted successfully.',
        ]);
    }

    /**
     * Reorder signatures
     */
    public function reorderSignatures(Request $request, $courseId)
    {
        $request->validate([
            'signatures' => 'required|array',
            'signatures.*.id' => 'required|integer|exists:certificate_signatures,id',
            'signatures.*.order' => 'required|integer|min:1',
        ]);

        $course = Course::findOrFail($courseId);

        foreach ($request->input('signatures') as $item) {
            CertificateSignature::where('id', $item['id'])
                ->where('course_id', $courseId)
                ->update(['order' => $item['order']]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Signatures reordered successfully.',
        ]);
    }

    /**
     * Preview certificate template (for testing/demo)
     */
    public function previewCertificate($courseId)
    {
        $course = Course::with(['certificateConfig', 'certificateSignatures'])->findOrFail($courseId);

        if (!$course->certificateConfig) {
            return response()->json([
                'message' => 'Certificate configuration not found. Please setup certificate first.',
            ], 404);
        }

        // Create mock certificate data for preview
        $mockCertificate = (object) [
            'certificate_number' => 'CERT-PREVIEW-' . date('Ymd'),
            'issued_at' => now(),
        ];

        // Use authenticated admin user or create mock user
        $mockUser = (object) [
            'name' => 'John Doe (Sample Student)',
        ];

        // Generate QR code for preview (using SVG to avoid imagick issue)
        try {
            $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(200)
                ->generate(route('api.certificates.verify', $mockCertificate->certificate_number));
            $qrCode = base64_encode($qrCodeSvg);
        } catch (\Exception $e) {
            // Fallback: no QR code if generation fails
            $qrCode = null;
        }

        // Render certificate HTML
        $html = view('certificates.template', [
            'certificate' => $mockCertificate,
            'user' => $mockUser,
            'course' => $course,
            'config' => $course->certificateConfig,
            'signatures' => $course->certificateSignatures,
            'qrCode' => $qrCode,
        ])->render();

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');

        // Return PDF for preview
        return $pdf->stream('certificate-preview.pdf');
    }
}
