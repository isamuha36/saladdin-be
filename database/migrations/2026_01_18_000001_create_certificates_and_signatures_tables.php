<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Table for certificate configuration per course
        Schema::create('course_certificate_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('template_type')->default('classic'); // classic, modern, elegant, minimal
            $table->string('background_image')->nullable(); // optional custom background
            $table->string('primary_color')->default('#1e3a8a'); // dark blue
            $table->string('secondary_color')->default('#d4af37'); // gold
            $table->text('certificate_text')->nullable(); // custom certificate text
            $table->string('certificate_title')->default('Certificate of Completion');
            $table->boolean('show_qr_code')->default(true); // QR code for verification
            $table->timestamps();
        });

        // Table for multiple signatures per course
        Schema::create('certificate_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('signatory_name'); // e.g., "Dr. Ahmad Marifi"
            $table->string('signatory_title'); // e.g., "Course Instructor" / "Director"
            $table->string('signature_image')->nullable(); // path to signature image
            $table->integer('order')->default(1); // display order (1, 2, 3...)
            $table->timestamps();
        });

        // Table for issued certificates
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('certificate_number')->unique(); // e.g., CERT-2026-00001
            $table->string('pdf_path')->nullable(); // path to generated PDF
            $table->timestamp('issued_at');
            $table->timestamps();

            // Ensure user can only have one certificate per course
            $table->unique(['user_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('certificate_signatures');
        Schema::dropIfExists('course_certificate_configs');
    }
};
