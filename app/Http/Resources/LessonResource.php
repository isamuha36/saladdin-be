<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // 1. LOGIC VIDEO URL
        $videoUrl = null;
        if ($this->type === 'video') {
            $videoUrl = ($this->video_source === 'upload')
                ? asset('storage/' . $this->video_path) // Kalau upload, kasih link server
                : $this->video_path; // Kalau youtube, kasih link aslinya
        }

        // 2. LOGIC ATTACHMENT
        $attachmentUrl = $this->attachment_path
            ? asset('storage/' . $this->attachment_path)
            : null;

        // 3. LOGIC QUIZ (Sembunyikan Kunci Jawaban!)
        $quizData = null;
        if ($this->type === 'quiz') {
            $quizData = [
                'duration' => $this->duration_minutes . ' Menit',
                'passing_grade' => $this->passing_grade,
                'questions' => $this->questions->map(function ($q) {
                    return [
                        'id' => $q->id,
                        'question' => $q->question_text,
                        'points' => $q->points,
                        'options' => $q->options->map(function ($opt) {
                            return [
                                'id' => $opt->id,
                                'text' => $opt->option_text,
                                // 'is_correct' DIHAPUS demi keamanan!
                            ];
                        }),
                    ];
                }),
            ];
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'type' => $this->type,
            'is_preview' => (bool) $this->is_preview,

            // Konten (Tergantung Tipe)
            'content_video' => $videoUrl,
            'content_text' => $this->content_text, // HTML dari WYSIWYG
            'content_quiz' => $quizData,

            'attachment_url' => $attachmentUrl,
        ];
    }
}
