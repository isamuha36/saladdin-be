<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // URL konten utama (video/document) - final URL yang siap dipakai frontend
        $contentUrl = null;

        if (in_array($this->type, ['video', 'document'])) {
            if ($this->content_source === 'upload' && $this->content_path) {
                $contentUrl = asset('storage/' . $this->content_path);
            } elseif ($this->content_source === 'external') {
                $contentUrl = $this->content_url;
            }
        }

        // Quiz data (sembunyikan jawaban)
        $quizData = null;
        if ($this->type === 'quiz') {
            $quizData = [
                'duration' => ($this->duration_minutes ?? 0) . ' Menit',
                'passing_grade' => $this->passing_grade,
                'questions_count' => $this->questions()->count(),
            ];
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'type' => $this->type, // video|document|text|quiz

            'content' => [
                'source' => $this->content_source,   // upload|external|null
                'url'    => $contentUrl,             // url final
                'mime'   => $this->content_mime,     // video/mp4 atau application/pdf
            ],

            'content_text' => $this->content_text,

            'content_quiz' => $quizData,
        ];
    }
}
