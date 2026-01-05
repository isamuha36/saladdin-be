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

    // Helper: Ambil semua Lesson lewat Section
    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, Section::class);
    }
}
