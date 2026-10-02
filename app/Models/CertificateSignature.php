<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateSignature extends Model
{
    protected $fillable = [
        'course_id',
        'signatory_name',
        'signatory_title',
        'signature_image',
        'order',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['signature_url'];

    /**
     * Get the course that owns this signature
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get signature image URL
     */
    public function getSignatureUrlAttribute(): ?string
    {
        if ($this->signature_image) {
            return asset('storage/' . $this->signature_image);
        }
        return null;
    }
}
