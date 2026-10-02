<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificateSignatureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'signatory_name' => 'required|string|max:255',
            'signatory_title' => 'required|string|max:255',
            'signature_image' => 'required|image|mimes:png,jpg,jpeg|max:1024', // 1MB - REQUIRED for create
            'order' => 'nullable|integer|min:1',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'signatory_name.required' => 'Signatory name is required.',
            'signatory_title.required' => 'Signatory title is required.',
            'signature_image.image' => 'Signature must be an image file.',
            'signature_image.mimes' => 'Signature must be a PNG or JPG file.',
            'signature_image.max' => 'Signature size must not exceed 1MB.',
            'order.integer' => 'Order must be a number.',
            'order.min' => 'Order must be at least 1.',
        ];
    }
}
