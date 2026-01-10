<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            // waktu mulai & submit (berguna untuk durasi dan audit)
            $table->timestamp('started_at')->nullable()->after('lesson_id');
            $table->timestamp('submitted_at')->nullable()->after('started_at');

            // opsional: batasi skor 0-100 via unsigned
            // kalau tipe integer biasa sudah ok, boleh di-skip
            // $table->unsignedTinyInteger('score')->change();
        });
    }

    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropColumn(['started_at', 'submitted_at']);
        });
    }
};
