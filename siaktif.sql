-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20260525.e89cda7ecd
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 29, 2026 at 02:49 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siaktif`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` bigint UNSIGNED NOT NULL,
  `nim` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prodi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_ajaran` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_ayah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_ibu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_orangtua` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(6, '2026_05_27_041812_create_mahasiswa_table', 2),
(8, '0001_01_01_000000_create_users_table', 3),
(9, '0001_01_01_000001_create_cache_table', 3),
(10, '0001_01_01_000002_create_jobs_table', 3),
(11, '2026_05_26_151231_create_surats_table', 3),
(12, '2026_05_26_151421_add_columns_to_users_table', 3),
(13, '2026_05_27_043914_create_settings_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('ivoryalvadiaskurniawan@gmail.com', '$2y$12$u3l/ON0iWEPeXuya8/zhiO3f9MBZ8AjtJibZTZkIvsglIf7x7vHom', '2026-05-28 00:51:55'),
('ivorykurniawan06@gmail.com', '$2y$12$VHRwPcqIx0wGYrf2WzCcieYHY.QjL09MB3XbET3OZUcTADai/bl6O', '2026-05-27 18:40:09');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'master_prodi', '[\"D2 - Perawatan Mesin Industri Perkebunan\", \"D3 - Akuntansi\", \"D3 - Budidaya Tanaman Perkebunan\", \"D4 - Pengelolaan Perkebunan\", \"D4 - Teknologi Rekayasa Kimia Industri\", \"D4 - Teknologi Rekayasa Mesin Industri Perkebunan\"]', '2026-05-27 01:58:21', '2026-05-27 01:58:21'),
(2, 'master_semester', '[\"1\", \"2\", \"3\", \"4\", \"5\", \"6\", \"7\", \"8\"]', '2026-05-27 01:58:21', '2026-05-27 01:58:21'),
(3, 'master_tahun_ajaran', '[\"20241 Ganjil\", \"20242 Genap\", \"20251 Ganjil\", \"20252 Genap\"]', '2026-05-27 01:58:22', '2026-05-27 01:58:22');

-- --------------------------------------------------------

--
-- Table structure for table `surats`
--

CREATE TABLE `surats` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nomor_surat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_mahasiswa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nim` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prodi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_ajaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_ayah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_ibu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_orangtua` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_surat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_disahkan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('diajukan','disetujui','direvisi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'diajukan',
  `catatan_revisi` text COLLATE utf8mb4_unicode_ci,
  `validated_by` bigint UNSIGNED DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `qr_token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surats`
--

INSERT INTO `surats` (`id`, `user_id`, `nomor_surat`, `nama_mahasiswa`, `nim`, `prodi`, `semester`, `tahun_ajaran`, `nama_ayah`, `nama_ibu`, `pekerjaan`, `alamat_orangtua`, `file_surat`, `file_disahkan`, `status`, `catatan_revisi`, `validated_by`, `validated_at`, `qr_token`, `created_at`, `updated_at`) VALUES
(1, 5, '081/Sket/AK/20252/2026', 'MUHAMMAD ARFAN RITONGA', '2305060', 'D4 - Pengelolaan Perkebunan', '6', '20252 Genap', 'MURADONG RITONGA', '', 'TNI', 'JL.SUMPAH PRAJURIT BARAT NO.42', '1779873347_ARFAN.pdf', 'disahkan_081-Sket-AK-20252-2026.pdf', 'disetujui', NULL, 4, '2026-05-27 02:23:18', 'nOJmlxxEXlkXulhQ3mfRJ4dBulBnGBPJqNMxC9Ceq10jCPyuxisx1COLTOElq8Jx', '2026-05-27 02:15:47', '2026-05-27 02:23:18'),
(4, 5, '082/Sket/AK/20252/2026', 'MUHAMMAD ARFAN RITONGA', '2305060', 'D4 - Pengelolaan Perkebunan', '7', '20252 Genap', 'MURADONG RITONGA', '', 'TNI', 'JL.SUMPAH PRAJURIT BARAT NO.42', '1779875156_ARFAN.pdf', 'disahkan_082-Sket-AK-20252-2026.pdf', 'disetujui', NULL, 4, '2026-05-27 03:48:33', 'JPEg4WZ0UdqClZEuudjhDNLCWOZ41Z57XFaza0EbtI2OCqPZ4a6XMMbSZUFM8Qcy', '2026-05-27 02:45:56', '2026-05-27 03:48:33'),
(5, 1, '083/Sket/AK/20252/2026', 'Mahasiswa 1', '12345', 'D2 - Perawatan Mesin Industri Perkebunan', '1', '20252 Genap', '', 'Ibu Mahasiswa 1', 'IRT', 'Rumah Mahasiswa 1', '1779891266_ARFAN.pdf', NULL, 'diajukan', NULL, NULL, NULL, 'zCyPzxdQCsR6qncERTxS9IluUqjLPMYOIOZb2zUrlscAfiHWXbAZMqM3xS6pMPYU', '2026-05-27 07:14:26', '2026-05-27 07:14:26'),
(6, 1, '084/Sket/AK/20252/2026', 'Mahasiswa 2', '12345', 'D3 - Akuntansi', '2', '20252 Genap', '', 'Ibu Mahasiswa 2', 'IRT', 'Rumah Mahasiswa 2', '1779891540_ARFAN.pdf', NULL, 'diajukan', NULL, NULL, NULL, 'vl4bepvaSwFJAGTQASgnJTHMa4w9vT8TO8FVHkf2YebOh82WNUXrT6SYAmgqZTbU', '2026-05-27 07:19:00', '2026-05-27 07:19:00'),
(7, 1, '085/Sket/AK/20252/2026', 'Mahasiswa 3', '12345', 'D3 - Budidaya Tanaman Perkebunan', '3', '20252 Genap', 'Ayah Mahasiswa 3', '', 'Wiraswasta', 'Rumah Mahasiswa 3', '1779891617_ARFAN.pdf', NULL, 'diajukan', NULL, NULL, NULL, 'pNhsFYziByVaRwlX6OmCfCIQ6gEgcbadlNpxZHres9p8dn7Rn7jUN11SsodRepRL', '2026-05-27 07:20:17', '2026-05-27 07:20:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `nip`, `jabatan`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Superadmin', 'ivoryalvadiaskurniawan@gmail.com', 'superadmin', '000000000', 'Administrator Sistem', 1, NULL, '$2y$12$Dq00Q9SDTiyt6cJSZ5FwMOZYaqp8NF1HO1okr6OA0yVwVqE/18c1e', NULL, '2026-05-27 01:58:15', '2026-05-27 01:58:15'),
(4, 'Kepala Ivory', 'vry@polteklpp.ac.id', 'kepala', '1234567890', 'Kepala Akademik', 1, NULL, '$2y$12$BzqLh5n0eoz11yp57lPssOOJwlkWt1CPG62TOnU2OpyibuIGPLjbi', NULL, '2026-05-27 02:00:38', '2026-05-27 02:00:38'),
(5, 'Staff Ivory', 'ivorykurniawan06@gmail.com', 'staff', '0987654321', 'Staff Akademik', 1, NULL, '$2y$12$7bFsznmJcBkfFtI00AYVgO86IQ0AfP3DJZP6YsP1pF88o0GbQWXU.', NULL, '2026-05-27 02:01:16', '2026-05-27 02:01:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mahasiswa_nim_unique` (`nim`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `surats`
--
ALTER TABLE `surats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `surats_nomor_surat_unique` (`nomor_surat`),
  ADD UNIQUE KEY `surats_qr_token_unique` (`qr_token`),
  ADD KEY `surats_user_id_foreign` (`user_id`),
  ADD KEY `surats_validated_by_foreign` (`validated_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `surats`
--
ALTER TABLE `surats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `surats`
--
ALTER TABLE `surats`
  ADD CONSTRAINT `surats_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `surats_validated_by_foreign` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
