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
        // Course 1
        $course1 = Course::create([
            'title' => 'Sejarah Lengkap Masjidil Aqsa',
            'slug' => 'sejarah-lengkap-masjidil-aqsa',
            'thumbnail' => 'https://placehold.co/600x400/1e1e1e/d4af37?text=Aqsa+Course',
            'price' => 0,
            'status' => 'published',
            'instructor_name' => 'Dr. Yasir Qadhi',
            'description' => 'Mempelajari sejarah Baitul Maqdis dari masa Nabi Adam hingga sekarang.',
        ]);

        $section1 = Section::create([
            'course_id' => $course1->id,
            'title' => 'Modul 1: Era Umayyah',
            'sort_order' => 1,
        ]);

        // Video lesson (has content_url + content_text)
        $l1 = Lesson::create([
            'section_id' => $section1->id,
            'title' => 'Saladin Camp Episode 1',
            'slug' => 'saladin-camp-episode-1',
            'sort_order' => 1,
            'type' => 'video',
            'content_source' => 'external',
            'content_url' => 'https://youtu.be/cUlAnDoGXpM',
            'content_text' => '<p>Intro: Sejarah singkat dan konteks Masjidil Aqsa.</p>',
        ]);

        // Document lesson (has content_path and a short text)
        $l2 = Lesson::create([
            'section_id' => $section1->id,
            'title' => 'Modul PDF',
            'slug' => 'modul-pdf',
            'sort_order' => 2,
            'type' => 'document',
            'content_source' => 'upload',
            'content_path' => '/uploads/modules/aqsa_modul_1.pdf',
            'content_text' => '<p>Silabus dan bacaan pendukung (PDF).</p>',
        ]);

        // Text lesson (rich text)
        $l3 = Lesson::create([
            'section_id' => $section1->id,
            'title' => 'Arsitektur Interior',
            'slug' => 'arsitektur-interior',
            'sort_order' => 3,
            'type' => 'text',
            'content_text' => '<h1>Detail Mosaik</h1><p>Mosaik di dalam masjid sangat indah dan penuh simbolisme.</p>',
        ]);

        // Quiz lesson (content_text + questions)
        $quiz1 = Lesson::create([
            'section_id' => $section1->id,
            'title' => 'Evaluasi Modul 1',
            'slug' => 'evaluasi-modul-1',
            'sort_order' => 4,
            'type' => 'quiz',
            'duration_minutes' => 15,
            'passing_grade' => 75,
            'content_text' => '<p>Kerjakan kuis ini dengan teliti. KKM 75.</p>',
        ]);

        $q1 = Question::create([
            'lesson_id' => $quiz1->id,
            'question_text' => 'Siapa khalifah yang membangun Kubah Shakhrah?',
            'points' => 10,
        ]);
        QuestionOption::create(['question_id' => $q1->id, 'option_text' => 'Muawiyah', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q1->id, 'option_text' => 'Abd al-Malik bin Marwan', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q1->id, 'option_text' => 'Al-Walid', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q1->id, 'option_text' => 'Umar II', 'is_correct' => false]);

        // Course 2 (PHP - gratis)
        $course2 = Course::create([
            'title' => 'Dasar Pemrograman PHP',
            'slug' => 'dasar-pemrograman-php',
            'thumbnail' => 'https://placehold.co/600x400/png?text=PHP',
            'price' => 0,
            'status' => 'published',
            'instructor_name' => 'Admin PHP',
            'description' => 'Belajar dasar-dasar PHP dari nol.',
        ]);

        $s2a = Section::create(['course_id' => $course2->id, 'title' => 'Pendahuluan', 'sort_order' => 1]);
        $s2b = Section::create(['course_id' => $course2->id, 'title' => 'Praktik', 'sort_order' => 2]);

        Lesson::create([
            'section_id' => $s2a->id,
            'title' => 'Pengenalan PHP',
            'slug' => 'pengenalan-php',
            'sort_order' => 1,
            'type' => 'video',
            'content_source' => 'external',
            'content_url' => 'https://youtu.be/example-php',
            'content_text' => '<p>Video pengantar PHP: sejarah singkat dan penggunaan.</p>',
        ]);

        Lesson::create([
            'section_id' => $s2a->id,
            'title' => 'Dokumentasi Instalasi',
            'slug' => 'dokumentasi-instalasi-php',
            'sort_order' => 2,
            'type' => 'document',
            'content_source' => 'upload',
            'content_path' => '/uploads/modules/php_installation.pdf',
            'content_text' => '<p>Langkah instalasi PHP pada Windows dan Linux.</p>',
        ]);

        Lesson::create([
            'section_id' => $s2b->id,
            'title' => 'Sintaks Dasar',
            'slug' => 'sintaks-dasar-php',
            'sort_order' => 1,
            'type' => 'text',
            'content_text' => '<p>Variabel, fungsi, kontrol alur: contoh kode dan penjelasan.</p>',
        ]);

        $quiz2 = Lesson::create([
            'section_id' => $s2b->id,
            'title' => 'Kuis PHP Dasar',
            'slug' => 'kuis-php-dasar',
            'sort_order' => 2,
            'type' => 'quiz',
            'duration_minutes' => 10,
            'passing_grade' => 70,
            'content_text' => '<p>Kuis singkat PHP.</p>',
        ]);

        $q2 = Question::create([
            'lesson_id' => $quiz2->id,
            'question_text' => 'Bagaimana cara membuat variabel di PHP?',
            'points' => 10,
        ]);
        QuestionOption::create(['question_id' => $q2->id, 'option_text' => 'var $a', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q2->id, 'option_text' => '$a = 1;', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q2->id, 'option_text' => 'let a = 1;', 'is_correct' => false]);

        // Course 3 (Lumen - gratis)
        $course3 = Course::create([
            'title' => 'Membangun REST API dengan Lumen',
            'slug' => 'rest-api-lumen',
            'thumbnail' => 'https://placehold.co/600x400/png?text=Lumen',
            'price' => 10000,
            'status' => 'published',
            'instructor_name' => 'API Expert',
            'description' => 'Praktik membuat API ringan menggunakan Lumen.',
        ]);

        $s3a = Section::create(['course_id' => $course3->id, 'title' => 'Konsep API', 'sort_order' => 1]);
        $s3b = Section::create(['course_id' => $course3->id, 'title' => 'Praktikum API', 'sort_order' => 2]);

        Lesson::create([
            'section_id' => $s3a->id,
            'title' => 'Apa itu REST',
            'slug' => 'apa-itu-rest',
            'sort_order' => 1,
            'type' => 'video',
            'content_source' => 'external',
            'content_url' => 'https://youtu.be/example-rest',
            'content_text' => '<p>Pengenalan REST dan prinsipnya.</p>',
        ]);

        Lesson::create([
            'section_id' => $s3a->id,
            'title' => 'API Spec (PDF)',
            'slug' => 'api-spec-pdf',
            'sort_order' => 2,
            'type' => 'document',
            'content_source' => 'upload',
            'content_path' => '/uploads/modules/lumen_api_spec.pdf',
            'content_text' => '<p>Contoh spesifikasi API yang baik.</p>',
        ]);

        Lesson::create([
            'section_id' => $s3b->id,
            'title' => 'Membuat Endpoint',
            'slug' => 'membuat-endpoint-lumen',
            'sort_order' => 1,
            'type' => 'text',
            'content_text' => '<p>Langkah membuat endpoint sederhana di Lumen dengan contoh kode.</p>',
        ]);

        $quiz3 = Lesson::create([
            'section_id' => $s3b->id,
            'title' => 'Kuis REST API',
            'slug' => 'kuis-rest-api',
            'sort_order' => 2,
            'type' => 'quiz',
            'duration_minutes' => 10,
            'passing_grade' => 70,
            'content_text' => '<p>Kuis singkat tentang REST API</p>',
        ]);

        $q3 = Question::create([
            'lesson_id' => $quiz3->id,
            'question_text' => 'Metode HTTP yang umum dipakai untuk mengambil data?',
            'points' => 10,
        ]);
        QuestionOption::create(['question_id' => $q3->id, 'option_text' => 'POST', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q3->id, 'option_text' => 'GET', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q3->id, 'option_text' => 'DELETE', 'is_correct' => false]);
    }
}
