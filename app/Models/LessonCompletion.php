<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonCompletion extends Model
{
    protected $table = 'course_progress'; // menggunakan table course_progress
    protected $fillable = ['user_id', 'lesson_id', 'completed_at'];
    public $timestamps = false;

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
