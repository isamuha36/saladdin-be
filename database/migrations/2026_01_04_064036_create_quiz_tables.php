<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Pertanyaan
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
            
            // Gunakan longText agar admin bisa memasukkan gambar/teks panjang di soal
            $table->longText('question_text'); 
            
            $table->integer('points')->default(10); // Bobot nilai per soal
            $table->longText('explanation')->nullable(); // Pembahasan (Rich Text)
            $table->timestamps();
        });

        // 2. Tabel Opsi Jawaban (A, B, C, D)
        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            
            $table->string('option_text'); // Teks jawaban
            $table->boolean('is_correct')->default(false); // Kunci Jawaban (1=Benar)
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_options');
        Schema::dropIfExists('questions');
    }
};