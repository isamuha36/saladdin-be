<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnail ?? null,
            'status' => $this->status ?? null,
            'instructor_name' => $this->instructor_name ?? null,
            // progress and is_enrolled injected by service; default fallback
            'progress' => $this->when(isset($this->progress), $this->progress, 0),
            'is_enrolled' => $this->when(isset($this->is_enrolled), (bool) $this->is_enrolled, false),
            // optional: include basic sections summary
            'sections_count' => $this->whenLoaded('sections', function () {
                return $this->sections->count();
            }),
        ];
    }
}
