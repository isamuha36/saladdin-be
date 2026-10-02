<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            // Mencegah duplikat enrollment: 1 user hanya bisa punya 1 enrollment per course
            $table->unique(['user_id', 'course_id'], 'enrollments_user_course_unique');
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropUnique('enrollments_user_course_unique');
        });
    }
};
