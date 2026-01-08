<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $guarded = ['id'];

    // Kebalikan: Section milik Course siapa?
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relasi: 1 Section punya banyak Lesson (Materi)
    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('sort_order');
    }
}
