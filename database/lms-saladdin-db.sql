-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table saladdin_db.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.cache: ~0 rows (approximately)

-- Dumping structure for table saladdin_db.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.cache_locks: ~0 rows (approximately)

-- Dumping structure for table saladdin_db.certificates
CREATE TABLE IF NOT EXISTS `certificates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `course_id` bigint unsigned NOT NULL,
  `certificate_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issued_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `certificates_user_id_course_id_unique` (`user_id`,`course_id`),
  UNIQUE KEY `certificates_certificate_number_unique` (`certificate_number`),
  KEY `certificates_course_id_foreign` (`course_id`),
  CONSTRAINT `certificates_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `certificates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.certificates: ~0 rows (approximately)

-- Dumping structure for table saladdin_db.certificate_signatures
CREATE TABLE IF NOT EXISTS `certificate_signatures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `signatory_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `signatory_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `signature_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `certificate_signatures_course_id_foreign` (`course_id`),
  CONSTRAINT `certificate_signatures_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.certificate_signatures: ~2 rows (approximately)
INSERT INTO `certificate_signatures` (`id`, `course_id`, `signatory_name`, `signatory_title`, `signature_image`, `order`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Dr. Ahmad Marifi', 'Course Instructor', NULL, 1, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(2, 1, 'Prof. Saladdin Rahman', 'Director of Education', NULL, 2, '2026-02-12 09:01:45', '2026-02-12 09:01:45');

-- Dumping structure for table saladdin_db.courses
CREATE TABLE IF NOT EXISTS `courses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `description` text COLLATE utf8mb4_unicode_ci,
  `instructor_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courses_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.courses: ~3 rows (approximately)
INSERT INTO `courses` (`id`, `title`, `slug`, `thumbnail`, `price`, `status`, `description`, `instructor_name`, `deleted_at`, `created_at`, `updated_at`) VALUES
	(1, 'Sejarah Masjidil Aqsa & Baitul Maqdis', 'sejarah-masjidil-aqsa-baitul-maqdis', 'https://placehold.co/600x400/1e1e1e/d4af37?text=Masjidil+Aqsa', 0.00, 'published', 'Mempelajari sejarah Baitul Maqdis, kedudukan Masjidil Aqsa, dan peristiwa penting yang terkait dengannya.', 'Ust. Ahmad Marifi', NULL, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(2, 'Fiqih Ibadah Dasar: Wudhu & Shalat', 'fiqih-ibadah-dasar-wudhu-shalat', 'https://placehold.co/600x400/0b3d2e/ffffff?text=Fiqih+Ibadah', 0.00, 'published', 'Materi dasar fiqih ibadah: wudhu, shalat, syarat, rukun, dan kesalahan yang sering terjadi.', 'Ustadzah Nisa', NULL, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(3, 'Tafsir Tematik: Akhlak & Adab Sehari-hari', 'tafsir-tematik-akhlak-adab', 'https://placehold.co/600x400/1b2a49/ffffff?text=Tafsir+Akhlak', 10000.00, 'published', 'Mempelajari ayat-ayat pilihan tentang akhlak, adab, dan muamalah dalam kehidupan sehari-hari.', 'Ust. Salman', NULL, '2026-02-12 09:01:45', '2026-02-12 09:01:45');

-- Dumping structure for table saladdin_db.course_certificate_configs
CREATE TABLE IF NOT EXISTS `course_certificate_configs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'classic',
  `background_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#1e3a8a',
  `secondary_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#d4af37',
  `certificate_text` text COLLATE utf8mb4_unicode_ci,
  `certificate_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Certificate of Completion',
  `show_qr_code` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `course_certificate_configs_course_id_foreign` (`course_id`),
  CONSTRAINT `course_certificate_configs_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.course_certificate_configs: ~1 rows (approximately)
INSERT INTO `course_certificate_configs` (`id`, `course_id`, `logo`, `template_type`, `background_image`, `primary_color`, `secondary_color`, `certificate_text`, `certificate_title`, `show_qr_code`, `created_at`, `updated_at`) VALUES
	(1, 1, NULL, 'classic', NULL, '#1e3a8a', '#d4af37', 'Dengan penuh kebanggaan memberikan penghargaan kepada:', 'SERTIFIKAT', 1, '2026-02-12 09:01:45', '2026-02-12 09:01:45');

-- Dumping structure for table saladdin_db.course_progress
CREATE TABLE IF NOT EXISTS `course_progress` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `lesson_id` bigint unsigned NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_progress_user_id_lesson_id_unique` (`user_id`,`lesson_id`),
  KEY `course_progress_lesson_id_foreign` (`lesson_id`),
  CONSTRAINT `course_progress_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `course_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.course_progress: ~0 rows (approximately)

-- Dumping structure for table saladdin_db.enrollments
CREATE TABLE IF NOT EXISTS `enrollments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `course_id` bigint unsigned NOT NULL,
  `enrolled_at` timestamp NOT NULL,
  `status` enum('pending','active','completed','blocked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_proof` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `enrollments_user_id_foreign` (`user_id`),
  KEY `enrollments_course_id_foreign` (`course_id`),
  CONSTRAINT `enrollments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enrollments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.enrollments: ~4 rows (approximately)
INSERT INTO `enrollments` (`id`, `user_id`, `course_id`, `enrolled_at`, `status`, `payment_proof`, `created_at`, `updated_at`) VALUES
	(1, 3, 1, '2026-02-12 09:01:45', 'active', NULL, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(2, 3, 2, '2026-02-12 09:01:45', 'active', NULL, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(3, 4, 3, '2026-02-12 09:01:45', 'pending', NULL, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(4, 4, 1, '2026-02-12 09:01:45', 'active', NULL, '2026-02-12 09:01:45', '2026-02-12 09:01:45');

-- Dumping structure for table saladdin_db.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table saladdin_db.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.jobs: ~0 rows (approximately)

-- Dumping structure for table saladdin_db.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.job_batches: ~0 rows (approximately)

-- Dumping structure for table saladdin_db.lessons
CREATE TABLE IF NOT EXISTS `lessons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `section_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `type` enum('video','document','text','quiz') COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_source` enum('upload','external') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content_mime` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content_text` longtext COLLATE utf8mb4_unicode_ci,
  `duration_minutes` int DEFAULT NULL,
  `passing_grade` int DEFAULT NULL,
  `is_preview` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lessons_section_id_slug_unique` (`section_id`,`slug`),
  CONSTRAINT `lessons_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.lessons: ~12 rows (approximately)
INSERT INTO `lessons` (`id`, `section_id`, `title`, `slug`, `sort_order`, `type`, `content_source`, `content_path`, `content_url`, `content_mime`, `content_text`, `duration_minutes`, `passing_grade`, `is_preview`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Pengantar: Keutamaan Masjidil Aqsa', 'pengantar-keutamaan-masjidil-aqsa', 1, 'video', 'external', NULL, 'https://youtu.be/cUlAnDoGXpM', NULL, '<p>Pengantar tentang keutamaan Masjidil Aqsa dan gambaran sejarah Baitul Maqdis.</p>', NULL, NULL, 0, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(2, 1, 'Ringkasan Materi (PDF)', 'ringkasan-materi-pdf-aqsa', 2, 'document', 'upload', '/uploads/modules/ringkasan_aqsa_modul_1.pdf', NULL, NULL, '<p>Ringkasan poin penting modul 1 dalam bentuk PDF.</p>', NULL, NULL, 0, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(3, 1, 'Kronologi Singkat Peristiwa Penting', 'kronologi-singkat-peristiwa-penting', 3, 'text', NULL, NULL, NULL, NULL, '<h2>Kronologi</h2><ul><li>Makna Baitul Maqdis dalam sejarah umat.</li><li>Peristiwa Isra’ Mi’raj dan keterkaitannya.</li><li>Peran ulama dalam menjaga warisan ilmu.</li></ul>', NULL, NULL, 0, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(4, 1, 'Kuis Modul 1: Masjidil Aqsa', 'kuis-modul-1-masjidil-aqsa', 4, 'quiz', NULL, NULL, NULL, NULL, '<p>Jawablah pertanyaan berikut sesuai materi. KKM 75.</p>', 15, 75, 0, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(5, 2, 'Niat, Rukun, dan Sunnah Wudhu', 'niat-rukun-sunnah-wudhu', 1, 'video', 'external', NULL, 'https://youtu.be/3uQy7yWudhuX', NULL, '<p>Pembahasan ringkas tentang niat, rukun, dan sunnah wudhu.</p>', NULL, NULL, 0, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(6, 2, 'Panduan Wudhu (PDF)', 'panduan-wudhu-pdf', 2, 'document', 'upload', '/uploads/modules/panduan_wudhu.pdf', NULL, NULL, '<p>Ringkasan langkah wudhu dan hal yang membatalkannya.</p>', NULL, NULL, 0, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(7, 3, 'Syarat & Rukun Shalat', 'syarat-dan-rukun-shalat', 1, 'text', NULL, NULL, NULL, NULL, '<h2>Syarat Shalat</h2><p>Menutup aurat, suci dari hadats dan najis, menghadap kiblat, masuk waktu.</p><h2>Rukun Shalat</h2><p>Niat, takbiratul ihram, berdiri bagi yang mampu, ruku, sujud, tasyahud akhir, salam.</p>', NULL, NULL, 0, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(8, 3, 'Kuis: Fiqih Shalat Dasar', 'kuis-fiqih-shalat-dasar', 2, 'quiz', NULL, NULL, NULL, NULL, '<p>Kuis singkat untuk menguatkan pemahaman.</p>', 10, 70, 0, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(9, 4, 'Kejujuran dalam Islam', 'kejujuran-dalam-islam', 1, 'video', 'external', NULL, 'https://youtu.be/4AkhlakJujurX', NULL, '<p>Pembahasan tentang nilai kejujuran dan dampaknya dalam kehidupan.</p>', NULL, NULL, 0, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(10, 4, 'Ringkasan Materi Akhlak (PDF)', 'ringkasan-materi-akhlak-pdf', 2, 'document', 'upload', '/uploads/modules/ringkasan_akhlak.pdf', NULL, NULL, '<p>Ringkasan poin penting tentang akhlak dalam Islam.</p>', NULL, NULL, 0, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(11, 5, 'Adab Menuntut Ilmu', 'adab-menuntut-ilmu', 1, 'text', NULL, NULL, NULL, NULL, '<h2>Adab Menuntut Ilmu</h2><ol><li>Ikhlas karena Allah</li><li>Rendah hati</li><li>Menjaga adab kepada guru</li><li>Istiqamah dan sabar</li></ol>', NULL, NULL, 0, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(12, 5, 'Kuis: Akhlak & Adab', 'kuis-akhlak-adab', 2, 'quiz', NULL, NULL, NULL, NULL, '<p>Kuis singkat seputar akhlak dan adab.</p>', 10, 70, 0, '2026-02-12 09:01:45', '2026-02-12 09:01:45');

-- Dumping structure for table saladdin_db.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.migrations: ~12 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_01_03_092645_create_personal_access_tokens_table', 1),
	(5, '2026_01_04_063438_create_course_content_tables', 1),
	(6, '2026_01_04_064036_create_quiz_tables', 1),
	(7, '2026_01_04_141617_create_student_tables', 1),
	(8, '2026_01_10_152851_add_timestamps_to_quiz_attempts_table', 1),
	(9, '2026_01_10_152925_create_quiz_answers_table', 1),
	(10, '2026_01_18_000001_create_certificates_and_signatures_tables', 1),
	(11, '2026_01_18_011947_add_logo_to_course_certificate_configs_table', 1),
	(12, '2026_01_28_104258_add_sequence_to_questions_table', 1);

-- Dumping structure for table saladdin_db.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table saladdin_db.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table saladdin_db.questions
CREATE TABLE IF NOT EXISTS `questions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` bigint unsigned NOT NULL,
  `question_text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `points` int NOT NULL DEFAULT '10',
  `sequence` int NOT NULL DEFAULT '1',
  `explanation` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_lesson_id_foreign` (`lesson_id`),
  KEY `questions_sequence_index` (`sequence`),
  CONSTRAINT `questions_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.questions: ~3 rows (approximately)
INSERT INTO `questions` (`id`, `lesson_id`, `question_text`, `points`, `sequence`, `explanation`, `created_at`, `updated_at`) VALUES
	(1, 4, 'Masjid yang termasuk dalam tiga masjid yang dianjurkan untuk dikunjungi adalah...', 10, 1, NULL, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(2, 8, 'Berikut ini yang termasuk rukun shalat adalah...', 10, 1, NULL, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(3, 12, 'Adab yang paling utama ketika menuntut ilmu adalah...', 10, 1, NULL, '2026-02-12 09:01:45', '2026-02-12 09:01:45');

-- Dumping structure for table saladdin_db.question_options
CREATE TABLE IF NOT EXISTS `question_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question_id` bigint unsigned NOT NULL,
  `option_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_options_question_id_foreign` (`question_id`),
  CONSTRAINT `question_options_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.question_options: ~10 rows (approximately)
INSERT INTO `question_options` (`id`, `question_id`, `option_text`, `is_correct`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Masjid Nabawi', 1, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(2, 1, 'Masjid Quba', 0, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(3, 1, 'Masjid Raya setempat', 0, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(4, 1, 'Semua masjid sama persis keutamaannya', 0, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(5, 2, 'Takbiratul ihram', 1, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(6, 2, 'Membaca doa setelah shalat', 0, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(7, 2, 'Bersiwak sebelum shalat', 0, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(8, 3, 'Ikhlas karena Allah', 1, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(9, 3, 'Pamer ilmu di media sosial', 0, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(10, 3, 'Merendahkan guru', 0, '2026-02-12 09:01:45', '2026-02-12 09:01:45');

-- Dumping structure for table saladdin_db.quiz_answers
CREATE TABLE IF NOT EXISTS `quiz_answers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `quiz_attempt_id` bigint unsigned NOT NULL,
  `question_id` bigint unsigned NOT NULL,
  `selected_option_id` bigint unsigned DEFAULT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  `earned_points` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `quiz_answers_quiz_attempt_id_question_id_unique` (`quiz_attempt_id`,`question_id`),
  KEY `quiz_answers_question_id_foreign` (`question_id`),
  KEY `quiz_answers_selected_option_id_foreign` (`selected_option_id`),
  CONSTRAINT `quiz_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quiz_answers_quiz_attempt_id_foreign` FOREIGN KEY (`quiz_attempt_id`) REFERENCES `quiz_attempts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quiz_answers_selected_option_id_foreign` FOREIGN KEY (`selected_option_id`) REFERENCES `question_options` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.quiz_answers: ~0 rows (approximately)

-- Dumping structure for table saladdin_db.quiz_attempts
CREATE TABLE IF NOT EXISTS `quiz_attempts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `lesson_id` bigint unsigned NOT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `passed` tinyint(1) NOT NULL DEFAULT '0',
  `total_questions` int DEFAULT NULL,
  `correct_answers` int DEFAULT NULL,
  `wrong_answers` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quiz_attempts_user_id_foreign` (`user_id`),
  KEY `quiz_attempts_lesson_id_foreign` (`lesson_id`),
  CONSTRAINT `quiz_attempts_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quiz_attempts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.quiz_attempts: ~0 rows (approximately)

-- Dumping structure for table saladdin_db.sections
CREATE TABLE IF NOT EXISTS `sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sections_course_id_foreign` (`course_id`),
  CONSTRAINT `sections_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.sections: ~5 rows (approximately)
INSERT INTO `sections` (`id`, `course_id`, `title`, `sort_order`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Modul 1: Kedudukan & Sejarah Singkat', 1, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(2, 2, 'Modul 1: Wudhu', 1, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(3, 2, 'Modul 2: Shalat', 2, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(4, 3, 'Modul 1: Akhlak', 1, '2026-02-12 09:01:45', '2026-02-12 09:01:45'),
	(5, 3, 'Modul 2: Adab', 2, '2026-02-12 09:01:45', '2026-02-12 09:01:45');

-- Dumping structure for table saladdin_db.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.sessions: ~0 rows (approximately)

-- Dumping structure for table saladdin_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','student') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'student',
  `bio` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_google_id_unique` (`google_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table saladdin_db.users: ~5 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `google_id`, `avatar`, `role`, `bio`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin LMS', 'admin@lms.com', '2026-02-12 09:01:43', '$2y$12$hnlqXcI.acF1k7I0RukfJO58.635UnRgEh4SN0yJnA.3JVX1cz.1W', NULL, NULL, 'admin', NULL, NULL, '2026-02-12 09:01:43', '2026-02-12 09:01:43'),
	(2, 'Admin Saladdin', 'admin@saladdin.com', '2026-02-12 09:01:43', '$2y$12$nmKok8BvEPweQKrP2gxhIupWLQwR4F5BvE6urq2ye6qcmRZ4jc3Ee', NULL, NULL, 'admin', NULL, NULL, '2026-02-12 09:01:43', '2026-02-12 09:01:43'),
	(3, 'John Doe', 'john@student.com', '2026-02-12 09:01:44', '$2y$12$niBxH/1nWE7IDBtkJzVN.eBa8t9y8gjBgTZz04wNYB3QYF7LLm82q', NULL, NULL, 'student', NULL, NULL, '2026-02-12 09:01:44', '2026-02-12 09:01:44'),
	(4, 'Jane Smith', 'jane@student.com', '2026-02-12 09:01:44', '$2y$12$UuqdpsaNJTaM5vCpLDBrxe9oucJiWsGE6C21QR4.Mg7W/O57z2JTC', NULL, NULL, 'student', NULL, NULL, '2026-02-12 09:01:44', '2026-02-12 09:01:44'),
	(5, 'Student Saladdin', 'student@saladdin.com', '2026-02-12 09:01:45', '$2y$12$iq5hkgw0S1zIwlfP3eMxX.LnBKp2XRn1WtbTLEiD5qc0xJbs.gBi6', NULL, NULL, 'student', NULL, NULL, '2026-02-12 09:01:45', '2026-02-12 09:01:45');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
