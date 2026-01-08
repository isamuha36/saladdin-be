<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseProgress extends Model
{
    protected $table = 'course_progress'; // Definisi nama tabel eksplisit
    protected $guarded = ['id'];
    public $timestamps = false; // Karena kita cuma butuh 'completed_at' manual atau timestamp create saja

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
