<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Courses (Induk)
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); // URL friendly
            $table->string('thumbnail')->nullable(); // URL/Path gambar
            $table->decimal('price', 10, 2)->default(0);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->text('description')->nullable();
            $table->string('instructor_name')->nullable(); // Manual input nama ustaz
            $table->softDeletes(); // Data tidak langsung hilang permanen jika dihapus
            $table->timestamps();
        });

        // 2. Tabel Sections (Bab/Modul)
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title');
            $table->integer('sort_order')->default(0); // Urutan Bab
            $table->timestamps();
        });

        // 3. Tabel Lessons (Materi: Video, Text, Quiz)
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->integer('sort_order')->default(0);
            
            // Tipe Utama
            $table->enum('type', ['video', 'text', 'quiz']); 
            
            // --- LOGIC VIDEO ---
            // 'upload' = file MP4 di server, 'youtube' = link URL, 'vimeo' = link URL
            $table->enum('video_source', ['upload', 'youtube', 'vimeo'])->nullable(); 
            $table->string('video_path')->nullable(); // Simpan Path File atau URL Youtube

            // --- LOGIC TEXT & WYSIWYG ---
            // Digunakan untuk: "Article Content" ATAU "Video Overview" ATAU "Quiz Description"
            // Menggunakan longText agar muat HTML gambar/teks panjang (aman 4GB)
            $table->longText('content_text')->nullable(); 
            
            // --- LOGIC ATTACHMENTS ---
            // File PDF/PPT tambahan
            $table->string('attachment_path')->nullable(); 

            // --- LOGIC QUIZ CONFIG ---
            $table->integer('duration_minutes')->nullable(); // Durasi kuis
            $table->integer('passing_grade')->nullable(); // Nilai lulus (0-100)
            
            $table->boolean('is_preview')->default(false); // Gratis ditonton tanpa beli?
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('courses');
    }
};