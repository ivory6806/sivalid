-- Adminer 4.8.1 MySQL 8.0.27 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('sivalid-cache-baak@polteklpp.ac.idpassword|127.0.0.1:timer',	'i:1786770726;',	1786770726),
('sivalid-cache-baak@polteklpp.ac.idpassword|127.0.0.1',	'i:1;',	1786770726),
('sivalid-cache-nnh@polteklpp.ac.id|127.0.0.1:timer',	'i:1787042421;',	1787042421),
('sivalid-cache-nnh@polteklpp.ac.id|127.0.0.1',	'i:3;',	1787042421);

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `mahasiswa`;
CREATE TABLE `mahasiswa` (
  `id` bigint unsigned NOT NULL,
  `nim` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prodi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_ajaran` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_ayah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_ibu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_orangtua` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(6,	'2026_05_27_041812_create_mahasiswa_table',	2),
(8,	'0001_01_01_000000_create_users_table',	3),
(9,	'0001_01_01_000001_create_cache_table',	3),
(10,	'0001_01_01_000002_create_jobs_table',	3),
(11,	'2026_05_26_151231_create_surats_table',	3),
(12,	'2026_05_26_151421_add_columns_to_users_table',	3),
(13,	'2026_05_27_043914_create_settings_table',	3),
(16,	'2026_07_15_102627_create_template_elements_table',	6),
(20,	'2026_07_15_103910_create_sertifikat_batches_table',	7),
(21,	'2026_08_12_111721_create_sertifikat_tables',	8),
(22,	'2026_08_14_153702_create_units_tables',	9),
(23,	'2026_08_14_153716_create_unit_user_tables',	9),
(24,	'2026_08_14_153750_add_is_superadmin_to_users_tables',	9),
(25,	'2026_08_14_153812_seed_default_akademik_unit_tables',	9),
(26,	'2026_08_14_185640_add_unit_and_qr_fields_to_surats_tables',	10),
(27,	'2026_08_15_055324_add_tanggal_surat_to_surats_tables',	11),
(28,	'2026_08_15_055431_change_nomor_surat_unique_per_unit',	12),
(29,	'2026_08_15_140837_make_akademik_fields_nullable_on_surats_table',	13),
(30,	'2026_08_16_114348_add_jabatan_to_unit_user_tables',	14);

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('ivoryalvadiaskurniawan@gmail.com',	'$2y$12$u3l/ON0iWEPeXuya8/zhiO3f9MBZ8AjtJibZTZkIvsglIf7x7vHom',	'2026-05-28 00:51:55'),
('ivorykurniawan06@gmail.com',	'$2y$12$VHRwPcqIx0wGYrf2WzCcieYHY.QjL09MB3XbET3OZUcTADai/bl6O',	'2026-05-27 18:40:09'),
('vry@polteklpp.ac.id',	'$2y$12$PqkNLcBPpOAEijrDqeWmtenK0lRk373u9XAnQcqTOHSqFDVHfklhe',	'2026-08-16 02:23:32');

DROP TABLE IF EXISTS `sertifikat_batches`;
CREATE TABLE `sertifikat_batches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kegiatan` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penyelenggara` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `judul` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `status` enum('draft','submitted','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `qr_position` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sertifikat_batches_template_id_foreign` (`template_id`),
  KEY `sertifikat_batches_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sertifikat_batches` (`id`, `kegiatan`, `penyelenggara`, `tempat`, `tanggal_mulai`, `tanggal_selesai`, `judul`, `template_id`, `user_id`, `status`, `catatan`, `qr_position`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1,	'Sertifikat Bintalfisdis',	'Politeknik LPP Yogyakarta',	NULL,	'2026-05-20',	'2026-05-22',	NULL,	1,	6,	'approved',	NULL,	NULL,	'2026-08-04 10:16:47',	'2026-07-26 16:56:31',	'2026-08-04 10:16:47'),
(2,	'Sertifikat Bintalfisdis',	'Politeknik LPP Yogyakarta',	NULL,	'2026-05-20',	'2026-05-22',	NULL,	1,	6,	'approved',	NULL,	NULL,	'2026-08-04 10:16:43',	'2026-07-26 17:41:55',	'2026-08-04 10:16:43');

DROP TABLE IF EXISTS `sertifikat_templates`;
CREATE TABLE `sertifikat_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `background_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` int unsigned NOT NULL DEFAULT '0',
  `height` int unsigned NOT NULL DEFAULT '0',
  `elements` json DEFAULT NULL,
  `qr_position` json DEFAULT NULL,
  `nomor_format` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SRT/{year}/{month}/{seq}',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sertifikat_templates` (`id`, `name`, `background_path`, `width`, `height`, `elements`, `qr_position`, `nomor_format`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1,	'Sertifikat Bintalfisdis 2025',	'templates/DLm6L6bMhuxTD4eOvavvEHQtAkdlZsgAQR17p1cz.png',	2000,	1414,	'[{\"x\": 900, \"y\": 707, \"id\": \"el_1785083427634\", \"tag\": \"{{nama}}\", \"type\": \"text\", \"color\": \"#1ca307\", \"content\": \"Teks Baru\", \"fontSize\": 24, \"rotation\": 0, \"fontStyle\": \"normal\", \"fontFamily\": \"serif\", \"fontWeight\": \"bold\"}]',	'{\"h\": 100, \"w\": 100, \"x\": 100, \"y\": 100}',	'SRT/{year}/{month}/{seq}',	NULL,	'2026-07-26 16:25:26',	'2026-07-26 16:30:56');

DROP TABLE IF EXISTS `sertifikats`;
CREATE TABLE `sertifikats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uploaded_by` bigint unsigned NOT NULL,
  `jenis_sertifikat` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_sertifikat` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_asli` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_disahkan` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_original_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` bigint unsigned DEFAULT NULL,
  `jumlah_halaman` int unsigned DEFAULT NULL,
  `status` enum('diajukan','disetujui','direvisi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'diajukan',
  `catatan_revisi` text COLLATE utf8mb4_unicode_ci,
  `qr_pos_x` decimal(5,2) DEFAULT NULL,
  `qr_pos_y` decimal(5,2) DEFAULT NULL,
  `qr_size` decimal(5,2) DEFAULT NULL,
  `validated_by` bigint unsigned DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `qr_token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qr_generated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sertifikats_nomor_sertifikat_unique` (`nomor_sertifikat`),
  UNIQUE KEY `sertifikats_qr_token_unique` (`qr_token`),
  KEY `sertifikats_validated_by_foreign` (`validated_by`),
  KEY `sertifikats_status_index` (`status`),
  KEY `sertifikats_uploaded_by_index` (`uploaded_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sertifikats` (`id`, `uploaded_by`, `jenis_sertifikat`, `nomor_sertifikat`, `file_asli`, `file_disahkan`, `file_original_name`, `file_type`, `file_size`, `jumlah_halaman`, `status`, `catatan_revisi`, `qr_pos_x`, `qr_pos_y`, `qr_size`, `validated_by`, `validated_at`, `submitted_at`, `qr_token`, `qr_generated_at`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1,	6,	'Sertifikat Bintalfisdis Angkatan 2025',	'153/Sket/AK/25.2/2026',	'1786513902_b4hdiF_Sertifikat_Bintalfisdis_2026.pdf',	NULL,	'Sertifikat Bintalfisdis 2026.pdf',	'application/pdf',	148356,	NULL,	'diajukan',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'2026-08-12 05:51:44',	'9Pdj5L231VBGiEubG4Yaz8Mb2Tqjpzx0nBnYbfff4sABOEiuD3EMOtnw9zmpxVdv',	NULL,	'2026-08-13 01:42:48',	'2026-08-12 05:51:44',	'2026-08-13 01:42:48'),
(2,	6,	'Sertifikat Bintalfisdis Angkatan 2025',	'154/Sket/AK/25.2/2026',	'1786519059_T34nz6_Sertifikat_Bintalfisdis_2026.png',	'disahkan_154-Sket-AK-25-2-2026_1786519094.pdf',	'Sertifikat Bintalfisdis 2026.png',	'image/png',	1250051,	NULL,	'disetujui',	NULL,	78.50,	71.25,	11.00,	7,	'2026-08-12 07:18:14',	'2026-08-12 07:17:39',	'VUW9CSVc2JIYDsCnCpKAsEUyTRYAMJKc3owhSCvdeRe36AmCRHSrS3TUNazba3tH',	'2026-08-12 07:18:14',	'2026-08-13 01:42:52',	'2026-08-12 07:17:39',	'2026-08-13 01:42:52'),
(3,	6,	'Sertifikat Bintalfisdis Angkatan 2025',	'155/Sket/AK/25.2/2026',	'1786522735_v4mVif_Sertifikat_Bintalfisdis_2026.png',	'disahkan_155-Sket-AK-25-2-2026_1786522754.pdf',	'Sertifikat Bintalfisdis 2026.png',	'image/png',	1250051,	NULL,	'disetujui',	NULL,	79.29,	73.63,	10.00,	7,	'2026-08-12 08:19:14',	'2026-08-12 08:18:55',	'9EpjZO9sfcVsIoRdDrYPJBSGuQ1wQRGLqZ1y2xadmNIXtsrOUb0LapBsC0CsMeSe',	'2026-08-12 08:19:14',	'2026-08-16 10:13:17',	'2026-08-12 08:18:55',	'2026-08-16 10:13:17'),
(4,	6,	'Sertifikat Bintalfisdis Angkatan 2025',	'156/Sket/AK/25.2/2026',	'1786633174_I26xsK_Sertifikat_Bintalfisdis_2026.png',	'disahkan_156-Sket-AK-25-2-2026_1786633208.pdf',	'Sertifikat Bintalfisdis 2026.png',	'image/png',	1250039,	NULL,	'disetujui',	NULL,	11.16,	71.00,	11.00,	7,	'2026-08-13 15:00:08',	'2026-08-13 14:59:34',	'yUoZXM9s6VHhSZRQt6adhCdDIIr4Po9fS13e7G9A2392v2FAzLGjJaVlGNPwiDQ2',	'2026-08-13 15:00:08',	'2026-08-16 10:13:21',	'2026-08-13 14:59:34',	'2026-08-16 10:13:21'),
(5,	6,	'Sertifikat Bintalfisdis Angkatan 2025',	'157/Sket/AK/25.2/2026',	'1786658507_mzJhCp_Sertifikat_Bintalfisdis_2026.png',	'disahkan_157-Sket-AK-25-2-2026_1786658529.pdf',	'Sertifikat Bintalfisdis 2026.png',	'image/png',	1250039,	NULL,	'disetujui',	NULL,	80.94,	11.51,	9.50,	7,	'2026-08-13 22:02:09',	'2026-08-13 22:01:47',	'QwQ7TWqAcDYYG4AccREEc9JCACbvxiKVfbELMugvd9NbtY94PSWk7Lo5QFh5s5nw',	'2026-08-13 22:02:09',	'2026-08-16 10:13:24',	'2026-08-13 21:57:54',	'2026-08-16 10:13:24'),
(6,	6,	'Sertifikat Bintalfisdis Angkatan 2025',	'158/Sket/AK/25.2/2026',	'1786661195_txCat9_sertifikat-bintalfisdis-2026.png',	'disahkan_158-Sket-AK-25-2-2026_1786676514.png',	'Sertifikat Bintalfisdis 2026.png',	'image/png',	1353674,	1,	'disetujui',	NULL,	9.12,	11.03,	11.00,	7,	'2026-08-14 03:01:55',	'2026-08-13 22:46:35',	'bOVUBSNKvxVeHL3iCV2idc6ZtKSNOaaiAyqkiXqy57ulR3P0lKv5Jng30pbqQEQt',	'2026-08-14 03:01:55',	NULL,	'2026-08-13 22:46:35',	'2026-08-14 03:01:55');

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(14,	'master_prodi',	'D2 - Perawatan Mesin Industri Perkebunan',	'2026-06-07 23:16:12',	'2026-06-07 23:16:12'),
(15,	'master_prodi',	'D3 - Akuntansi',	'2026-06-07 23:16:13',	'2026-06-07 23:16:13'),
(16,	'master_prodi',	'D3 - Budidaya Tanaman Perkebunan',	'2026-06-07 23:16:13',	'2026-06-07 23:16:13'),
(17,	'master_prodi',	'D4 - Pengelolaan Perkebunan',	'2026-06-07 23:16:13',	'2026-06-07 23:16:13'),
(18,	'master_prodi',	'D4 - Teknologi Rekayasa Kimia Industri',	'2026-06-07 23:16:13',	'2026-06-07 23:16:13'),
(19,	'master_prodi',	'D4 - Teknologi Rekayasa Mesin Industri Perkebunan',	'2026-06-07 23:16:13',	'2026-06-07 23:16:13'),
(20,	'master_semester',	'1',	'2026-06-07 23:16:13',	'2026-06-07 23:16:13'),
(21,	'master_semester',	'2',	'2026-06-07 23:16:13',	'2026-06-07 23:16:13'),
(22,	'master_semester',	'3',	'2026-06-07 23:16:13',	'2026-06-07 23:16:13'),
(23,	'master_semester',	'4',	'2026-06-07 23:16:13',	'2026-06-07 23:16:13'),
(24,	'master_semester',	'5',	'2026-06-07 23:16:13',	'2026-06-07 23:16:13'),
(25,	'master_semester',	'6',	'2026-06-07 23:16:13',	'2026-06-07 23:16:13'),
(26,	'master_semester',	'7',	'2026-06-07 23:16:13',	'2026-06-07 23:16:13'),
(27,	'master_semester',	'8',	'2026-06-07 23:16:13',	'2026-06-07 23:16:13'),
(30,	'master_tahun_ajaran',	'20251 Ganjil',	'2026-06-07 23:16:13',	'2026-06-07 23:16:13'),
(31,	'master_tahun_ajaran',	'20252 Genap',	'2026-06-07 23:16:13',	'2026-06-07 23:16:13'),
(34,	'master_tahun_ajaran',	'20261 Ganjil',	'2026-06-07 23:32:04',	'2026-06-07 23:32:04'),
(35,	'master_tahun_ajaran',	'20262 Genap',	'2026-06-11 05:35:45',	'2026-06-11 05:35:45');

DROP TABLE IF EXISTS `surat_activities`;
CREATE TABLE `surat_activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `surat_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `organizational_unit_id` bigint unsigned DEFAULT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `surat_activities_surat_id_index` (`surat_id`),
  KEY `surat_activities_user_id_index` (`user_id`),
  KEY `surat_activities_action_index` (`action`),
  KEY `surat_activities_created_at_index` (`created_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `surats`;
CREATE TABLE `surats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `unit_id` bigint unsigned DEFAULT NULL,
  `nama_surat` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_surat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_surat` datetime DEFAULT NULL,
  `nama_mahasiswa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nim` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prodi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `semester` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_ajaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_ayah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_ibu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_orangtua` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `file_surat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_halaman` int unsigned DEFAULT NULL,
  `file_disahkan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('diajukan','disetujui','direvisi') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'diajukan',
  `catatan_revisi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `validated_by` bigint unsigned DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `qr_token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qr_page` int unsigned DEFAULT NULL,
  `qr_pos_x` decimal(5,2) DEFAULT NULL,
  `qr_pos_y` decimal(5,2) DEFAULT NULL,
  `qr_size_mm` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `surats_unit_nomor_unique` (`unit_id`,`nomor_surat`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `surats` (`id`, `user_id`, `unit_id`, `nama_surat`, `nomor_surat`, `tanggal_surat`, `nama_mahasiswa`, `nim`, `prodi`, `semester`, `tahun_ajaran`, `nama_ayah`, `nama_ibu`, `pekerjaan`, `alamat_orangtua`, `file_surat`, `total_halaman`, `file_disahkan`, `status`, `catatan_revisi`, `validated_by`, `validated_at`, `qr_token`, `qr_page`, `qr_pos_x`, `qr_pos_y`, `qr_size_mm`, `created_at`, `updated_at`) VALUES
(11,	6,	1,	'Surat Keterangan Aktif Kuliah',	'081/Sket/AK/20252/2026',	NULL,	'MUHAMMAD ARFAN RITONGA',	'2305060',	'D4 - Pengelolaan Perkebunan',	'6',	'20252 Genap',	'081/Sket/AK/20252/2026',	'',	'TNI',	'JL.SUMPAH PRAJURIT BARAT NO.42',	'1780043880_ARFAN_(1).pdf',	NULL,	'disahkan_081-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-05-29 09:09:21',	'oELP99Fid9uVOqWwJCqpqIcqgmLpbtzsudy9oN270gFieQn3z6fe2UQIZ6Ys5Pow',	NULL,	NULL,	NULL,	NULL,	'2026-05-29 08:38:00',	'2026-05-29 09:09:21'),
(13,	6,	1,	'Surat Keterangan Aktif Kuliah',	'084/Sket/AK/20252/2026',	NULL,	'DHEA AULIA SAFITRI',	'2201022',	'D4 - Teknologi Rekayasa Kimia Industri',	'6',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780383585_DHEA.pdf',	NULL,	'disahkan_084-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-02 07:08:44',	'7p2hHxPVftlGZysygwSuQZ943eF2cnNJdSRxIKkvUOjKUVgVtqaWxxoxoNiYYT3g',	NULL,	NULL,	NULL,	NULL,	'2026-06-02 06:59:45',	'2026-06-02 07:08:44'),
(15,	6,	1,	'Surat Keterangan Aktif Kuliah',	'085/Sket/AK/20252/2026',	NULL,	'AJIB FARHAN ABDILLAH',	'2404018',	'D3 - Budidaya Tanaman Perkebunan',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780475687_AJIB.pdf',	NULL,	'disahkan_084-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-03 08:47:55',	'gSTYQ1HwXQ1YugncYohdYkmfohZYahf1PQmhanDboSh2rprXKlLKgKhYY4eBdBsF',	NULL,	NULL,	NULL,	NULL,	'2026-06-03 08:34:47',	'2026-06-03 08:47:55'),
(16,	6,	1,	'Surat Keterangan Aktif Kuliah',	'086/Sket/AK/20252/2026',	NULL,	'NUR INDAH SUCIATI',	'2405141',	'D4 - Pengelolaan Perkebunan',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780645945_NUR_INDAH.pdf',	NULL,	'disahkan_086-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-05 09:47:22',	'vt7E9FCXLzYyYwtLRO8djnVpTXVDYd705IGMhkFxYWZTTUBfqMG5pDX40rXy8qsJ',	NULL,	NULL,	NULL,	NULL,	'2026-06-05 07:52:25',	'2026-06-05 09:47:22'),
(17,	6,	1,	'Surat Keterangan Aktif Kuliah',	'087/Sket/AK/20252/2026',	NULL,	'NAFA MEI LUIS PURBA',	'2508007',	'D4 - Teknologi Rekayasa Kimia Industri',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780646034_NAFA.pdf',	NULL,	'disahkan_087-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-05 09:47:05',	'ZRBgts1k2F987jePBKZEl2EHRvFVUbxF8MnMf3FIDknq3nlTlIKLNpEdGqjEHAwa',	NULL,	NULL,	NULL,	NULL,	'2026-06-05 07:53:54',	'2026-06-05 09:47:05'),
(19,	6,	1,	'Surat Keterangan Aktif Kuliah',	'088/Sket/AK/20252/2026',	NULL,	'ADLYL ADNAN GYMNASTIAR',	'2407016',	'D4 - Teknologi Rekayasa Mesin Industri Perkebunan',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780882290_ADLYL.pdf',	NULL,	'disahkan_088-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 02:38:36',	'uV0b6y742x6txZX2Lcfss6be36cAi8kASLv2eWSDhtk55aIaeLCkeCuuOBXB79kF',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 01:31:30',	'2026-06-08 02:38:36'),
(20,	6,	1,	'Surat Keterangan Aktif Kuliah',	'090/Sket/AK/20252/2026',	NULL,	'EDELWEIS RANUPANE',	'2301046',	'D4 - Teknologi Rekayasa Kimia Industri',	'6',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780887049_EDELWIS.pdf',	NULL,	'disahkan_090-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 02:52:49',	'6tOUcEd5GK5ekRr5eWHpvicFKaedjNqRlbsWV7pHY0MG5GsWqNHhRUBq2zDQEBAv',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 02:50:49',	'2026-06-08 02:52:49'),
(21,	6,	1,	'Surat Keterangan Aktif Kuliah',	'089/Sket/AK/20252/2026',	NULL,	'AULIA RAHMADHINI',	'2301001',	'D4 - Teknologi Rekayasa Kimia Industri',	'6',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780887152_AULIA.pdf',	NULL,	'disahkan_089-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 02:53:33',	'gLkQ3K1D5ywY97BF6eemwtTjJXNqkJt7SEJgkli4fMdCf2otZV0DRCAVxJ4Vo1Rf',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 02:52:32',	'2026-06-08 02:53:33'),
(22,	6,	1,	'Surat Keterangan Aktif Kuliah',	'091/Sket/AK/20252/2026',	NULL,	'KURNIA HERAWATI',	'2305055',	'D4 - Pengelolaan Perkebunan',	'6',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780887232_KURNIA_G.pdf',	NULL,	'disahkan_091-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 04:39:57',	'yTqRpyIBJeiRpiRFExYkIXO4b0aUHT4ZPwzsmEOzXheB2i7JueChTNPsRzwsPzYV',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 02:53:52',	'2026-06-08 04:39:57'),
(23,	6,	1,	'Surat Keterangan Aktif Kuliah',	'092/Sket/AK/20252/2026',	NULL,	'DANIEL INDRA SETIAWAN',	'2508002',	'D4 - Teknologi Rekayasa Kimia Industri',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780887314_DANIEL.pdf',	NULL,	'disahkan_092-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 04:40:09',	'Uu8uDTpjbeRMOcqsDByTRwgS4LFKE4Xa6xIE9XMKBzl0UDvu84aY7O8eE6hya8Zo',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 02:55:14',	'2026-06-08 04:40:09'),
(24,	6,	1,	'Surat Keterangan Aktif Kuliah',	'093/Sket/AK/20252/2026',	NULL,	'NURNAZILAH BR PANJAITAN',	'2504069',	'D3 - Budidaya Tanaman Perkebunan',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780887392_NURNAZILAH.pdf',	NULL,	'disahkan_093-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 04:40:18',	'qUOTgJ5WtK3M0jNSO2gl6FoCdnn9amSXIGJf8quHryK2dT75IVJNLjzFsnmTc6N9',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 02:56:32',	'2026-06-08 04:40:18'),
(25,	6,	1,	'Surat Keterangan Aktif Kuliah',	'094/Sket/AK/20252/2026',	NULL,	'FAATHIR AL IKHSAN',	'2508017',	'D4 - Teknologi Rekayasa Kimia Industri',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780887473_FAATHIR_2.pdf',	NULL,	'disahkan_094-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 04:40:27',	'haUo21vQupyoYnK0sI6LQrCb9RdwywKeCIYwJHksuNL6SZE6qWuEWbPggsC6Lddg',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 02:57:53',	'2026-06-08 04:40:27'),
(26,	6,	1,	'Surat Keterangan Aktif Kuliah',	'095/Sket/AK/20252/2026',	NULL,	'MUHAMMAD NAZWA ZULFAH LUBIS',	'2304027',	'D3 - Budidaya Tanaman Perkebunan',	'6',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780887623_NAZWA_ZULFA.pdf',	NULL,	'disahkan_095-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 04:40:35',	'HxX3nsi5dpwkpYVYU187bYlkIKw8akT3B4566jWEF3GEHcD0CaYZ3ziaCKXljyUt',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 03:00:23',	'2026-06-08 04:40:35'),
(27,	6,	1,	'Surat Keterangan Aktif Kuliah',	'096/Sket/AK/20252/2026',	NULL,	'DWI ANDINI',	'2301021',	'D4 - Teknologi Rekayasa Kimia Industri',	'6',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780887709_DWI_ANDINI.pdf',	NULL,	'disahkan_096-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 04:40:43',	'JbWBBphunNjBSGYyIYjqGkEJNw6CtOs5659G1ZK2mRVwLHMD7HcovNeRCNqnUjGn',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 03:01:49',	'2026-06-08 04:40:43'),
(28,	6,	1,	'Surat Keterangan Aktif Kuliah',	'097/Sket/AK/20252/2026',	NULL,	'ATIFHA AYULIZANY',	'2405012',	'D4 - Pengelolaan Perkebunan',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780888146_ATIFHA.pdf',	NULL,	'disahkan_097-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 04:40:51',	'fUIXy0J72AsFxxlfUFKTWctzNEZX4NU8nsE14Jr6D5KD6l9NfuVhkTgYEh7y5Ia9',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 03:09:06',	'2026-06-08 04:40:51'),
(29,	6,	1,	'Surat Keterangan Aktif Kuliah',	'098/Sket/AK/20252/2026',	NULL,	'UMMU ATIA SALAMAH',	'2301015',	'D4 - Teknologi Rekayasa Kimia Industri',	'6',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780888544_UMMU_ATIA.pdf',	NULL,	'disahkan_098-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 04:41:00',	'6ZcNa2CbAH9kvtGViKP91dSCmFxj66amj2DS7PNjDNYyzniYJN4DrSLb0S3iMhKI',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 03:15:44',	'2026-06-08 04:41:00'),
(30,	6,	1,	'Surat Keterangan Aktif Kuliah',	'099/Sket/AK/20252/2026',	NULL,	'DIMAS SUPANDI',	'2508016',	'D4 - Teknologi Rekayasa Kimia Industri',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780891854_DIMAS_SUPANDI.pdf',	NULL,	'disahkan_099-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 04:41:17',	'NzYvcTWgyIfRPSlSivJjY9SAtl3MfUwmzX7b4csFUsdsHEGgjQzdrKzX02ag9Rxj',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 04:10:54',	'2026-06-08 04:41:17'),
(31,	6,	1,	'Surat Keterangan Aktif Kuliah',	'100 /Sket/AK/20252/2026',	NULL,	'EKA NUR LAELA MAR\'ATUS SHOLIKHA',	'2303002',	'D3 - Akuntansi',	'6',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780897214_EKA_NUR_LAELA.pdf',	NULL,	'disahkan_100--Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 06:22:29',	'tHS0Ep6xIvwFh8Kd8LUikLEQGDsJl2SOPeLBbh5GWsA65nOyhRFts18hzF7SofPK',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 05:40:14',	'2026-06-08 06:22:29'),
(32,	6,	1,	'Surat Keterangan Aktif Kuliah',	'101/Sket/AK/20252/2026',	NULL,	'FALINTINO ROZI',	'2205030',	'D4 - Pengelolaan Perkebunan',	'8',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780904524_FALENTINO.pdf',	NULL,	'disahkan_101-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 10:04:34',	'HhpmL837vniwZyswdvgBv8TUladZMVT6CjPxCy051ZYsc0XeP1GjtY5rrw2bEm5g',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 07:42:04',	'2026-06-08 10:04:34'),
(33,	6,	1,	'Surat Keterangan Aktif Kuliah',	'102/Sket/AK/20252/2026',	NULL,	'NANDITA NUR AYU FADILA',	'2303027',	'D3 - Akuntansi',	'6',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780904832_NANDITA.pdf',	NULL,	'disahkan_102-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 10:04:25',	'V1Js7J6nuHev8qMqixOvX2UTRQQKNkYMVd54NkIlS9s7JlULPkixqWEhDDiApzkR',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 07:47:12',	'2026-06-08 10:04:25'),
(34,	6,	1,	'Surat Keterangan Aktif Kuliah',	'103/Sket/AK/20252/2026',	NULL,	'APRILIANA',	'APRILIANA',	'D3 - Budidaya Tanaman Perkebunan',	'6',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780908424_APRILIA.pdf',	NULL,	'disahkan_103-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 10:04:16',	'MDUUSpONoPUGdqOt0t4AMm25vTrcupeAWYhD3YbjyjXrygGekRzENhnmSUCOa9nc',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 08:47:04',	'2026-06-08 10:04:16'),
(35,	6,	1,	'Surat Keterangan Aktif Kuliah',	'104/Sket/AK/20252/2026',	NULL,	'FANY FADHILAH HSB',	'2301024',	'D4 - Teknologi Rekayasa Kimia Industri',	'6',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780908962_FANY_FADHILAH.pdf',	NULL,	'disahkan_104-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 10:03:03',	'ZUJFbNSROtHABVL8AJydrVzZ7uU4TGff3ZGxc0YWU1sFdoqW0rQHi1uNOVJgppg8',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 08:56:02',	'2026-06-08 10:03:03'),
(36,	6,	1,	'Surat Keterangan Aktif Kuliah',	'105/Sket/AK/20252/2026',	NULL,	'ALISSA NAYLA',	'2408019',	'D4 - Teknologi Rekayasa Kimia Industri',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780909450_ALISSA_NAYLA.pdf',	NULL,	'disahkan_105-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 10:02:32',	'hXUPKXdrzTaPGCp3h54i9GVqewvRJZruRlGJHeKBgPIpBgnAHpcLk5kfb0ZS37dS',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 09:04:10',	'2026-06-08 10:02:32'),
(37,	6,	1,	'Surat Keterangan Aktif Kuliah',	'106/Sket/AK/20252/2026',	NULL,	'RUBEN SITINJAK',	'2307083',	'D4 - Teknologi Rekayasa Mesin Industri Perkebunan',	'6',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780909814_RUBEN_SITINJAK.pdf',	NULL,	'disahkan_106-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 10:02:22',	'3UCsEYr4QMYvltAKodyZNJrtJsxLQZ7nJSftjejKG8QHWysOWg6r5MH17yWvYEru',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 09:10:14',	'2026-06-08 10:02:22'),
(38,	6,	1,	'Surat Keterangan Aktif Kuliah',	'107/Sket/AK/20252/2026',	NULL,	'NOVALUNA IRLIANTI',	'2408012',	'D4 - Teknologi Rekayasa Kimia Industri',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780910122_NOVALUNA_IRLIANTI.pdf',	NULL,	'disahkan_107-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 10:02:00',	'm11fZ7QQJWOBKa4XjbaCbyJ6dPwkwbwVNmKvgdIpuX6sPl5WeCuX4bF9fxUJlqJ9',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 09:15:22',	'2026-06-08 10:02:00'),
(39,	6,	1,	'Surat Keterangan Aktif Kuliah',	'108/Sket/AK/20252/2026',	NULL,	'MARSAULINA HUTAGALUNG',	'2407064',	'D4 - Teknologi Rekayasa Mesin Industri Perkebunan',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780910514_MARSAULINA_HUTAGALUNG.pdf',	NULL,	'disahkan_108-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 10:01:50',	'ysuQrhMxVP1vYhlaRqXtBGarPWHXKpuCZ9RUlMAvb6tXCDdJiOHSr4vdiTmrt0GS',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 09:21:54',	'2026-06-08 10:01:50'),
(40,	6,	1,	'Surat Keterangan Aktif Kuliah',	'109/Sket/AK/20252/2026',	NULL,	'BIMA TRI ANGGARA',	'2508013',	'D4 - Teknologi Rekayasa Kimia Industri',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780910773_BIMA_TRI_ANGGARA.pdf',	NULL,	'disahkan_109-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 10:01:38',	'RhWzqeVdy2poWh6Sel9HnFkMLbxFnDfcY16fHo3MoSZBdhMAm2Mf36DLcg7kbIYP',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 09:26:13',	'2026-06-08 10:01:38'),
(41,	6,	1,	'Surat Keterangan Aktif Kuliah',	'110/Sket/AK/20252/2026',	NULL,	'YUYUN CHOIRANI NASUTION',	'2205104',	'D4 - Pengelolaan Perkebunan',	'8',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780911133_YUYUN_CHOIRANI.pdf',	NULL,	'disahkan_110-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 10:01:27',	'y3v8bXWYMoEJFyVZJbEsw58egc7gqDMYbO74YpEfDEAUd7RtrrzwefYTtYmvQ199',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 09:32:13',	'2026-06-08 10:01:27'),
(42,	6,	1,	'Surat Keterangan Aktif Kuliah',	'111/Sket/AK/20252/2026',	NULL,	'AGUSTIAWAN',	'2202001',	'D4 - Teknologi Rekayasa Mesin Industri Perkebunan',	'8',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780911547_AGUSTIAWAN.pdf',	NULL,	'disahkan_111-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-08 10:01:04',	'3X9GoKwdPytBJRyUx9s2WAn3iXQJMTYIBFdPQNcHmwuVhpCn2matJYQ1mh39g6eI',	NULL,	NULL,	NULL,	NULL,	'2026-06-08 09:39:07',	'2026-06-08 10:01:04'),
(43,	6,	1,	'Surat Keterangan Aktif Kuliah',	'112/Sket/AK/20252/2026',	NULL,	'YUSNITA AULIA',	'2408047',	'D4 - Teknologi Rekayasa Kimia Industri',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780990085_YUSNITA.pdf',	NULL,	'disahkan_112-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-09 09:51:44',	'T4DhTGccPopt2O2Kb17PGLyXkohLsNcNhPRzpIfxar1sXi8b3wNpRmMADrb8rcSq',	NULL,	NULL,	NULL,	NULL,	'2026-06-09 07:28:05',	'2026-06-09 09:51:44'),
(44,	6,	1,	'Surat Keterangan Aktif Kuliah',	'113/Sket/AK/20252/2026',	NULL,	'NUR KHORIYATUL ULUM',	'2304083',	'D3 - Budidaya Tanaman Perkebunan',	'6',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780990887_NUR_KHORIYATUL.pdf',	NULL,	'disahkan_113-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-09 09:51:35',	'6zOzmgquBBIf1aBtaUUWg223HmfbP5vsVD1QUeSEWTQVRKS446ilFmnGxXSrXJ8Y',	NULL,	NULL,	NULL,	NULL,	'2026-06-09 07:41:27',	'2026-06-09 09:51:35'),
(45,	6,	1,	'Surat Keterangan Aktif Kuliah',	'114/Sket/AK/20252/2026',	NULL,	'RADJA ADJI BAGUS SATRIO',	'2205058',	'D4 - Pengelolaan Perkebunan',	'8',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780990974_RADJA_ADJI.pdf',	NULL,	'disahkan_114-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-09 09:51:25',	'AhloDRxsxIPKjQKFUENjh11zQanF9GG6FGjzmIqnvua2EPDUJyIX7riydGByDL6U',	NULL,	NULL,	NULL,	NULL,	'2026-06-09 07:42:54',	'2026-06-09 09:51:25'),
(46,	6,	1,	'Surat Keterangan Aktif Kuliah',	'115/Sket/AK/20252/2026',	NULL,	'BAGUS PRASETYA',	'2408022',	'D4 - Teknologi Rekayasa Kimia Industri',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780991270_BAGUS_PRASETYA.pdf',	NULL,	'disahkan_115-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-09 09:51:15',	'lt0w0e8r9OB1lRf6H4n0hWbKhEpeWrKDk3Cs6V4QcQNmqAOKspTXWhRcZQbn9wIR',	NULL,	NULL,	NULL,	NULL,	'2026-06-09 07:47:50',	'2026-06-09 09:51:15'),
(48,	6,	1,	'Surat Keterangan Aktif Kuliah',	'116/Sket/AK/20252/2026',	NULL,	'MUHAMMAD YUSUF AKBAR SEPTIAWAN',	'2508071',	'D4 - Teknologi Rekayasa Kimia Industri',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780991544_YUSUF_AKBAR.pdf',	NULL,	'disahkan_116-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-09 09:50:58',	'cZJ8k9czGTmGW7xINE0B7F8eNpv76P5eODPGlxQ5F0F9No6IQBZdta80n0IeMQfh',	NULL,	NULL,	NULL,	NULL,	'2026-06-09 07:52:24',	'2026-06-09 09:50:58'),
(49,	6,	1,	'Surat Keterangan Aktif Kuliah',	'117/Sket/AK/20252/2026',	NULL,	'DIAN ASTUTI RUMAMPUK',	'2408026',	'D4 - Teknologi Rekayasa Kimia Industri',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780991963_DIAN_ASTUTI.pdf',	NULL,	'disahkan_117-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-09 09:50:49',	'ee8Je29PalvPFypcoatmC6IeyuCfSsLyHga7fki8KLqaSKbHIGxpIGFEfKshqeTT',	NULL,	NULL,	NULL,	NULL,	'2026-06-09 07:59:23',	'2026-06-09 09:50:49'),
(50,	6,	1,	'Surat Keterangan Aktif Kuliah',	'118/Sket/AK/20252/2026',	NULL,	'IMAM MURSYID NUR WIBOWO',	'2407045',	'D4 - Teknologi Rekayasa Mesin Industri Perkebunan',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780992306_IMAM_MURSYID.pdf',	NULL,	'disahkan_118-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-09 09:50:39',	'ENdpt9YA7TBM0wHSNCfmqI2wPYqnBP1CA1VYl8LZRTNNi97JLqJKn4Gnp1jBSgMG',	NULL,	NULL,	NULL,	NULL,	'2026-06-09 08:05:06',	'2026-06-09 09:50:39'),
(51,	6,	1,	'Surat Keterangan Aktif Kuliah',	'119/Sket/AK/20252/2026',	NULL,	'MUHAMAD KIA AZHARI',	'2408036',	'D4 - Teknologi Rekayasa Kimia Industri',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780992663_MUHAMAD_KIA.pdf',	NULL,	'disahkan_119-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-09 09:50:28',	'wZYUrOq51V4Whh5OWOgTRl9P7Tnis78EGSijl8cJhzHUjsUGp2ATgjv1m0L2vO8O',	NULL,	NULL,	NULL,	NULL,	'2026-06-09 08:11:03',	'2026-06-09 09:50:28'),
(52,	6,	1,	'Surat Keterangan Aktif Kuliah',	'120/Sket/AK/20252/2026',	NULL,	'MUHAMMAD IQBAL',	'2301033',	'D4 - Teknologi Rekayasa Kimia Industri',	'6',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780994160_MUHAMAD_IQBAL.pdf',	NULL,	'disahkan_120-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-09 09:50:18',	'rQO0QcUYFEQSWXjoO9hudi8E9lbZV2gP7B4h9edTczfJMUVitGfo6QWYZSxs0Muu',	NULL,	NULL,	NULL,	NULL,	'2026-06-09 08:36:01',	'2026-06-09 09:50:18'),
(53,	6,	1,	'Surat Keterangan Aktif Kuliah',	'121/Sket/AK/20252/2026',	NULL,	'MUHAMMAD AZFAR FAIZI',	'2508022',	'D4 - Teknologi Rekayasa Kimia Industri',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780994501_MUHAMAD_AZFAR.pdf',	NULL,	'disahkan_121-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-09 09:50:09',	'P0e9dvmdVi4mAVCgDpilqjIZOtwGnmKz4YOrTUNwDv7Q160tMDQJaSkFmwTjiWJ2',	NULL,	NULL,	NULL,	NULL,	'2026-06-09 08:41:41',	'2026-06-09 09:50:09'),
(54,	6,	1,	'Surat Keterangan Aktif Kuliah',	'122/Sket/AK/20252/2026',	NULL,	'MUHAMMAD ICHWANUDDIN ROFFA',	'2507032',	'D4 - Teknologi Rekayasa Mesin Industri Perkebunan',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780994776_MUHAMAD_ICHWANUDDIN.pdf',	NULL,	'disahkan_122-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-09 09:49:57',	'HiVm2xXg0Pxi8OQOV3hne1y8vgLzkAA80YO5lF9USFAGZM86SU83smdSEaSAFlVH',	NULL,	NULL,	NULL,	NULL,	'2026-06-09 08:46:16',	'2026-06-09 09:49:57'),
(55,	6,	1,	'Surat Keterangan Aktif Kuliah',	'123/Sket/AK/20252/2026',	NULL,	'DWI CAHYA SEPTIANI',	'2408029',	'D4 - Teknologi Rekayasa Kimia Industri',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780995088_DWI_CAHYA.pdf',	NULL,	'disahkan_123-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-09 09:49:45',	'lQ3y5OnaPqWJ9QW752lcIyhx6CsPOcds7CuiuXeKEcITmIvnRTu2LsQbT7DteI3R',	NULL,	NULL,	NULL,	NULL,	'2026-06-09 08:51:28',	'2026-06-09 09:49:45'),
(56,	6,	1,	'Surat Keterangan Aktif Kuliah',	'124/Sket/AK/20252/2026',	NULL,	'BAGAS PRATAMA MANURUNG',	'2403002',	'D3 - Akuntansi',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780995353_BAGAS_PRATAMA.pdf',	NULL,	'disahkan_124-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-09 09:49:35',	'ynNjp8ivIKKFV3HyHOZuOdNfrFpD9qXbuffkJzfUQWaNNKwaruNWSZbNdqxaXCbv',	NULL,	NULL,	NULL,	NULL,	'2026-06-09 08:55:53',	'2026-06-09 09:49:35'),
(57,	6,	1,	'Surat Keterangan Aktif Kuliah',	'125/Sket/AK/20252/2026',	NULL,	'ADISTYA DEVI AULIANI',	'2304039',	'D3 - Budidaya Tanaman Perkebunan',	'6',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780995580_ADISTYA.pdf',	NULL,	'disahkan_125-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-09 09:49:26',	'x3lbVL9QWBkIr5W7A4XblWCzcThVDEzzdzeDYUrJ5QAiSm5mr0Na6i5MGujTCY5i',	NULL,	NULL,	NULL,	NULL,	'2026-06-09 08:59:40',	'2026-06-09 09:49:26'),
(58,	6,	1,	'Surat Keterangan Aktif Kuliah',	'126/Sket/AK/20252/2026',	NULL,	'CINTA FERBIASTUTI',	'2508042',	'D4 - Teknologi Rekayasa Kimia Industri',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1780995824_CINTA_FERBIASTUTI.pdf',	NULL,	'disahkan_126-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-09 09:49:16',	'yfBHanZRO5pDFxewmtl8urqRh2HkIWbWejNR4afbt7rsBw7YOrSnIh3bXj0PPbJC',	NULL,	NULL,	NULL,	NULL,	'2026-06-09 09:03:44',	'2026-06-09 09:49:16'),
(59,	6,	1,	'Surat Keterangan Aktif Kuliah',	'127/Sket/AK/20252/2026',	NULL,	'AVINI RATNA SARWANTI',	'2408021',	'D4 - Teknologi Rekayasa Kimia Industri',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781055777_AVINI_RATNA.pdf',	NULL,	'disahkan_127-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-11 04:35:24',	'4pFhVVCOkOlP4KQm8La1Wqn5pSaJk82aqcikUtM3ZfpigOqXgwGY7t1SQeb5pO2b',	NULL,	NULL,	NULL,	NULL,	'2026-06-10 01:42:57',	'2026-06-11 04:35:24'),
(60,	6,	1,	'Surat Keterangan Aktif Kuliah',	'128/Sket/AK/20252/2026',	NULL,	'NOVIKA INDRIANI',	'2403005',	'D3 - Akuntansi',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781056000_NOVIKA_INDRIANI.pdf',	NULL,	'disahkan_128-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-11 04:35:21',	'ppeQ6TOtmPGKmaCKllLHUWLXRP1jzVJisO9x5kVhwXjz2MlKuJFspyiRTLmMJvOM',	NULL,	NULL,	NULL,	NULL,	'2026-06-10 01:46:40',	'2026-06-11 04:35:21'),
(61,	6,	1,	'Surat Keterangan Aktif Kuliah',	'129/Sket/AK/20252/2026',	NULL,	'PIPIT NOVIANI',	'2403009',	'D3 - Akuntansi',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781056241_PIPIT_NOVIANI.pdf',	NULL,	'disahkan_129-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-11 04:35:17',	'oVWGIKIVVCeZ00ySVsACgnATFSsLTPFZtNIxjKDM9N1fpYCH5aTUFBbnT2DP0Ghp',	NULL,	NULL,	NULL,	NULL,	'2026-06-10 01:50:41',	'2026-06-11 04:35:17'),
(62,	6,	1,	'Surat Keterangan Aktif Kuliah',	'130/Sket/AK/20252/2026',	NULL,	'RESKI TAMA PANGGABEAN',	'2404096',	'D3 - Budidaya Tanaman Perkebunan',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781056960_RESKI_TAMA.pdf',	NULL,	'disahkan_130-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-11 04:34:57',	'TGX6y2Qed5hEOrGdht8vFYgHmjTJiT9sJP0ABDlB4ADbWwyIFpd24tW9p4UCSkrO',	NULL,	NULL,	NULL,	NULL,	'2026-06-10 02:02:40',	'2026-06-11 04:34:57'),
(63,	6,	1,	'Surat Keterangan Aktif Kuliah',	'131/Sket/AK/20252/2026',	NULL,	'AZZAHRA MAYLANI',	'2508040',	'D4 - Teknologi Rekayasa Kimia Industri',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781057231_AZZAHRA_MAYLANI.pdf',	NULL,	'disahkan_131-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-11 04:34:54',	'HdfD5K9XHfxNnXVMSuHyTCTjh4KVqp5J3OgLOxpOjjNobH6F6KKcxQwA3qgJEa3L',	NULL,	NULL,	NULL,	NULL,	'2026-06-10 02:07:11',	'2026-06-11 04:34:54'),
(64,	6,	1,	'Surat Keterangan Aktif Kuliah',	'132/Sket/AK/20252/2026',	NULL,	'AHMAD ARDHIANSYAH',	'2403001',	'D3 - Akuntansi',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781057458_AHMAD_ARDHIANSYAH.pdf',	NULL,	'disahkan_132-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-11 04:34:52',	'FOYuqX1yxCuNsqwBOGV9wLQBrz639fXVTU4b6BDJFEN0HfmBttGz1GNeSSodfqcn',	NULL,	NULL,	NULL,	NULL,	'2026-06-10 02:10:58',	'2026-06-11 04:34:52'),
(67,	6,	1,	'Surat Keterangan Aktif Kuliah',	'134/Sket/AK/20252/2026',	NULL,	'MUHAMMAD SUBHAN',	'2301035',	'D4 - Teknologi Rekayasa Kimia Industri',	'6',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781058962_MUHAMAD_SUBHAN.pdf',	NULL,	'disahkan_134-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-11 01:49:35',	'vBIMmLhiuGT1t8w3G44vyf8fBRP9anZCbR6m6M8L5BBAvarYYzV5baeLOhN9w6sd',	NULL,	NULL,	NULL,	NULL,	'2026-06-10 02:36:02',	'2026-06-11 01:49:35'),
(68,	6,	1,	'Surat Keterangan Aktif Kuliah',	'135/Sket/AK/20252/2026',	NULL,	'ANANDA PUTRI NOVIVANESA',	'2405010',	'D4 - Pengelolaan Perkebunan',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781059588_ANANADA_PUTRI.pdf',	NULL,	'disahkan_135-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-11 01:31:15',	'MmMQofEbHVNXyqjhLBYXGkpxmTWir61XXtDItAkx0knUNEVr4PuDeCjqPb3seKO7',	NULL,	NULL,	NULL,	NULL,	'2026-06-10 02:46:28',	'2026-06-11 01:31:15'),
(70,	6,	1,	'Surat Keterangan Aktif Kuliah',	'136/Sket/AK/20252/2026',	NULL,	'SIFA AULIANA',	'2403011',	'D3 - Akuntansi',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781061886_SIFA_AULIANA.pdf',	NULL,	'disahkan_136-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-11 01:30:37',	'Mt7l8TTaTYaPE2WCNvnFdMkWuI8dwKOB8QsnoZgLjDjNEf93JF3fITbL67SjbXFG',	NULL,	NULL,	NULL,	NULL,	'2026-06-10 03:24:46',	'2026-06-11 01:30:37'),
(71,	6,	1,	'Surat Keterangan Aktif Kuliah',	'137/Sket/AK/20252/2026',	NULL,	'WAN ANANDA AYUDEA PRATIWI',	'2504092',	'D3 - Budidaya Tanaman Perkebunan',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781062172_WAN_ANANDA.pdf',	NULL,	'disahkan_137-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-11 01:30:28',	'T2c05cViaYxpKafX3dTDOGXK7nFK4LkLORlukdanaSd6jMhsVMUUz7HilJdBgZx2',	NULL,	NULL,	NULL,	NULL,	'2026-06-10 03:29:32',	'2026-06-11 01:30:28'),
(72,	6,	1,	'Surat Keterangan Aktif Kuliah',	'138/Sket/AK/20252/2026',	NULL,	'MUHAMMAD KHANIF RIDHO ABRORI',	'2202020',	'D4 - Teknologi Rekayasa Mesin Industri Perkebunan',	'8',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781062457_MUHAMAD_KHANIF.pdf',	NULL,	'disahkan_138-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-11 01:30:20',	'KXB7I1cQLAYJXZr3O0JlMJLrxRi0HxIiS0xm1c1eegV3hjyVziu2y43PyYAa8lX8',	NULL,	NULL,	NULL,	NULL,	'2026-06-10 03:34:17',	'2026-06-11 01:30:20'),
(73,	6,	1,	'Surat Keterangan Aktif Kuliah',	'139/Sket/AK/20252/2026',	NULL,	'ULI ULFATUL KHUSNA',	'2508088',	'D4 - Teknologi Rekayasa Kimia Industri',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781062697_ULI_ULFATUN.pdf',	NULL,	'disahkan_139-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-11 01:30:13',	'0c8HrHJ41S225Hb4VsVOAR7zVvjpyCiSt92o599LEg0B3oBIHWHaUqgrICNwj6Wz',	NULL,	NULL,	NULL,	NULL,	'2026-06-10 03:38:17',	'2026-06-11 01:30:13'),
(74,	6,	1,	'Surat Keterangan Aktif Kuliah',	'140/Sket/AK/20252/2026',	NULL,	'BAYU ADIGUNA',	'2408023',	'D4 - Teknologi Rekayasa Kimia Industri',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781063143_BAYU_ADIGUNA.pdf',	NULL,	'disahkan_140-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-11 01:30:04',	'VUGnzvsEmfKPpCHg1pBB1ZWyz4FuQgNX4ddzExwyadInrzu0nxHUHWksXO5aBTbJ',	NULL,	NULL,	NULL,	NULL,	'2026-06-10 03:45:43',	'2026-06-11 01:30:04'),
(75,	6,	1,	'Surat Keterangan Aktif Kuliah',	'141/Sket/AK/20252/2026',	NULL,	'MUHAMMAD ALFAHROZI',	'2205047',	'D4 - Pengelolaan Perkebunan',	'8',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781063335_muhamad_alfahrozi.pdf',	NULL,	'disahkan_141-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-11 01:29:47',	'u7xYEZhpzJdH5J0RUb0DsmMxUc56r40stJ7Z8U4MGqOT1NQ7VS0FQd63fswLZ2UD',	NULL,	NULL,	NULL,	NULL,	'2026-06-10 03:48:55',	'2026-06-11 01:29:47'),
(76,	6,	1,	'Surat Keterangan Aktif Kuliah',	'142/Sket/AK/20252/2026',	NULL,	'MIKO ALFALAH',	'2301032',	'D4 - Teknologi Rekayasa Kimia Industri',	'6',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781063615_MIKO_ALFALAH.pdf',	NULL,	'disahkan_142-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-11 01:29:34',	'KuJ9buFbBHAHmLWGDCXkGuQDxQM6nPCqQ7YhLw0T3cSXQfVAIuDDs38p4h25luuB',	NULL,	NULL,	NULL,	NULL,	'2026-06-10 03:53:35',	'2026-06-11 01:29:34'),
(77,	6,	1,	'Surat Keterangan Aktif Kuliah',	'133/Sket/AK/20252/2026',	NULL,	'RIDWAN SEMBIRING',	'2507088',	'D4 - Teknologi Rekayasa Mesin Industri Perkebunan',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781152425_RIDWAN_SEMBIRING.pdf',	NULL,	'disahkan_133-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-11 04:34:45',	'Ux0wm5CgOhDmymaqaSLkCF3hfMCjYv94fcFvsYjKh2WCzzPOltSEQuLIb2waXk8e',	NULL,	NULL,	NULL,	NULL,	'2026-06-11 04:33:45',	'2026-06-11 04:34:45'),
(78,	6,	1,	'Surat Keterangan Aktif Kuliah',	'146/Sket/AK/20252/2026',	NULL,	'MHD. IMAM MAULANA PURBA',	'2505092',	'D4 - Pengelolaan Perkebunan',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781677198_IMAM_MAULANA.pdf',	NULL,	'disahkan_146-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-17 06:53:49',	'h4DYkAUwtsj3iC9t9irBmGhUbeU2CkIp2diLryrP0AI7ESrPBdlMVgQ9AJtA8RJl',	NULL,	NULL,	NULL,	NULL,	'2026-06-17 06:19:58',	'2026-06-17 06:53:49'),
(79,	6,	1,	'Surat Keterangan Aktif Kuliah',	'147/Sket/AK/20252/2026',	NULL,	'ABIYYU PUTRA YUANTO',	'2505022',	'D4 - Pengelolaan Perkebunan',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781677368_ABIYYU.pdf',	NULL,	'disahkan_147-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-17 06:53:47',	'PUrS1Ecbr3ZIFFgKl8MtV7pYKBdt8DKDmVHqnNdbnc0RyrfzoLIK6AUzHHlVH4VB',	NULL,	NULL,	NULL,	NULL,	'2026-06-17 06:22:48',	'2026-06-17 06:53:47'),
(80,	6,	1,	'Surat Keterangan Aktif Kuliah',	'148/Sket/AK/20252/2026',	NULL,	'AURA BALQIS CLEO NATYA HARIONO',	'2505016',	'D4 - Pengelolaan Perkebunan',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781677579_AURA.pdf',	NULL,	'disahkan_148-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-17 06:53:42',	'UAMGI8JTYFDdVz92nXpuHsWrkZsM2u6nwykX7Nr2FHoeRXfEQI7owO0bZKKDBVhn',	NULL,	NULL,	NULL,	NULL,	'2026-06-17 06:26:19',	'2026-06-17 06:53:42'),
(81,	6,	1,	'Surat Keterangan Aktif Kuliah',	'149/Sket/AK/20252/2026',	NULL,	'JESSE GIDEON KARO-KARO',	'2405030',	'D4 - Pengelolaan Perkebunan',	'4',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781763993_JESSE.pdf',	NULL,	'disahkan_149-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-19 02:48:22',	'3vaOSPsX6mYrZ9Y1p2MLQ88XhjnuiUO5gQS6miNRohUW7CiOsjXf54T18qgn8bOo',	NULL,	NULL,	NULL,	NULL,	'2026-06-18 06:26:33',	'2026-06-19 02:48:22'),
(82,	6,	1,	'Surat Keterangan Aktif Kuliah',	'151/Sket/AK/20252/2026',	NULL,	'M. HAFIZ FITRA RAMBE',	'2507069',	'D4 - Teknologi Rekayasa Mesin Industri Perkebunan',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1781836263_M._HAFIZ_FITRA.pdf',	NULL,	'disahkan_151-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-19 02:48:17',	'e3t3MdVST0HmkfkWi5jQbHdpHGwZft9DgFm9pYyanM3jLKBvZ40fITGMMvXd2PzL',	NULL,	NULL,	NULL,	NULL,	'2026-06-19 02:31:03',	'2026-06-19 02:48:17'),
(83,	6,	1,	'Surat Keterangan Aktif Kuliah',	'152/Sket/AK/20252/2026',	NULL,	'AFIF MAULANA RAVA PUTRA',	'2505024',	'D4 - Pengelolaan Perkebunan',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1782117263_AFIF.pdf',	NULL,	'disahkan_152-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-06-23 04:18:58',	'xsVhCXgPttLTgQoP99tkn0ra7B7vUSGoMp1AMDCaJD6npVlGMzNPZPDYUR6nQyxb',	NULL,	NULL,	NULL,	NULL,	'2026-06-22 08:34:23',	'2026-06-23 04:18:58'),
(84,	6,	1,	'Surat Keterangan Aktif Kuliah',	'156/Sket/AK/20252/2026',	NULL,	'ASA SYAHLA ARIFATU SONIA',	'2305028',	'D4 - Pengelolaan Perkebunan',	'6',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1783904510_ASA_SYAHLA.pdf',	NULL,	'disahkan_156-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-07-13 01:08:47',	'4QalpsywDcXAMMIKQ0YG42BJgiK6eHpbdgqrf7p9GJNBIYZnPdRMSAlpHusIqDJ1',	NULL,	NULL,	NULL,	NULL,	'2026-07-13 01:01:51',	'2026-07-13 01:08:47'),
(85,	6,	1,	'Surat Keterangan Aktif Kuliah',	'127.Sket/AK/20252/2026',	NULL,	'AYU TIARA KASIH',	'2504041',	'D3 - Budidaya Tanaman Perkebunan',	'2',	'20252 Genap',	NULL,	NULL,	NULL,	NULL,	'1786587299_AURA_KASIH.pdf',	NULL,	'disahkan_127-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	7,	'2026-08-13 02:21:47',	'9K1VSAg54Y0vPUB6BFaettZuM9Cv0Pe4nUmqGztw6obm4bJeLHVvjkOdDCdidQ5i',	NULL,	NULL,	NULL,	NULL,	'2026-08-13 02:15:00',	'2026-08-13 02:21:47'),
(90,	5,	2,	'Surat Permohonan Nilai',	'081/Sket/AK/20252/2026',	'2026-08-20 00:00:00',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'1787194647_Permohonan_Input_Nilai_(Seminar_Proposal)_Periode_20252.pdf',	2,	'disahkan_081-Sket-AK-20252-2026.pdf',	'disetujui',	NULL,	4,	'2026-08-20 03:02:26',	'FMXsj78ogl3CDz4TcBzE4QXdyT1ezDMyPTB58DtnQCvIGvICkZB2pWmLnlGUN4Nh',	1,	11.65,	44.91,	35.00,	'2026-08-20 02:57:27',	'2026-08-20 03:02:26');

DROP TABLE IF EXISTS `template_elements`;
CREATE TABLE `template_elements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `template_id` bigint unsigned NOT NULL,
  `element_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `x` int NOT NULL DEFAULT '0',
  `y` int NOT NULL DEFAULT '0',
  `font_size` int NOT NULL DEFAULT '24',
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#000000',
  `font_family` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'arial.ttf',
  `is_bold` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `template_elements_element_key_unique` (`element_key`),
  KEY `template_elements_template_id_foreign` (`template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `unit_user`;
CREATE TABLE `unit_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `unit_id` bigint unsigned NOT NULL,
  `role` enum('kepala','staff') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unit_user_unique` (`user_id`,`unit_id`,`role`),
  KEY `unit_user_unit_id_foreign` (`unit_id`),
  KEY `unit_user_role_index` (`role`),
  KEY `unit_user_is_active_index` (`is_active`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `unit_user` (`id`, `user_id`, `unit_id`, `role`, `jabatan`, `is_active`, `created_at`, `updated_at`) VALUES
(5,	8,	2,	'kepala',	'Kaprodi Teknologi Rekayasa Perangkat Lunak',	0,	'2026-08-14 09:13:25',	'2026-08-20 02:11:47'),
(3,	6,	1,	'staff',	'Staff BAA',	0,	'2026-08-14 08:50:21',	'2026-08-20 01:53:36'),
(4,	7,	1,	'kepala',	'Ka. Bagian Administrasi Akademik dan Beasiswa',	0,	'2026-08-14 08:50:21',	'2026-08-20 01:50:26'),
(8,	9,	2,	'staff',	'Staff TRPL',	0,	'2026-08-20 01:50:07',	'2026-08-20 01:57:45'),
(10,	4,	1,	'kepala',	'Ka. Bagian Administrasi Akademik dan Beasiswa',	1,	'2026-08-20 01:50:53',	'2026-08-20 01:50:53'),
(11,	5,	1,	'staff',	'Staff BAA',	1,	'2026-08-20 01:53:30',	'2026-08-20 01:53:30'),
(12,	5,	2,	'staff',	NULL,	1,	'2026-08-20 01:57:44',	'2026-08-20 01:57:44'),
(13,	4,	2,	'kepala',	NULL,	1,	'2026-08-20 01:57:52',	'2026-08-20 01:57:52');

DROP TABLE IF EXISTS `units`;
CREATE TABLE `units` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_dokumen_label` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pengajuan Surat',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `units_code_unique` (`code`),
  KEY `units_is_active_index` (`is_active`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `units` (`id`, `name`, `code`, `jenis_dokumen_label`, `is_active`, `created_at`, `updated_at`) VALUES
(1,	'Bagian Administrasi Akademik dan Beasiswa',	'akademik',	'Surat Keterangan Aktif Kuliah',	1,	'2026-08-14 08:50:21',	'2026-08-14 09:20:00'),
(2,	'D4 - Teknologi Rekayasa Perangkat Lunak',	'trpl',	'Pengajuan surat',	1,	'2026-08-14 09:07:12',	'2026-08-14 09:15:18');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `nip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_superadmin` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `role`, `nip`, `jabatan`, `is_superadmin`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1,	'Yang Punya Sistem',	'vry@polteklpp.ac.id',	'superadmin',	'000000000',	'Administrator Sistem',	1,	1,	NULL,	'$2y$12$qVpKaC6uJIfUUeEiP1fV4OF.sKDL/GBeeiEt5WtZG7W3duoGtuY2q',	NULL,	'2026-05-27 01:58:15',	'2026-06-11 05:34:26'),
(4,	'Kepala Ivory',	'ivoryalvadiaskurniawan@gmail.com',	'kepala',	'1234567890',	'Kepala Akademik',	0,	1,	NULL,	'$2y$12$oECicOjsmMkIzLESGHiz1ey9hxgXv54ut9sHoDEu0LS4VAItrKelK',	'paalj1KhV8uKRlYS7H6WWa6HfhHS0TvnrDoLi7GSmUDBs5iPFSMzx9660fBa',	'2026-05-27 02:00:38',	'2026-08-20 01:56:34'),
(5,	'Staff Ivory',	'ivorykurniawan06@gmail.com',	'staff',	'0987654321',	'Staff Akademik',	0,	1,	NULL,	'$2y$12$7bFsznmJcBkfFtI00AYVgO86IQ0AfP3DJZP6YsP1pF88o0GbQWXU.',	NULL,	'2026-05-27 02:01:16',	'2026-05-27 02:01:16'),
(6,	'Admin BAA',	'baak@polteklpp.ac.id',	'staff',	'-',	'Staff BAA',	0,	1,	NULL,	'$2y$12$/WvfgkCdCq0J8fSSdn3kk.93tiCXdZqiqdQAmhXaUVEvFJqq.WkzS',	NULL,	'2026-05-28 22:55:56',	'2026-05-28 22:55:56'),
(7,	'Arif Hidayat, ST, M.Pd, M.Eng.',	'arh@polteklpp.ac.id',	'kepala',	'0527058702',	'Ka. Bagian Administrasi Akademik dan Beasiswa',	0,	1,	NULL,	'$2y$12$4ADEnVH470X1F6D5qPQRxOEUJHue4oohf/KmuOsBkA1FNTXdkTnuC',	NULL,	'2026-05-28 23:00:23',	'2026-05-29 07:40:16'),
(8,	'Sal Sabila Wijayanti, S.Kom., M.Kom.',	'salsabila.wjynt@gmail.com',	'kepala',	'251101074',	'Ka. Prodi TRPL',	0,	1,	NULL,	'$2y$12$B20Hlb1dG.ua3VHJNcBBlO/.NcPfyLYu0hwQ2cmxBEInjGpSrZWlu',	NULL,	'2026-08-14 09:12:54',	'2026-08-14 09:12:54'),
(9,	'Staff TRPL',	'trpl@polteklpp.ac.id',	'staff',	NULL,	'Staff TRPL',	0,	1,	NULL,	'$2y$12$AxdCLcaKTzvmTmZvWVOxLeHor5.M/J7SEb33OC8iPtT1N16nkGfUy',	NULL,	'2026-08-20 01:49:50',	'2026-08-20 01:49:50');

-- 2026-08-20 06:42:54
