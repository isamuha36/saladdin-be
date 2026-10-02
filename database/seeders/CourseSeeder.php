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
        /**
         * =========================================
         * Course 1: Sejarah & Sirah (Masjidil Aqsa)
         * =========================================
         */
        $course1 = Course::create([
            'title' => 'Sejarah Masjidil Aqsa & Baitul Maqdis',
            'slug' => 'sejarah-masjidil-aqsa-baitul-maqdis',
            'thumbnail' => 'https://placehold.co/600x400/1e1e1e/d4af37?text=Masjidil+Aqsa',
            'price' => 0,
            'status' => 'published',
            'instructor_name' => 'Ust. Ahmad Marifi',
            'description' => 'Mempelajari sejarah Baitul Maqdis, kedudukan Masjidil Aqsa, dan peristiwa penting yang terkait dengannya.',
        ]);

        $section1 = Section::create([
            'course_id' => $course1->id,
            'title' => 'Modul 1: Kedudukan & Sejarah Singkat',
            'sort_order' => 1,
        ]);

        // Video lesson (YouTube external)
        $l1 = Lesson::create([
            'section_id' => $section1->id,
            'title' => 'Pengantar: Keutamaan Masjidil Aqsa',
            'slug' => 'pengantar-keutamaan-masjidil-aqsa',
            'sort_order' => 1,
            'type' => 'video',
            'content_source' => 'external',
            'content_url' => 'https://youtu.be/cUlAnDoGXpM', // ganti sesuai video kamu
            'content_text' => '<p>Pengantar tentang keutamaan Masjidil Aqsa dan gambaran sejarah Baitul Maqdis.</p>',
        ]);

        // Document lesson (PDF upload)
        $l2 = Lesson::create([
            'section_id' => $section1->id,
            'title' => 'Ringkasan Materi (PDF)',
            'slug' => 'ringkasan-materi-pdf-aqsa',
            'sort_order' => 2,
            'type' => 'document',
            'content_source' => 'upload',
            'content_path' => '/uploads/modules/ringkasan_aqsa_modul_1.pdf',
            'content_text' => '<p>Ringkasan poin penting modul 1 dalam bentuk PDF.</p>',
        ]);

        // Text lesson (rich text)
        $l3 = Lesson::create([
            'section_id' => $section1->id,
            'title' => 'Kronologi Singkat Peristiwa Penting',
            'slug' => 'kronologi-singkat-peristiwa-penting',
            'sort_order' => 3,
            'type' => 'text',
            'content_text' => '<h2>Kronologi</h2><ul><li>Makna Baitul Maqdis dalam sejarah umat.</li><li>Peristiwa Isra’ Mi’raj dan keterkaitannya.</li><li>Peran ulama dalam menjaga warisan ilmu.</li></ul>',
        ]);

        // Quiz lesson
        $quiz1 = Lesson::create([
            'section_id' => $section1->id,
            'title' => 'Kuis Modul 1: Masjidil Aqsa',
            'slug' => 'kuis-modul-1-masjidil-aqsa',
            'sort_order' => 4,
            'type' => 'quiz',
            'duration_minutes' => 15,
            'passing_grade' => 75,
            'content_text' => '<p>Jawablah pertanyaan berikut sesuai materi. KKM 75.</p>',
        ]);

        $q1 = Question::create([
            'lesson_id' => $quiz1->id,
            'question_text' => 'Masjid yang termasuk dalam tiga masjid yang dianjurkan untuk dikunjungi adalah...',
            'points' => 10,
        ]);
        QuestionOption::create(['question_id' => $q1->id, 'option_text' => 'Masjid Nabawi', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q1->id, 'option_text' => 'Masjid Quba', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q1->id, 'option_text' => 'Masjid Raya setempat', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q1->id, 'option_text' => 'Semua masjid sama persis keutamaannya', 'is_correct' => false]);

        /**
         * =========================================
         * Course 2: Fiqih Ibadah Dasar
         * =========================================
         */
        $course2 = Course::create([
            'title' => 'Fiqih Ibadah Dasar: Wudhu & Shalat',
            'slug' => 'fiqih-ibadah-dasar-wudhu-shalat',
            'thumbnail' => 'https://placehold.co/600x400/0b3d2e/ffffff?text=Fiqih+Ibadah',
            'price' => 0,
            'status' => 'published',
            'instructor_name' => 'Ustadzah Nisa',
            'description' => 'Materi dasar fiqih ibadah: wudhu, shalat, syarat, rukun, dan kesalahan yang sering terjadi.',
        ]);

        $s2a = Section::create(['course_id' => $course2->id, 'title' => 'Modul 1: Wudhu', 'sort_order' => 1]);
        $s2b = Section::create(['course_id' => $course2->id, 'title' => 'Modul 2: Shalat', 'sort_order' => 2]);

        Lesson::create([
            'section_id' => $s2a->id,
            'title' => 'Niat, Rukun, dan Sunnah Wudhu',
            'slug' => 'niat-rukun-sunnah-wudhu',
            'sort_order' => 1,
            'type' => 'video',
            'content_source' => 'external',
            'content_url' => 'https://youtu.be/3uQy7yWudhuX', // placeholder, ganti sesuai video
            'content_text' => '<p>Pembahasan ringkas tentang niat, rukun, dan sunnah wudhu.</p>',
        ]);

        Lesson::create([
            'section_id' => $s2a->id,
            'title' => 'Panduan Wudhu (PDF)',
            'slug' => 'panduan-wudhu-pdf',
            'sort_order' => 2,
            'type' => 'document',
            'content_source' => 'upload',
            'content_path' => '/uploads/modules/panduan_wudhu.pdf',
            'content_text' => '<p>Ringkasan langkah wudhu dan hal yang membatalkannya.</p>',
        ]);

        Lesson::create([
            'section_id' => $s2b->id,
            'title' => 'Syarat & Rukun Shalat',
            'slug' => 'syarat-dan-rukun-shalat',
            'sort_order' => 1,
            'type' => 'text',
            'content_text' => '<h2>Syarat Shalat</h2><p>Menutup aurat, suci dari hadats dan najis, menghadap kiblat, masuk waktu.</p><h2>Rukun Shalat</h2><p>Niat, takbiratul ihram, berdiri bagi yang mampu, ruku, sujud, tasyahud akhir, salam.</p>',
        ]);

        $quiz2 = Lesson::create([
            'section_id' => $s2b->id,
            'title' => 'Kuis: Fiqih Shalat Dasar',
            'slug' => 'kuis-fiqih-shalat-dasar',
            'sort_order' => 2,
            'type' => 'quiz',
            'duration_minutes' => 10,
            'passing_grade' => 70,
            'content_text' => '<p>Kuis singkat untuk menguatkan pemahaman.</p>',
        ]);

        $q2 = Question::create([
            'lesson_id' => $quiz2->id,
            'question_text' => 'Berikut ini yang termasuk rukun shalat adalah...',
            'points' => 10,
        ]);
        QuestionOption::create(['question_id' => $q2->id, 'option_text' => 'Takbiratul ihram', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q2->id, 'option_text' => 'Membaca doa setelah shalat', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q2->id, 'option_text' => 'Bersiwak sebelum shalat', 'is_correct' => false]);

        /**
         * =========================================
         * Course 3: Tafsir Tematik (Akhlak & Adab)
         * =========================================
         */
        $course3 = Course::create([
            'title' => 'Tafsir Tematik: Akhlak & Adab Sehari-hari',
            'slug' => 'tafsir-tematik-akhlak-adab',
            'thumbnail' => 'https://placehold.co/600x400/1b2a49/ffffff?text=Tafsir+Akhlak',
            'price' => 0,
            'status' => 'published',
            'instructor_name' => 'Ust. Salman',
            'description' => 'Mempelajari ayat-ayat pilihan tentang akhlak, adab, dan muamalah dalam kehidupan sehari-hari.',
        ]);

        $s3a = Section::create(['course_id' => $course3->id, 'title' => 'Modul 1: Akhlak', 'sort_order' => 1]);
        $s3b = Section::create(['course_id' => $course3->id, 'title' => 'Modul 2: Adab', 'sort_order' => 2]);

        Lesson::create([
            'section_id' => $s3a->id,
            'title' => 'Kejujuran dalam Islam',
            'slug' => 'kejujuran-dalam-islam',
            'sort_order' => 1,
            'type' => 'video',
            'content_source' => 'external',
            'content_url' => 'https://youtu.be/4AkhlakJujurX', // placeholder, ganti sesuai video
            'content_text' => '<p>Pembahasan tentang nilai kejujuran dan dampaknya dalam kehidupan.</p>',
        ]);

        Lesson::create([
            'section_id' => $s3a->id,
            'title' => 'Ringkasan Materi Akhlak (PDF)',
            'slug' => 'ringkasan-materi-akhlak-pdf',
            'sort_order' => 2,
            'type' => 'document',
            'content_source' => 'upload',
            'content_path' => '/uploads/modules/ringkasan_akhlak.pdf',
            'content_text' => '<p>Ringkasan poin penting tentang akhlak dalam Islam.</p>',
        ]);

        Lesson::create([
            'section_id' => $s3b->id,
            'title' => 'Adab Menuntut Ilmu',
            'slug' => 'adab-menuntut-ilmu',
            'sort_order' => 1,
            'type' => 'text',
            'content_text' => '<h2>Adab Menuntut Ilmu</h2><ol><li>Ikhlas karena Allah</li><li>Rendah hati</li><li>Menjaga adab kepada guru</li><li>Istiqamah dan sabar</li></ol>',
        ]);

        $quiz3 = Lesson::create([
            'section_id' => $s3b->id,
            'title' => 'Kuis: Akhlak & Adab',
            'slug' => 'kuis-akhlak-adab',
            'sort_order' => 2,
            'type' => 'quiz',
            'duration_minutes' => 10,
            'passing_grade' => 70,
            'content_text' => '<p>Kuis singkat seputar akhlak dan adab.</p>',
        ]);

        $q3 = Question::create([
            'lesson_id' => $quiz3->id,
            'question_text' => 'Adab yang paling utama ketika menuntut ilmu adalah...',
            'points' => 10,
        ]);
        QuestionOption::create(['question_id' => $q3->id, 'option_text' => 'Ikhlas karena Allah', 'is_correct' => true]);
        QuestionOption::create(['question_id' => $q3->id, 'option_text' => 'Pamer ilmu di media sosial', 'is_correct' => false]);
        QuestionOption::create(['question_id' => $q3->id, 'option_text' => 'Merendahkan guru', 'is_correct' => false]);
    }
}
