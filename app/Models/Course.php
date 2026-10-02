<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes; // Agar bisa restore data yang terhapus

    protected $guarded = ['id'];

    // Relasi: 1 Course punya banyak Section (Bab)
    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('sort_order');
    }

    // Relasi: 1 Course punya banyak Siswa (Enrollments)
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Relasi: Get students enrolled in this course (through enrollments)
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'user_id')
            ->withTimestamps();
    }

    // Helper: Ambil semua Lesson lewat Section
    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, Section::class);
    }

    // Relasi: Certificate configuration
    public function certificateConfig()
    {
        return $this->hasOne(CourseCertificateConfig::class);
    }

    // Relasi: Certificate signatures
    public function certificateSignatures()
    {
        return $this->hasMany(CertificateSignature::class)->orderBy('order');
    }

    // Relasi: Issued certificates
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailAttribute($value)
    {
        if ($value) {
            // If already full URL, return as is
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }
            // Convert path to full URL
            return asset('storage/' . $value);
        }
        return null;
    }
}
