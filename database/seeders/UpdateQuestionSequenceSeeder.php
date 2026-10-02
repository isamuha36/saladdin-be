<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;

class UpdateQuestionSequenceSeeder extends Seeder
{
    public function run(): void
    {
        // Update sequence for existing questions
        $quizLessons = Lesson::where('type', 'quiz')->get();

        foreach ($quizLessons as $lesson) {
            $seq = 1;
            foreach ($lesson->questions()->orderBy('id')->get() as $question) {
                $question->update(['sequence' => $seq++]);
            }
        }

        $this->command->info('Updated sequence numbers for all existing questions.');
    }
}
