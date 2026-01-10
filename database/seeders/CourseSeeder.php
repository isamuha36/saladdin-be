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
            'title' => 'Saladin Camp Episode 1',
            'slug' => 'saladin-camp-episode-1',
            'sort_order' => 1,
            'type' => 'video',

            'content_source' => 'external',
            'content_url' => 'https://youtu.be/cUlAnDoGXpM?si=g24vWtYN-4kgAr8i',
            'content_path' => null,
            'content_mime' => null,

            'content_text' => '<p>Al I\'dad Al Ma\'rifi (Persiapan Pengetahuan) (Part 1)</p>',
        ]);

        // 4. LESSON 1B: TIPE DOCUMENT (PDF)
        Lesson::create([
            'section_id' => $section1->id,
            'title' => 'Modul PDF',
            'slug' => 'modul-pdf',
            'sort_order' => 4,
            'type' => 'document',
            'content_source' => 'upload',
            'content_path' => '',
        ]);

        // 5. LESSON 2: TIPE TEXT (ARTIKEL)
        Lesson::create([
            'section_id' => $section1->id,
            'title' => 'Arsitektur Interior',
            'slug' => 'arsitektur-interior',
            'sort_order' => 2,
            'type' => 'text',

            'content_source' => null,
            'content_url' => null,
            'content_path' => null,
            'content_mime' => null,

            'content_text' => '<h1>Detail Mosaik</h1><p>Mosaik di dalam masjid sangat indah...</p>',
        ]);

        // 6. LESSON 3: TIPE QUIZ
        $quiz = Lesson::create([
            'section_id' => $section1->id,
            'title' => 'Evaluasi Modul 1',
            'slug' => 'evaluasi-modul-1',
            'sort_order' => 3,
            'type' => 'quiz',

            'duration_minutes' => 15,
            'passing_grade' => 75,
            'content_text' => '<p>Kerjakan kuis ini dengan teliti. KKM 75.</p>',
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
