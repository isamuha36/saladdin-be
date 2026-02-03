<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificateConfigRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    /**
     * Prepare the data for validation.
     * Convert string booleans from form-data to actual booleans.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('show_qr_code')) {
            $this->merge([
                'show_qr_code' => filter_var($this->show_qr_code, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'template_type' => 'nullable|string|in:classic,modern,elegant,minimal',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:512', // 512KB
            'background_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048', // 2MB
            'primary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'certificate_text' => 'nullable|string|max:1000',
            'certificate_title' => 'nullable|string|max:255',
            'show_qr_code' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'template_type.in' => 'Template type must be one of: classic, modern, elegant, minimal.',
            'logo.image' => 'Logo must be an image file.',
            'logo.mimes' => 'Logo must be a PNG or JPG file.',
            'logo.max' => 'Logo size must not exceed 512KB.',
            'background_image.image' => 'Background must be an image file.',
            'background_image.mimes' => 'Background must be a PNG or JPG file.',
            'background_image.max' => 'Background size must not exceed 2MB.',
            'primary_color.regex' => 'Primary color must be a valid hex color (e.g., #1e3a8a).',
            'secondary_color.regex' => 'Secondary color must be a valid hex color (e.g., #d4af37).',
        ];
    }
}
