<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_preview' => 'boolean', // Agar output JSON jadi true/false (bukan 1/0)
    ];

    // Kebalikan: Lesson milik Section siapa?
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    // Relasi: Jika Lesson ini QUIZ, dia punya banyak Pertanyaan
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    // Relasi: Tracking progress user di materi ini
    public function progress()
    {
        return $this->hasMany(CourseProgress::class);
    }
}
