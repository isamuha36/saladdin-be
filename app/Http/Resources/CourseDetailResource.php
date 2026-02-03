<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $completedLessonIds = $this->completed_lesson_ids ?? [];

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnail,
            'instructor' => $this->instructor_name,
            'price' => (int) $this->price,
            'price_formatted' => 'Rp ' . number_format($this->price, 0, ',', '.'),
            'description' => $this->description,
            'sections' => $this->sections->map(function ($section) use ($completedLessonIds) {
                return [
                    'id' => $section->id,
                    'title' => $section->title,
                    'lessons' => $section->lessons->map(function ($lesson) use ($completedLessonIds) {
                        return [
                            'id' => $lesson->id,
                            'title' => $lesson->title,
                            'slug' => $lesson->slug,
                            'type' => $lesson->type,
                            'is_completed' => in_array($lesson->id, $completedLessonIds),
                        ];
                    }),
                ];
            }),
            'is_enrolled' => $this->is_enrolled ?? false,
            'progress' => $this->progress ?? null, // null jika guest atau belum enroll
        ];
    }
}
