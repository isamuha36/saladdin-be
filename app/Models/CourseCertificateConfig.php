<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseCertificateConfig extends Model
{
    protected $fillable = [
        'course_id',
        'logo',
        'template_type',
        'background_image',
        'primary_color',
        'secondary_color',
        'certificate_text',
        'certificate_title',
        'show_qr_code',
    ];

    protected $casts = [
        'show_qr_code' => 'boolean',
    ];

    /**
     * Get the course that owns this config
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get background image URL
     */
    public function getBackgroundUrlAttribute(): ?string
    {
        if ($this->background_image) {
            return asset('storage/' . $this->background_image);
        }
        return null;
    }

    /**
     * Get logo URL
     */
    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }

    /**
     * Get default certificate text
     */
    public function getDefaultCertificateText(): string
    {
        return $this->certificate_text ??
            'This is to certify that {student_name} has successfully completed the course {course_title} on {completion_date}.';
    }
}
