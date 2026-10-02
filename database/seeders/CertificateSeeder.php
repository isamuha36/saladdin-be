<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCertificateConfig;
use App\Models\CertificateSignature;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first course for testing
        $course = Course::first();

        if (!$course) {
            $this->command->warn('No courses found. Please run CourseSeeder first.');
            return;
        }

        // Create certificate configuration
        CourseCertificateConfig::updateOrCreate(
            ['course_id' => $course->id],
            [
                'template_type' => 'classic',
                'primary_color' => '#1e3a8a', // Dark blue
                'secondary_color' => '#d4af37', // Gold
                'certificate_text' => 'Dengan penuh kebanggaan memberikan penghargaan kepada:',
                'certificate_title' => 'SERTIFIKAT',
                'show_qr_code' => true,
            ]
        );

        // Create sample signatures
        $signatures = [
            [
                'course_id' => $course->id,
                'signatory_name' => 'Dr. Ahmad Marifi',
                'signatory_title' => 'Course Instructor',
                'order' => 1,
            ],
            [
                'course_id' => $course->id,
                'signatory_name' => 'Prof. Saladdin Rahman',
                'signatory_title' => 'Director of Education',
                'order' => 2,
            ],
        ];

        foreach ($signatures as $signature) {
            CertificateSignature::updateOrCreate(
                [
                    'course_id' => $signature['course_id'],
                    'signatory_name' => $signature['signatory_name'],
                ],
                $signature
            );
        }

        $this->command->info('Certificate configuration and signatures created successfully!');
    }
}
