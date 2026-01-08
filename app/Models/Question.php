<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = ['id'];

    // Kebalikan: Soal ini milik Lesson (Kuis) mana?
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    // Relasi: 1 Soal punya banyak Opsi Jawaban (A, B, C, D)
    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }
}
