<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Section;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BUAT KURSUS UTAMA
        $course = Course::create([
            'title' => 'Sejarah Lengkap Masjidil Aqsa',
            'slug' => 'sejarah-lengkap-masjidil-aqsa',
            'thumbnail' => 'https://placehold.co/600x400/1e1e1e/d4af37?text=Aqsa+Course',
            'price' => 150000,
            'status' => 'published',
            'instructor_name' => 'Dr. Yasir Qadhi',
            'description' => 'Mempelajari sejarah Baitul Maqdis dari masa Nabi Adam hingga sekarang.',
        ]);

        // 2. BUAT SECTION (BAB 1)
        $section1 = Section::create([
            'course_id' => $course->id,
            'title' => 'Modul 1: Era Umayyah',
            'sort_order' => 1,
        ]);

        // 3. LESSON 1: TIPE VIDEO (YOUTUBE)
        Lesson::create([
            'section_id' => $section1->id,
            'title' => 'Pembangunan Kubah Shakhrah',
            'slug' => 'pembangunan-kubah',
            'sort_order' => 1,
            'type' => 'video',
            'video_source' => 'youtube',
            'video_path' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Link Dummy
            'content_text' => '<p>Video ini menjelaskan arsitektur awal kubah.</p>', // Overview
            'is_preview' => true,
        ]);

        // 4. LESSON 2: TIPE TEXT (ARTIKEL)
        Lesson::create([
            'section_id' => $section1->id,
            'title' => 'Arsitektur Interior',
            'slug' => 'arsitektur-interior',
            'sort_order' => 2,
            'type' => 'text',
            'content_text' => '<h1>Detail Mosaik</h1><p>Mosaik di dalam masjid sangat indah...</p>',
        ]);

        // 5. LESSON 3: TIPE QUIZ
        $quiz = Lesson::create([
            'section_id' => $section1->id,
            'title' => 'Evaluasi Modul 1',
            'slug' => 'evaluasi-modul-1',
            'sort_order' => 3,
            'type' => 'quiz',
            'duration_minutes' => 15,
            'passing_grade' => 75,
            'content_text' => '<p>Kerjakan kuis ini dengan teliti. KKM 75.</p>', // Instruksi Kuis
        ]);

        // --- BIKIN SOAL UNTUK KUIS DI ATAS ---

        // Soal No 1
        $q1 = Question::create([
            'lesson_id' => $quiz->id,
            'question_text' => 'Siapa khalifah yang membangun Kubah Shakhrah?',
            'points' => 10,
        ]);

        // Opsi Jawaban Soal No 1
        QuestionOption::create(['question_id' => $q1->id, 'option_text' => 'Muawiyah', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q1->id, 'option_text' => 'Abd al-Malik bin Marwan', 'is_correct' => true]); // BENAR
        QuestionOption::create(['question_id' => $q1->id, 'option_text' => 'Al-Walid', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q1->id, 'option_text' => 'Umar II', 'is_correct' => false]);
    }
}
