<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();

            // Header attempt (satu kali pengerjaan quiz)
            $table->foreignId('quiz_attempt_id')
                ->constrained('quiz_attempts')
                ->cascadeOnDelete();

            // Pertanyaan mana yang dijawab
            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnDelete();

            // Opsi yang dipilih user (nullable jika tidak menjawab)
            $table->foreignId('selected_option_id')
                ->nullable()
                ->constrained('question_options')
                ->nullOnDelete();

            // hasil evaluasi saat submit
            $table->boolean('is_correct')->default(false);
            $table->integer('earned_points')->default(0);

            $table->timestamps();

            // 1 attempt hanya boleh punya 1 jawaban untuk 1 pertanyaan
            $table->unique(['quiz_attempt_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
    }
};
