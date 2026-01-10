<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'section_id',
        'title',
        'slug',
        'type',             // video|document|text|quiz
        'duration_minutes', // durasi quiz
        'passing_grade',    // passing grade quiz
        'sort_order',

        // konten utama
        'content_source',  // upload|external
        'content_path',    // untuk upload
        'content_url',     // untuk external
        'content_mime',    // mime file

        // untuk text
        'content_text',
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
