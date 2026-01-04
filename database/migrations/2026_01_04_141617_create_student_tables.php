<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Enrollments (Pembelian/Akses Kursus)
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();

            $table->timestamp('enrolled_at');
            $table->enum('status', ['pending', 'active', 'completed', 'blocked'])->default('pending');
            $table->string('payment_proof')->nullable(); // Upload Bukti Transfer
            $table->timestamps();
        });

        // 2. Tabel Course Progress (Tracking Materi Selesai)
        Schema::create('course_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();

            $table->timestamp('completed_at')->useCurrent();

            // Penting: Satu user hanya bisa 'completed' satu materi sekali saja.
            $table->unique(['user_id', 'lesson_id']);
        });

        // 3. Tabel Quiz Attempts (Rekap Nilai Siswa)
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete(); // Kuis mana?

            $table->integer('score'); // Nilai Akhir (0-100)
            $table->enum('status', ['pass', 'fail']); // Status Lulus/Gagal

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('course_progress');
        Schema::dropIfExists('enrollments');
    }
};
