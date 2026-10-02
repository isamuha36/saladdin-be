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
            $table->enum('type', ['video', 'document', 'text', 'quiz']);

            $table->enum('content_source', ['upload', 'external'])->nullable(); // upload | external
            $table->string('content_path')->nullable();  // path file kalau upload (mp4/pdf)
            $table->string('content_url')->nullable();   // url kalau external
            $table->string('content_mime')->nullable();  // video/mp4 atau application/pdf

            // Text / Overview / Instruksi (WYSIWYG HTML)
            $table->longText('content_text')->nullable();

            // Quiz config
            $table->integer('duration_minutes')->nullable();
            $table->integer('passing_grade')->nullable();

            // Preview (kalau kamu masih butuh fitur preview)
            $table->boolean('is_preview')->default(false);

            $table->timestamps();

            // Optional (bagus): cegah slug tabrakan dalam 1 section
            $table->unique(['section_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('courses');
    }
};
