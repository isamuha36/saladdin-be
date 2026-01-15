<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question' => $this->question_text,
            'points' => $this->points ?? 0,
            'sequence' => $request->query('seq') ? (int)$request->query('seq') : null,
            'options' => $this->options->map(function ($opt) {
                return [
                    'id' => $opt->id,
                    'text' => $opt->option_text,
                ];
            }),
        ];
    }
}