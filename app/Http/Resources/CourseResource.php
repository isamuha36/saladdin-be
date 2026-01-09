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
            'thumbnail' => $this->thumbnail,
            'instructor' => $this->instructor_name,
            'price' => (int) $this->price,
            'price_formatted' => 'Rp ' . number_format($this->price, 0, ',', '.'),
        ];
    }
}
