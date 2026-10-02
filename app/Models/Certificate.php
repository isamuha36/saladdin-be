<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'certificate_number',
        'pdf_path',
        'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    /**
     * Get the user who owns the certificate
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course for this certificate
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the certificate URL
     */
    public function getCertificateUrlAttribute(): string
    {
        if ($this->pdf_path) {
            return asset('storage/' . $this->pdf_path);
        }
        return route('api.certificates.download', $this->id);
    }

    /**
     * Generate verification URL
     */
    public function getVerificationUrlAttribute(): string
    {
        return route('api.certificates.verify', $this->certificate_number);
    }
}
