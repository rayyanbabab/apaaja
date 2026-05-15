-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 10, 2026 at 07:10 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `artilia_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `module`, `description`, `subject_type`, `subject_id`, `properties`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 1, 'incoming.created', 'Barang Masuk', 'Stok masuk ditambahkan untuk 1 item barang', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 14:08:54', '2026-04-07 14:08:54'),
(2, 1, 'user.created', 'User', 'User \"ugu\" (kontolodon@gmail.com) dengan role user dibuat', 'App\\Models\\User', 18, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 14:34:36', '2026-04-07 14:34:36'),
(3, 1, 'user.deleted', 'User', 'User \"ugu\" (justinbones31@outlook.com) dihapus', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 14:34:53', '2026-04-07 14:34:53'),
(6, 15, 'item.created', 'Inventory', 'Barang \"KUNCI RING\" (ITM-0064) ditambahkan', 'App\\Models\\Item', 64, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 15:49:43', '2026-04-07 15:49:43'),
(7, 15, 'item.deleted', 'Inventory', 'Barang \"KUNCI RING\" () dihapus permanen', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 15:50:00', '2026-04-07 15:50:00'),
(8, 15, 'item.deleted', 'Inventory', 'Barang \"KUNCI RING\" () dihapus permanen', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 15:50:08', '2026-04-07 15:50:08'),
(9, 15, 'incoming.created', 'Barang Masuk', 'Stok masuk ditambahkan untuk 1 item barang', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 15:50:57', '2026-04-07 15:50:57'),
(10, 15, 'item.updated', 'Inventory', 'Barang \"KUNCI RING\" (ITM-0064) diperbarui', 'App\\Models\\Item', 64, '{\"nama_baru\": \"KUNCI RING\", \"nama_lama\": \"KUNCI RING\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 15:53:46', '2026-04-07 15:53:46'),
(11, 1, 'item.created', 'Inventory', 'Barang \"hiuhiu\" (ITM-0065) ditambahkan', 'App\\Models\\Item', 65, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 15:56:58', '2026-04-07 15:56:58'),
(12, 1, 'item.deleted', 'Inventory', 'Barang \"hiuhiu\" (ITM-0065) dihapus permanen', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 15:57:21', '2026-04-07 15:57:21'),
(13, 1, 'incoming.created', 'Barang Masuk', 'Stok masuk ditambahkan untuk 1 item barang', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 16:38:00', '2026-04-07 16:38:00'),
(14, 1, 'item.created', 'Inventory', 'Barang \"hiuhiu\" (ITM-0066) ditambahkan', 'App\\Models\\Item', 66, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 16:42:04', '2026-04-07 16:42:04'),
(15, 1, 'item.deleted', 'Inventory', 'Barang \"hiuhiu\" (ITM-0066) dihapus permanen', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 16:42:34', '2026-04-07 16:42:34'),
(16, 1, 'item.updated', 'Inventory', 'Barang \"KUNCI RING\" (ITM-0064) diperbarui', 'App\\Models\\Item', 64, '{\"nama_baru\": \"KUNCI RING\", \"nama_lama\": \"KUNCI RING\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 16:44:09', '2026-04-07 16:44:09'),
(17, 1, 'item.created', 'Inventory', 'Barang \"BEARING-8399\" (ITM-0067) ditambahkan', 'App\\Models\\Item', 67, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 16:44:46', '2026-04-07 16:44:46'),
(18, 1, 'item.deleted', 'Inventory', 'Barang \"BEARING-8399\" (ITM-0067) dihapus permanen', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 16:44:53', '2026-04-07 16:44:53'),
(19, 15, 'item.created', 'Inventory', 'Barang \"BEARING-8399\" (ITM-0068) ditambahkan', 'App\\Models\\Item', 68, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 16:46:09', '2026-04-07 16:46:09'),
(20, 15, 'item.deleted', 'Inventory', 'Barang \"BEARING-8399\" (ITM-0068) dihapus permanen', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 16:46:18', '2026-04-07 16:46:18'),
(21, 1, 'category.created', 'Kategori', 'Kategori \"KUNCI\" ditambahkan', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 15:30:23', '2026-04-08 15:30:23'),
(22, 1, 'category.updated', 'Kategori', 'Kategori \"KUNCII\" diperbarui', 'App\\Models\\Category', 48, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 15:31:35', '2026-04-08 15:31:35'),
(23, 1, 'supplier.created', 'Supplier', 'Supplier \"CV.MAJU TERUSS\" ditambahkan', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 15:33:35', '2026-04-08 15:33:35'),
(24, 1, 'supplier.updated', 'Supplier', 'Supplier \"CV.MAJU TERUS\" diperbarui', 'App\\Models\\Supplier', 26, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 15:34:16', '2026-04-08 15:34:16'),
(25, 1, 'category.updated', 'Kategori', 'Kategori \"KUNCI\" diperbarui', 'App\\Models\\Category', 48, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 15:35:57', '2026-04-08 15:35:57'),
(26, 1, 'item.created', 'Inventory', 'Barang \"IMPACT\" (ITM-0069) ditambahkan', 'App\\Models\\Item', 69, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 15:36:36', '2026-04-08 15:36:36'),
(27, 1, 'incoming.created', 'Barang Masuk', 'Stok masuk ditambahkan untuk 1 item barang', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 15:37:08', '2026-04-08 15:37:08'),
(28, 1, 'outgoing.created', 'Barang Keluar', 'Pencatatan 1 item barang keluar untuk messi', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 15:44:27', '2026-04-08 15:44:27'),
(29, 1, 'user.updated', 'User', 'User \"staff\" (staff@gmail.com) diperbarui', 'App\\Models\\User', 15, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 15:51:28', '2026-04-08 15:51:28'),
(30, 1, 'user.updated', 'User', 'User \"staff\" (staff@gmail.com) diperbarui', 'App\\Models\\User', 15, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-09 02:04:05', '2026-04-09 02:04:05'),
(31, 1, 'user.updated', 'User', 'User \"staff\" (staff@gmail.com) diperbarui', 'App\\Models\\User', 15, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-09 02:04:20', '2026-04-09 02:04:20'),
(32, 1, 'user.updated', 'User', 'User \"staff\" (staff@gmail.com) diperbarui', 'App\\Models\\User', 15, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-09 02:04:35', '2026-04-09 02:04:35'),
(33, 15, 'user.updated', 'User', 'User \"staff\" (staff@gmail.com) diperbarui', 'App\\Models\\User', 15, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-09 02:05:02', '2026-04-09 02:05:02'),
(34, 1, 'category.deleted', 'Kategori', 'Kategori \"ikan\" dihapus', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 05:48:45', '2026-04-10 05:48:45'),
(35, 1, 'category.deleted', 'Kategori', 'Kategori \"BARANG-99\" dihapus', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 05:49:21', '2026-04-10 05:49:21'),
(36, 1, 'item.updated', 'Inventory', 'Barang \"KUNCI RING\" (ITM-0064) diperbarui', 'App\\Models\\Item', 64, '{\"nama_baru\": \"KUNCI RING\", \"nama_lama\": \"KUNCI RING\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 06:06:54', '2026-04-10 06:06:54'),
(37, 1, 'item.updated', 'Inventory', 'Barang \"PAPER XRAY\" () diperbarui', 'App\\Models\\Item', 55, '{\"nama_baru\": \"PAPER XRAY\", \"nama_lama\": \"PAPER XRAY\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 06:07:15', '2026-04-10 06:07:15'),
(38, 1, 'item.updated', 'Inventory', 'Barang \"TANG ORING\" () diperbarui', 'App\\Models\\Item', 53, '{\"nama_baru\": \"TANG ORING\", \"nama_lama\": \"TANG ORING\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 06:07:29', '2026-04-10 06:07:29'),
(39, 1, 'item.updated', 'Inventory', 'Barang \"BEARING-8399\" () diperbarui', 'App\\Models\\Item', 50, '{\"nama_baru\": \"BEARING-8399\", \"nama_lama\": \"BEARING-8399\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 06:07:48', '2026-04-10 06:07:48'),
(40, 1, 'item.updated', 'Inventory', 'Barang \"SENSOR-FU 40\" () diperbarui', 'App\\Models\\Item', 49, '{\"nama_baru\": \"SENSOR-FU 40\", \"nama_lama\": \"SENSOR-FU 40\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 06:08:04', '2026-04-10 06:08:04'),
(41, 1, 'item.updated', 'Inventory', 'Barang \"SOLDER\" () diperbarui', 'App\\Models\\Item', 48, '{\"nama_baru\": \"SOLDER\", \"nama_lama\": \"SOLDER\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 06:08:17', '2026-04-10 06:08:17'),
(42, 1, 'user.updated', 'User', 'User \"messi\" (messi@gmailcom) diperbarui', 'App\\Models\\User', 10, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 06:21:41', '2026-04-10 06:21:41'),
(43, 1, 'user.updated', 'User', 'User \"messi\" (messi@gmailcom) diperbarui', 'App\\Models\\User', 10, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 06:23:18', '2026-04-10 06:23:18');

-- --------------------------------------------------------

--
-- Table structure for table `borrowings`
--

CREATE TABLE `borrowings` (
  `id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali_rencana` date NOT NULL,
  `tanggal_kembali_aktual` date DEFAULT NULL,
  `status` enum('dipinjam','dikembalikan','terlambat') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dipinjam',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `kondisi_pinjam` text COLLATE utf8mb4_unicode_ci,
  `kondisi_kembali` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `borrowings`
--

INSERT INTO `borrowings` (`id`, `item_id`, `user_id`, `jumlah`, `tanggal_pinjam`, `tanggal_kembali_rencana`, `tanggal_kembali_aktual`, `status`, `keterangan`, `kondisi_pinjam`, `kondisi_kembali`, `created_at`, `updated_at`) VALUES
(14, 48, 10, 1, '2025-11-27', '2025-11-28', '2025-11-27', 'dikembalikan', NULL, NULL, NULL, '2025-11-27 04:09:44', '2025-11-27 04:09:53'),
(16, 69, 10, 1, '2026-04-08', '2026-04-09', '2026-04-08', 'dikembalikan', 'kk', 'baik', NULL, '2026-04-08 15:47:00', '2026-04-08 16:24:06'),
(17, 47, 10, 1, '2026-04-08', '2026-04-09', '2026-04-08', 'dikembalikan', NULL, 'ii', NULL, '2026-04-08 16:23:12', '2026-04-08 16:23:51'),
(18, 69, 10, 1, '2026-04-08', '2026-04-09', '2026-04-08', 'dikembalikan', NULL, 'oo', NULL, '2026-04-08 16:26:15', '2026-04-08 16:26:33'),
(19, 69, 10, 1, '2026-04-08', '2026-04-09', '2026-04-08', 'dikembalikan', NULL, 'o', NULL, '2026-04-08 16:30:47', '2026-04-08 16:30:52');

-- --------------------------------------------------------

--
-- Table structure for table `borrowing_carts`
--

CREATE TABLE `borrowing_carts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `borrowing_carts`
--

INSERT INTO `borrowing_carts` (`id`, `user_id`, `item_id`, `jumlah`, `created_at`, `updated_at`) VALUES
(37, 10, 47, 1, '2026-04-04 12:30:12', '2026-04-04 12:30:12'),
(38, 10, 48, 1, '2026-04-04 12:30:15', '2026-04-04 12:30:15');

-- --------------------------------------------------------

--
-- Table structure for table `borrowing_requests`
--

CREATE TABLE `borrowing_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `batch_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali_rencana` date NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `kondisi_pinjam` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `overdue_notified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `borrowing_requests`
--

INSERT INTO `borrowing_requests` (`id`, `user_id`, `batch_id`, `item_id`, `jumlah`, `tanggal_pinjam`, `tanggal_kembali_rencana`, `keterangan`, `kondisi_pinjam`, `status`, `admin_notes`, `approved_by`, `approved_at`, `completed_at`, `overdue_notified_at`, `created_at`, `updated_at`) VALUES
(3, 10, NULL, 48, 1, '2025-10-10', '2025-10-12', 'kmweo', '23io2o', 'completed', NULL, 1, '2025-10-10 10:02:20', '2025-10-10 10:05:19', NULL, '2025-10-10 09:38:27', '2025-10-10 10:05:19'),
(4, 10, NULL, 47, 1, '2025-10-23', '2025-10-25', 'ko', 'eiie', 'rejected', 'sedang di pinjam', NULL, '2025-11-03 04:46:44', NULL, NULL, '2025-10-23 14:15:16', '2025-11-03 04:46:44'),
(5, 10, NULL, 47, 1, '2025-10-25', '2025-10-27', 'jiooi', 'niin', 'rejected', 'KAMU SUKA TELAT MENGEMBALIKAN', NULL, '2025-11-10 15:40:19', NULL, NULL, '2025-10-24 23:19:41', '2025-11-10 15:40:19'),
(6, 10, NULL, 47, 1, '2025-10-31', '2025-11-02', 'untuk benerin', 'baik', 'completed', 'jangan lupa di kembalikan', NULL, '2025-11-03 04:31:23', '2025-11-10 15:40:26', NULL, '2025-10-31 12:10:26', '2025-11-10 15:40:26'),
(8, 10, NULL, 48, 2, '2025-11-20', '2025-11-21', 'BENERIN', 'BAIK', 'completed', 'JANGAN LUPA DI KEMBALIKAN', NULL, '2025-11-20 14:15:12', '2025-11-20 14:16:35', NULL, '2025-11-20 14:07:50', '2025-11-20 14:16:35'),
(9, 10, NULL, 47, 1, '2025-11-21', '2025-11-22', 'repaire', 'good', 'rejected', 'nkn', NULL, '2025-11-20 17:25:39', NULL, NULL, '2025-11-20 17:16:37', '2025-11-20 17:25:39'),
(10, 10, NULL, 48, 1, '2025-11-21', '2025-11-22', 'repaire', 'good', 'rejected', 'rusak', NULL, '2025-11-20 17:28:19', NULL, NULL, '2025-11-20 17:16:37', '2025-11-20 17:28:19'),
(11, 10, NULL, 53, 1, '2025-11-21', '2025-11-22', 'repaire', 'good', 'rejected', 'rusak', NULL, '2025-11-20 17:28:27', NULL, NULL, '2025-11-20 17:16:37', '2025-11-20 17:28:27'),
(12, 10, 'BATCH-10-691f4f4c5b9362.15420228', 47, 1, '2025-11-21', '2025-11-22', 'kk', 'kk', 'rejected', 'rusak', NULL, '2025-11-20 17:28:07', NULL, NULL, '2025-11-20 17:26:36', '2025-11-20 17:28:07'),
(13, 10, 'BATCH-10-691f4f4c5b9362.15420228', 48, 1, '2025-11-21', '2025-11-22', 'kk', 'kk', 'rejected', 'rusak', NULL, '2025-11-20 17:28:07', NULL, NULL, '2025-11-20 17:26:36', '2025-11-20 17:28:07'),
(14, 10, 'BATCH-10-691f4f4c5b9362.15420228', 53, 1, '2025-11-21', '2025-11-22', 'kk', 'kk', 'rejected', 'rusak', NULL, '2025-11-20 17:28:07', NULL, NULL, '2025-11-20 17:26:36', '2025-11-20 17:28:07'),
(15, 10, 'BATCH-10-691f520d607468.24750689', 48, 2, '2025-11-21', '2025-11-22', 'repaire', 'baik', 'completed', NULL, NULL, '2025-11-20 17:38:56', '2025-11-20 17:40:13', NULL, '2025-11-20 17:38:21', '2025-11-20 17:40:13'),
(16, 10, 'BATCH-10-69265af0f1dde7.47377969', 47, 1, '2025-11-26', '2025-11-27', 'repaire', NULL, 'cancelled', NULL, NULL, NULL, NULL, NULL, '2025-11-26 01:42:08', '2025-11-26 03:06:47'),
(17, 10, 'BATCH-10-69265af0f1dde7.47377969', 48, 2, '2025-11-26', '2025-11-27', 'repaire', NULL, 'completed', NULL, NULL, '2025-11-26 01:49:04', '2025-11-26 01:52:13', NULL, '2025-11-26 01:42:09', '2025-11-26 01:52:13'),
(18, 10, 'BATCH-10-69266893c4a129.16759397', 47, 1, '2025-11-26', '2025-11-27', NULL, NULL, 'cancelled', NULL, NULL, NULL, NULL, NULL, '2025-11-26 02:40:19', '2025-11-26 02:56:09'),
(19, 10, 'BATCH-10-69266893c4a129.16759397', 48, 2, '2025-11-26', '2025-11-27', NULL, NULL, 'cancelled', NULL, NULL, NULL, NULL, NULL, '2025-11-26 02:40:19', '2025-11-26 02:56:15'),
(20, 10, 'BATCH-10-69266f4c4f3756.83104696', 48, 1, '2025-11-26', '2025-11-27', NULL, NULL, 'completed', NULL, NULL, '2025-11-26 04:50:12', '2025-11-26 04:57:29', NULL, '2025-11-26 03:09:00', '2025-11-26 04:57:29'),
(21, 10, 'BATCH-10-69266f4c4f3756.83104696', 53, 1, '2025-11-26', '2025-11-27', NULL, NULL, 'rejected', 'gaboleh', NULL, '2025-11-26 04:56:21', NULL, NULL, '2025-11-26 03:09:00', '2025-11-26 04:56:21'),
(22, 10, 'BATCH-10-69268ea0d185a6.66282022', 47, 1, '2025-11-26', '2025-11-27', NULL, NULL, 'completed', NULL, NULL, '2025-11-26 05:23:09', '2025-11-26 06:38:42', NULL, '2025-11-26 05:22:40', '2025-11-26 06:38:42'),
(23, 10, 'BATCH-10-69268ea0d185a6.66282022', 48, 1, '2025-11-26', '2025-11-27', NULL, NULL, 'rejected', 'nn', NULL, '2025-11-26 05:23:19', NULL, NULL, '2025-11-26 05:22:40', '2025-11-26 05:23:19'),
(26, 10, NULL, 48, 1, '2025-11-27', '2025-11-28', 'Pengembalian barang langsung', NULL, 'completed', 'Barang dikembalikan melalui sistem peminjaman langsung', NULL, '2025-11-27 04:09:53', '2025-11-27 04:09:53', NULL, '2025-11-27 04:09:53', '2025-11-27 04:09:53'),
(28, 10, 'BATCH-10-69841490bd3615.31496567', 47, 1, '2026-02-05', '2026-02-06', NULL, NULL, 'completed', NULL, NULL, '2026-02-05 03:56:22', '2026-02-05 04:06:51', NULL, '2026-02-05 03:54:56', '2026-02-05 04:06:51'),
(29, 10, 'BATCH-10-69841490bd3615.31496567', 48, 1, '2026-02-05', '2026-02-06', NULL, NULL, 'rejected', 'rusak', NULL, '2026-02-05 04:03:50', NULL, NULL, '2026-02-05 03:54:56', '2026-02-05 04:03:50'),
(32, 10, NULL, 48, 1, '2026-02-05', '2026-02-06', 'un', 'baik', 'completed', 'jangan lupa di kembalikan', 1, '2026-02-05 04:28:17', '2026-02-05 06:22:41', NULL, '2026-02-05 04:22:54', '2026-02-05 06:22:41'),
(33, 10, NULL, 48, 2, '2026-02-05', '2026-02-06', 'lem', NULL, 'completed', 'okkk', 1, '2026-02-05 06:27:25', '2026-02-05 06:28:16', NULL, '2026-02-05 06:26:58', '2026-02-05 06:28:16'),
(34, 10, 'BATCH-10-69cb7177f25c67.65370046', 47, 1, '2026-03-31', '2026-04-02', 'iij', 'ii', 'completed', 'aa', 1, '2026-03-31 07:03:16', '2026-03-31 07:04:40', NULL, '2026-03-31 07:02:15', '2026-03-31 07:04:40'),
(35, 10, 'BATCH-10-69cb7177f25c67.65370046', 53, 1, '2026-03-31', '2026-04-02', 'iij', 'ii', 'completed', 'kkk', 1, '2026-03-31 07:03:01', '2026-03-31 07:04:22', NULL, '2026-03-31 07:02:16', '2026-03-31 07:04:22'),
(36, 10, 'BATCH-10-69cdf219cfb9b2.92364579', 47, 1, '2026-04-02', '2026-04-03', NULL, NULL, 'completed', 'kk', 1, '2026-04-02 04:36:23', '2026-04-04 06:28:48', '2026-04-04 00:13:04', '2026-04-02 04:35:37', '2026-04-04 06:28:48'),
(37, 10, 'BATCH-10-69cdf219cfb9b2.92364579', 53, 1, '2026-04-02', '2026-04-03', NULL, NULL, 'completed', 'oo', 1, '2026-04-02 04:36:47', '2026-04-04 06:28:35', '2026-04-04 00:13:04', '2026-04-02 04:35:37', '2026-04-04 06:28:35'),
(38, 10, 'BATCH-10-69d101be6ddda9.30412663', 47, 2, '2026-04-04', '2026-04-05', 's', 's', 'rejected', 'kk', 1, '2026-04-04 12:21:10', NULL, NULL, '2026-04-04 12:19:10', '2026-04-04 12:21:10'),
(39, 10, 'BATCH-10-69d101be6ddda9.30412663', 48, 2, '2026-04-04', '2026-04-05', 's', 's', 'rejected', 'q', 1, '2026-04-04 12:21:19', NULL, NULL, '2026-04-04 12:19:10', '2026-04-04 12:21:19'),
(40, 10, 'BATCH-10-69d101be6ddda9.30412663', 53, 1, '2026-04-04', '2026-04-05', 's', 's', 'completed', 'm', 1, '2026-04-04 12:21:29', '2026-04-05 15:29:54', '2026-04-05 15:25:03', '2026-04-04 12:19:10', '2026-04-05 15:29:54'),
(41, 10, 'BATCH-10-69d102ce0bcc22.79719383', 47, 1, '2026-04-04', '2026-04-05', 'kkk', 's', 'cancelled', NULL, NULL, NULL, NULL, NULL, '2026-04-04 12:23:42', '2026-04-05 12:01:28'),
(42, 10, 'BATCH-10-69d102ce0bcc22.79719383', 48, 1, '2026-04-04', '2026-04-05', 'kkk', 's', 'cancelled', NULL, NULL, NULL, NULL, NULL, '2026-04-04 12:23:42', '2026-04-04 12:29:17'),
(43, 10, NULL, 48, 2, '2026-04-04', '2026-04-05', 'skk', 'w', 'cancelled', NULL, NULL, NULL, NULL, NULL, '2026-04-04 12:29:33', '2026-04-05 12:01:30');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('notifications:user:1:recent', 'a:2:{s:13:\"notifications\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:4:{i:0;a:7:{s:2:\"id\";s:36:\"a88901a0-747c-4df0-a35c-d1f4782a1af2\";s:7:\"message\";s:82:\"Stok item \"IMPACT\" hampir habis! Stok saat ini: 2 unit (batas peringatan: 2 unit).\";s:4:\"type\";s:9:\"low_stock\";s:3:\"url\";s:45:\"http://127.0.0.1:8000/admin/inventory/show/69\";s:7:\"read_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-08 22:50:42.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:12:\"Asia/Jakarta\";}s:9:\"is_unread\";b:0;s:10:\"created_at\";s:9:\"1 day ago\";}i:1;a:7:{s:2:\"id\";s:36:\"4daaa119-5b4d-4bbb-b878-f1608905f598\";s:7:\"message\";s:85:\"Stok item \"KUNCI LLL\" hampir habis! Stok saat ini: 5 unit (batas peringatan: 5 unit).\";s:4:\"type\";s:9:\"low_stock\";s:3:\"url\";s:45:\"http://127.0.0.1:8000/admin/inventory/show/61\";s:7:\"read_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-04 13:45:29.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:12:\"Asia/Jakarta\";}s:9:\"is_unread\";b:0;s:10:\"created_at\";s:10:\"5 days ago\";}i:2;a:7:{s:2:\"id\";s:36:\"1a9589cc-f2b3-4dd2-b7c4-2a26c10f2a7e\";s:7:\"message\";s:86:\"Stok item \"TANG ORING\" hampir habis! Stok saat ini: 1 unit (batas peringatan: 5 unit).\";s:4:\"type\";s:9:\"low_stock\";s:3:\"url\";s:40:\"http://localhost/admin/inventory/show/53\";s:7:\"read_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-04 01:37:14.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:12:\"Asia/Jakarta\";}s:9:\"is_unread\";b:0;s:10:\"created_at\";s:10:\"6 days ago\";}i:3;a:7:{s:2:\"id\";s:36:\"80dfc279-d36f-4827-8091-9be47fb36b92\";s:7:\"message\";s:82:\"Stok item \"SOLDER\" hampir habis! Stok saat ini: 5 unit (batas peringatan: 5 unit).\";s:4:\"type\";s:9:\"low_stock\";s:3:\"url\";s:40:\"http://localhost/admin/inventory/show/48\";s:7:\"read_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-04 01:37:14.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:12:\"Asia/Jakarta\";}s:9:\"is_unread\";b:0;s:10:\"created_at\";s:10:\"6 days ago\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:12:\"unread_count\";i:0;}', 1775802201),
('notifications:user:1:unread_count', 'i:0;', 1775802201),
('notifications:user:10:recent', 'a:2:{s:13:\"notifications\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:8:{i:0;a:7:{s:2:\"id\";s:36:\"92b212fa-c569-4f15-8200-e5ee8bfe646d\";s:7:\"message\";s:41:\"Peminjaman barang \"IMPACT\" telah selesai.\";s:4:\"type\";s:9:\"completed\";s:3:\"url\";s:36:\"http://127.0.0.1:8000/user/dashboard\";s:7:\"read_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-09 09:23:37.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:12:\"Asia/Jakarta\";}s:9:\"is_unread\";b:0;s:10:\"created_at\";s:9:\"1 day ago\";}i:1;a:7:{s:2:\"id\";s:36:\"0d52db5c-0030-4a1a-98f8-23b760b04ab7\";s:7:\"message\";s:82:\"Anda sedang meminjam barang \"IMPACT\". Harap kembalikan sebelum tanggal 09 Apr 2026\";s:4:\"type\";s:7:\"ongoing\";s:3:\"url\";s:36:\"http://127.0.0.1:8000/user/dashboard\";s:7:\"read_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-09 09:23:37.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:12:\"Asia/Jakarta\";}s:9:\"is_unread\";b:0;s:10:\"created_at\";s:9:\"1 day ago\";}i:2;a:7:{s:2:\"id\";s:36:\"61148110-7fc6-4b58-bd1f-abb010336b4b\";s:7:\"message\";s:41:\"Peminjaman barang \"IMPACT\" telah selesai.\";s:4:\"type\";s:9:\"completed\";s:3:\"url\";s:36:\"http://127.0.0.1:8000/user/dashboard\";s:7:\"read_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-08 23:27:08.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:12:\"Asia/Jakarta\";}s:9:\"is_unread\";b:0;s:10:\"created_at\";s:9:\"1 day ago\";}i:3;a:7:{s:2:\"id\";s:36:\"bc115013-b504-4146-b053-db56f963912f\";s:7:\"message\";s:82:\"Anda sedang meminjam barang \"IMPACT\". Harap kembalikan sebelum tanggal 09 Apr 2026\";s:4:\"type\";s:7:\"ongoing\";s:3:\"url\";s:36:\"http://127.0.0.1:8000/user/dashboard\";s:7:\"read_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-08 23:27:08.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:12:\"Asia/Jakarta\";}s:9:\"is_unread\";b:0;s:10:\"created_at\";s:9:\"1 day ago\";}i:4;a:7:{s:2:\"id\";s:36:\"a4e4328d-19b6-446c-ba7f-f22a6bcbae08\";s:7:\"message\";s:85:\"Anda sedang meminjam barang \"LEM AIBON\". Harap kembalikan sebelum tanggal 09 Apr 2026\";s:4:\"type\";s:7:\"ongoing\";s:3:\"url\";s:36:\"http://127.0.0.1:8000/user/dashboard\";s:7:\"read_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-08 23:27:08.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:12:\"Asia/Jakarta\";}s:9:\"is_unread\";b:0;s:10:\"created_at\";s:9:\"1 day ago\";}i:5;a:7:{s:2:\"id\";s:36:\"76ee2a29-80f9-48a0-9025-b0ba5c9d38b5\";s:7:\"message\";s:45:\"Peminjaman barang \"TANG ORING\" telah selesai.\";s:4:\"type\";s:4:\"info\";s:3:\"url\";s:44:\"http://127.0.0.1:8000/user/borrowing/history\";s:7:\"read_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-06 08:56:11.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:12:\"Asia/Jakarta\";}s:9:\"is_unread\";b:0;s:10:\"created_at\";s:10:\"4 days ago\";}i:6;a:7:{s:2:\"id\";s:36:\"c7fb9abb-f95a-4cf9-ae68-7f717724082d\";s:7:\"message\";s:88:\"Barang \"TANG ORING\" belum dikembalikan. Sudah terlambat 6 hari dari tanggal 05 Apr 2026.\";s:4:\"type\";s:7:\"overdue\";s:3:\"url\";s:48:\"http://127.0.0.1:8000/user/borrowing/my-requests\";s:7:\"read_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-05 22:25:07.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:12:\"Asia/Jakarta\";}s:9:\"is_unread\";b:0;s:10:\"created_at\";s:10:\"4 days ago\";}i:7;a:7:{s:2:\"id\";s:36:\"6e602936-7aab-4089-9ead-ffe8ca9e14cd\";s:7:\"message\";s:142:\"Permintaan peminjaman \"LEM AIBON\" telah otomatis dibatalkan karena tanggal pinjam (04 Apr 2026) sudah terlewat 2 hari tanpa persetujuan admin.\";s:4:\"type\";s:7:\"expired\";s:3:\"url\";s:44:\"http://127.0.0.1:8000/user/borrowing/history\";s:7:\"read_at\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-05 19:01:33.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:12:\"Asia/Jakarta\";}s:9:\"is_unread\";b:0;s:10:\"created_at\";s:10:\"4 days ago\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:12:\"unread_count\";i:0;}', 1775787024),
('notifications:user:10:unread_count', 'i:0;', 1775787024),
('notifications:user:15:recent', 'a:2:{s:13:\"notifications\";O:55:\"Illuminate\\Notifications\\DatabaseNotificationCollection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:12:\"unread_count\";i:0;}', 1775802155),
('notifications:user:15:unread_count', 'i:0;', 1775802155),
('setting_company_address', 'O:18:\"App\\Models\\Setting\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:9;s:3:\"key\";s:15:\"company_address\";s:5:\"value\";s:16:\"Cikarang Selatan\";s:4:\"type\";s:6:\"string\";s:5:\"label\";s:17:\"Alamat Perusahaan\";s:11:\"description\";s:26:\"Alamat lengkap perusahaan.\";s:10:\"created_at\";s:19:\"2026-04-04 02:24:03\";s:10:\"updated_at\";s:19:\"2026-04-10 08:16:04\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:9;s:3:\"key\";s:15:\"company_address\";s:5:\"value\";s:16:\"Cikarang Selatan\";s:4:\"type\";s:6:\"string\";s:5:\"label\";s:17:\"Alamat Perusahaan\";s:11:\"description\";s:26:\"Alamat lengkap perusahaan.\";s:10:\"created_at\";s:19:\"2026-04-04 02:24:03\";s:10:\"updated_at\";s:19:\"2026-04-10 08:16:04\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:5:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"label\";i:4;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1775802251),
('setting_company_logo', 'O:18:\"App\\Models\\Setting\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:12;s:3:\"key\";s:12:\"company_logo\";s:5:\"value\";s:23:\"images/company-logo.png\";s:4:\"type\";s:6:\"string\";s:5:\"label\";s:15:\"Logo Perusahaan\";s:11:\"description\";s:58:\"Path ke file logo perusahaan (diisi otomatis saat upload).\";s:10:\"created_at\";s:19:\"2026-04-04 02:24:03\";s:10:\"updated_at\";s:19:\"2026-04-04 13:47:03\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:12;s:3:\"key\";s:12:\"company_logo\";s:5:\"value\";s:23:\"images/company-logo.png\";s:4:\"type\";s:6:\"string\";s:5:\"label\";s:15:\"Logo Perusahaan\";s:11:\"description\";s:58:\"Path ke file logo perusahaan (diisi otomatis saat upload).\";s:10:\"created_at\";s:19:\"2026-04-04 02:24:03\";s:10:\"updated_at\";s:19:\"2026-04-04 13:47:03\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:5:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"label\";i:4;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1775802530),
('setting_company_name', 'O:18:\"App\\Models\\Setting\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:7;s:3:\"key\";s:12:\"company_name\";s:5:\"value\";s:23:\"PT. INDONESIA CHEMI-CON\";s:4:\"type\";s:6:\"string\";s:5:\"label\";s:15:\"Nama Perusahaan\";s:11:\"description\";s:44:\"Nama perusahaan yang ditampilkan di laporan.\";s:10:\"created_at\";s:19:\"2026-04-04 02:24:03\";s:10:\"updated_at\";s:19:\"2026-04-10 08:16:04\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:7;s:3:\"key\";s:12:\"company_name\";s:5:\"value\";s:23:\"PT. INDONESIA CHEMI-CON\";s:4:\"type\";s:6:\"string\";s:5:\"label\";s:15:\"Nama Perusahaan\";s:11:\"description\";s:44:\"Nama perusahaan yang ditampilkan di laporan.\";s:10:\"created_at\";s:19:\"2026-04-04 02:24:03\";s:10:\"updated_at\";s:19:\"2026-04-10 08:16:04\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:5:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"label\";i:4;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1775802530),
('setting_company_phone', 'O:18:\"App\\Models\\Setting\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:10;s:3:\"key\";s:13:\"company_phone\";s:5:\"value\";s:14:\"(021) 12345678\";s:4:\"type\";s:6:\"string\";s:5:\"label\";s:13:\"Nomor Telepon\";s:11:\"description\";s:25:\"Nomor telepon perusahaan.\";s:10:\"created_at\";s:19:\"2026-04-04 02:24:03\";s:10:\"updated_at\";s:19:\"2026-04-10 08:16:04\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:10;s:3:\"key\";s:13:\"company_phone\";s:5:\"value\";s:14:\"(021) 12345678\";s:4:\"type\";s:6:\"string\";s:5:\"label\";s:13:\"Nomor Telepon\";s:11:\"description\";s:25:\"Nomor telepon perusahaan.\";s:10:\"created_at\";s:19:\"2026-04-04 02:24:03\";s:10:\"updated_at\";s:19:\"2026-04-10 08:16:04\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:5:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"label\";i:4;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1775802251),
('setting_company_tagline', 'O:18:\"App\\Models\\Setting\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:8;s:3:\"key\";s:15:\"company_tagline\";s:5:\"value\";s:9:\"Inventory\";s:4:\"type\";s:6:\"string\";s:5:\"label\";s:20:\"Tagline / Keterangan\";s:11:\"description\";s:43:\"Tagline atau keterangan singkat perusahaan.\";s:10:\"created_at\";s:19:\"2026-04-04 02:24:03\";s:10:\"updated_at\";s:19:\"2026-04-05 00:54:18\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:8;s:3:\"key\";s:15:\"company_tagline\";s:5:\"value\";s:9:\"Inventory\";s:4:\"type\";s:6:\"string\";s:5:\"label\";s:20:\"Tagline / Keterangan\";s:11:\"description\";s:43:\"Tagline atau keterangan singkat perusahaan.\";s:10:\"created_at\";s:19:\"2026-04-04 02:24:03\";s:10:\"updated_at\";s:19:\"2026-04-05 00:54:18\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:5:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"label\";i:4;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1775802530),
('setting_low_stock_threshold', 'O:18:\"App\\Models\\Setting\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:5;s:3:\"key\";s:19:\"low_stock_threshold\";s:5:\"value\";s:1:\"1\";s:4:\"type\";s:7:\"integer\";s:5:\"label\";s:18:\"Batas Stok Minimum\";s:11:\"description\";s:77:\"Kirim notifikasi ke admin ketika stok item mencapai atau di bawah jumlah ini.\";s:10:\"created_at\";s:19:\"2026-04-03 23:54:22\";s:10:\"updated_at\";s:19:\"2026-04-08 23:31:34\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:5;s:3:\"key\";s:19:\"low_stock_threshold\";s:5:\"value\";s:1:\"1\";s:4:\"type\";s:7:\"integer\";s:5:\"label\";s:18:\"Batas Stok Minimum\";s:11:\"description\";s:77:\"Kirim notifikasi ke admin ketika stok item mencapai atau di bawah jumlah ini.\";s:10:\"created_at\";s:19:\"2026-04-03 23:54:22\";s:10:\"updated_at\";s:19:\"2026-04-08 23:31:34\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:5:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"label\";i:4;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1775802541),
('setting_max_borrow_days', 'O:18:\"App\\Models\\Setting\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:2:\"id\";i:1;s:3:\"key\";s:15:\"max_borrow_days\";s:5:\"value\";s:1:\"1\";s:4:\"type\";s:7:\"integer\";s:5:\"label\";s:24:\"Maksimal Hari Peminjaman\";s:11:\"description\";s:52:\"Jumlah hari maksimal untuk setiap peminjaman barang.\";s:10:\"created_at\";s:19:\"2026-04-03 23:06:57\";s:10:\"updated_at\";s:19:\"2026-04-04 19:13:13\";}s:11:\"\0*\0original\";a:8:{s:2:\"id\";i:1;s:3:\"key\";s:15:\"max_borrow_days\";s:5:\"value\";s:1:\"1\";s:4:\"type\";s:7:\"integer\";s:5:\"label\";s:24:\"Maksimal Hari Peminjaman\";s:11:\"description\";s:52:\"Jumlah hari maksimal untuk setiap peminjaman barang.\";s:10:\"created_at\";s:19:\"2026-04-03 23:06:57\";s:10:\"updated_at\";s:19:\"2026-04-04 19:13:13\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:5:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"label\";i:4;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1775701740);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(22, 'VLA-5122', 'IH', 'active', '2025-10-02 13:16:10', '2025-10-02 13:16:10'),
(23, 'BAUT & MUR', 'KO', 'active', '2025-10-02 13:16:30', '2025-10-02 13:16:30'),
(24, 'JD-5100', 'EE', 'active', '2025-10-02 13:17:35', '2025-10-02 13:17:35'),
(25, 'VLA-5130', 'beda dengan vla 5130', 'active', '2025-10-06 14:03:09', '2025-10-06 14:03:09'),
(26, 'CAM CLUTCH', NULL, 'active', '2025-10-06 14:33:43', '2025-10-06 14:33:43'),
(27, 'SENSOR', 'SENSOR', 'active', '2025-10-06 14:34:00', '2025-10-06 14:34:00'),
(28, 'CDI-5110', 'CDI', 'active', '2025-10-06 14:34:44', '2025-10-06 14:34:44'),
(29, 'VLC-5130', NULL, 'active', '2025-10-06 14:35:27', '2025-10-06 14:35:27'),
(30, 'RELAY', 'WKWKMKWMK', 'active', '2025-10-06 14:36:22', '2025-10-06 14:36:22'),
(31, 'BEARING', 'BEARING', 'active', '2025-10-06 14:37:27', '2025-10-06 14:37:27'),
(33, 'PEMINJAMAN', 'KMKMD', 'active', '2025-10-06 15:06:09', '2025-10-07 15:39:56'),
(35, 'BUSA', 'BUSA', 'active', '2025-10-14 16:05:37', '2025-10-14 16:05:37'),
(36, 'VLC-5140', 'DI RAK PALING BELAKANG', 'active', '2025-10-14 16:06:08', '2025-10-14 16:06:08'),
(37, 'SOLENOIDE', 'GOOD', 'active', '2025-10-14 16:07:56', '2025-10-14 16:07:56'),
(38, 'SILINDER', 'TOP', 'active', '2025-10-14 16:08:11', '2025-10-14 16:08:11'),
(39, 'REPAIRE', 'MANTAP', 'active', '2025-10-14 16:08:26', '2025-10-14 16:08:26'),
(40, 'PROFILE', 'BEDA DENGAN AKUN', 'active', '2025-10-14 16:08:46', '2025-10-14 16:08:46'),
(41, 'BELT', 'BELT', 'active', '2025-10-14 16:31:51', '2025-10-14 16:31:51'),
(42, 'SPRING', 'PER', 'active', '2025-10-15 17:29:56', '2025-10-15 17:30:41'),
(43, 'PM-JD', 'PM', 'active', '2025-10-19 10:35:16', '2025-10-19 10:35:59'),
(46, 'ALAT PERKAKAS', 'PERKAKAS', 'active', '2025-11-10 15:25:34', '2025-11-10 15:35:31'),
(48, 'KUNCI', 'KUNCI', 'active', '2026-04-08 15:30:23', '2026-04-08 15:35:57');

-- --------------------------------------------------------

--
-- Table structure for table `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `tipe` enum('masuk','keluar') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventories`
--

INSERT INTO `inventories` (`id`, `item_id`, `user_id`, `tipe`, `jumlah`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
(16, 49, 1, 'masuk', 10, 'received', 'sensor', '2025-10-06 15:16:42', '2025-10-06 15:16:42'),
(17, 47, 1, 'masuk', 2, 'received', 'dsnj', '2025-10-06 15:17:48', '2025-10-06 15:17:48'),
(18, 47, 1, 'masuk', 1, 'received', 'jwnedjnw', '2025-10-06 15:18:16', '2025-10-06 15:18:16'),
(19, 49, 1, 'masuk', 2, 'received', 'nsnkw', '2025-10-06 15:18:46', '2025-10-06 15:18:46'),
(21, 48, 1, 'masuk', 4, 'received', 'fcgh', '2025-10-06 15:52:31', '2025-10-06 15:52:31'),
(22, 50, 1, 'masuk', 7, 'received', 'gfvgvhj', '2025-10-06 15:53:05', '2025-10-06 15:53:05'),
(23, 50, NULL, 'keluar', 1, 'to_production', 'elel', '2025-10-07 11:49:44', '2025-10-07 11:49:44'),
(24, 49, NULL, 'keluar', 2, 'to_production', ',LS,LS', '2025-10-08 16:50:30', '2025-10-08 16:50:30'),
(25, 55, 1, 'masuk', 8, 'received', 'GHFGFH', '2025-10-09 10:46:19', '2025-10-09 10:46:19'),
(26, 55, NULL, 'keluar', 2, 'to_production', 'kjjkj', '2025-10-09 11:43:22', '2025-10-09 11:43:22'),
(30, 50, NULL, 'keluar', 2, 'to_production', 'IOIO', '2025-10-16 14:04:09', '2025-10-16 14:04:09'),
(31, 55, NULL, 'keluar', 1, 'to_production', 'IOIO', '2025-10-16 14:04:09', '2025-10-16 14:04:09'),
(33, 50, 1, 'masuk', 6, 'received', NULL, '2025-10-16 14:57:18', '2025-10-16 14:57:18'),
(37, 61, NULL, 'masuk', 2, 'received', NULL, '2025-11-10 15:38:37', '2025-11-10 15:38:37'),
(38, 50, NULL, 'masuk', 1, 'received', NULL, '2025-11-10 15:38:37', '2025-11-10 15:38:37'),
(39, 61, NULL, 'masuk', 1, 'received', NULL, '2025-11-10 15:39:38', '2025-11-10 15:39:38'),
(40, 53, NULL, 'masuk', 2, 'received', NULL, '2025-11-10 15:42:04', '2025-11-10 15:42:04'),
(41, 61, NULL, 'keluar', 1, 'to_production', '-', '2025-11-20 12:41:04', '2025-11-20 12:41:04'),
(42, 48, NULL, 'masuk', 1, 'received', NULL, '2025-11-23 14:20:39', '2025-11-23 14:20:39'),
(43, 55, NULL, 'masuk', 2, 'received', NULL, '2025-11-23 14:20:40', '2025-11-23 14:20:40'),
(44, 55, 1, 'masuk', 1, 'received', NULL, '2026-03-31 06:58:37', '2026-03-31 06:58:37'),
(45, 50, 1, 'masuk', 3, 'received', NULL, '2026-03-31 06:58:37', '2026-03-31 06:58:37'),
(46, 55, 10, 'keluar', 1, 'to_production', 'Barang keluar untuk produksi', '2026-03-31 06:59:39', '2026-03-31 06:59:39'),
(47, 49, 10, 'keluar', 1, 'to_production', 'Barang keluar untuk produksi', '2026-03-31 06:59:39', '2026-03-31 06:59:39'),
(48, 61, 10, 'keluar', 2, 'to_production', 'Barang keluar untuk produksi', '2026-03-31 07:00:47', '2026-03-31 07:00:47'),
(49, 55, 10, 'keluar', 1, 'to_production', '11', '2026-04-02 07:16:11', '2026-04-02 07:16:11'),
(50, 49, 10, 'keluar', 1, 'to_production', '1', '2026-04-02 07:16:31', '2026-04-02 07:16:31'),
(51, 61, 1, 'masuk', 8, 'received', NULL, '2026-04-04 06:26:56', '2026-04-04 06:26:56'),
(52, 47, 1, 'masuk', 5, 'received', NULL, '2026-04-04 06:26:56', '2026-04-04 06:26:56'),
(53, 61, NULL, 'keluar', 3, 'to_production', 'Barang keluar untuk produksi', '2026-04-04 06:27:42', '2026-04-04 06:27:42'),
(54, 61, 1, 'masuk', 1, 'received', NULL, '2026-04-07 14:08:54', '2026-04-07 14:08:54'),
(55, 64, 15, 'masuk', 2, 'received', NULL, '2026-04-07 15:50:57', '2026-04-07 15:50:57'),
(56, 64, 1, 'masuk', 1, 'received', NULL, '2026-04-07 16:38:00', '2026-04-07 16:38:00'),
(57, 69, 1, 'masuk', 3, 'received', NULL, '2026-04-08 15:37:08', '2026-04-08 15:37:08'),
(58, 49, 10, 'keluar', 2, 'to_production', 'Barang keluar untuk produksi', '2026-04-08 15:44:26', '2026-04-08 15:44:26');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_id` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `stok_total` int NOT NULL DEFAULT '0',
  `stok_reguler` int NOT NULL DEFAULT '0',
  `stok_peminjaman` int NOT NULL DEFAULT '0',
  `harga` int NOT NULL DEFAULT '0',
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` enum('stok','peminjaman') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'stok'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `nama`, `kode`, `supplier_id`, `category_id`, `stok_total`, `stok_reguler`, `stok_peminjaman`, `harga`, `gambar`, `keterangan`, `created_at`, `updated_at`, `type`) VALUES
(47, 'LEM AIBON', NULL, 18, 33, 6, 0, 6, 0, 'images/1759763209_68e3db09693d2.jpg', 'LEM', '2025-10-06 15:06:49', '2026-04-08 16:23:51', 'peminjaman'),
(48, 'SOLDER', NULL, 18, 33, 5, 0, 5, 0, 'images/1759763286_68e3db56c1a9e.jpeg', 'solder', '2025-10-06 15:07:43', '2026-04-10 06:08:17', 'peminjaman'),
(49, 'SENSOR-FU 40', NULL, 19, 27, 6, 6, 0, 20000, 'images/1759763365_68e3dba58d3c9.jpg', 'sensor', '2025-10-06 15:09:25', '2026-04-10 06:08:04', 'stok'),
(50, 'BEARING-8399', NULL, 20, 31, 14, 14, 0, 3500, 'images/1759765917_68e3e59da0c9a.jpg', 'bearing', '2025-10-06 15:51:57', '2026-04-10 06:07:48', 'stok'),
(53, 'TANG ORING', NULL, 16, 33, 2, 0, 2, 0, 'images/1759852916_68e53974d1d56.jpg', 'oring', '2025-10-07 16:01:56', '2026-04-10 06:07:29', 'peminjaman'),
(55, 'PAPER XRAY', NULL, 16, 26, 6, 6, 0, 53897, 'images/1760006688_68e792206f8fc.jpg', 'paper', '2025-10-09 10:44:48', '2026-04-10 06:07:15', 'stok'),
(61, 'KUNCI LLL', NULL, 17, 46, 6, 6, 0, 15656, 'images/1762789068_691206ccd6690.jpg', 'perkakas', '2025-11-10 15:37:48', '2026-04-07 14:08:54', 'stok'),
(64, 'KUNCI RING', 'ITM-0064', 18, 26, 3, 3, 0, 25500, 'images/1775576983_69d5279771201.jpg', 'kunci ring', '2026-04-07 15:49:43', '2026-04-10 06:06:54', 'stok'),
(69, 'IMPACT', 'ITM-0069', 26, 48, 3, 0, 3, 0, 'images/1775662596_69d676049e6b3.jpg', 'MESIN', '2026-04-08 15:36:36', '2026-04-08 16:30:52', 'peminjaman');

-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

CREATE TABLE `logins` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `logged_in_at` timestamp NOT NULL DEFAULT '2025-09-28 16:20:52',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `logins`
--

INSERT INTO `logins` (`id`, `user_id`, `ip_address`, `user_agent`, `logged_in_at`, `created_at`, `updated_at`) VALUES
(79, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-14 15:27:31', '2025-10-14 15:27:31', '2025-10-14 15:27:31'),
(80, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-14 16:36:13', '2025-10-14 16:36:13', '2025-10-14 16:36:13'),
(81, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-14 16:40:57', '2025-10-14 16:40:57', '2025-10-14 16:40:57'),
(82, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-15 14:43:22', '2025-10-15 14:43:22', '2025-10-15 14:43:22'),
(83, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-15 15:35:39', '2025-10-15 15:35:39', '2025-10-15 15:35:39'),
(84, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-15 15:54:42', '2025-10-15 15:54:42', '2025-10-15 15:54:42'),
(85, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-15 17:28:42', '2025-10-15 17:28:42', '2025-10-15 17:28:42'),
(86, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-15 22:24:18', '2025-10-15 22:24:18', '2025-10-15 22:24:18'),
(87, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-16 11:53:45', '2025-10-16 11:53:45', '2025-10-16 11:53:45'),
(88, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-16 13:07:18', '2025-10-16 13:07:18', '2025-10-16 13:07:18'),
(89, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-16 14:54:52', '2025-10-16 14:54:52', '2025-10-16 14:54:52'),
(90, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-16 15:23:10', '2025-10-16 15:23:10', '2025-10-16 15:23:10'),
(91, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-16 15:26:34', '2025-10-16 15:26:34', '2025-10-16 15:26:34'),
(92, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-16 15:45:48', '2025-10-16 15:45:48', '2025-10-16 15:45:48'),
(93, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-17 12:04:56', '2025-10-17 12:04:56', '2025-10-17 12:04:56'),
(94, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-17 15:01:25', '2025-10-17 15:01:25', '2025-10-17 15:01:25'),
(95, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-17 15:07:18', '2025-10-17 15:07:18', '2025-10-17 15:07:18'),
(96, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-17 15:08:52', '2025-10-17 15:08:52', '2025-10-17 15:08:52'),
(97, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-17 15:12:36', '2025-10-17 15:12:36', '2025-10-17 15:12:36'),
(98, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-17 15:54:57', '2025-10-17 15:54:57', '2025-10-17 15:54:57'),
(99, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-18 04:00:39', '2025-10-18 04:00:39', '2025-10-18 04:00:39'),
(100, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-18 04:07:21', '2025-10-18 04:07:21', '2025-10-18 04:07:21'),
(101, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-18 04:16:40', '2025-10-18 04:16:40', '2025-10-18 04:16:40'),
(102, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-18 05:22:12', '2025-10-18 05:22:12', '2025-10-18 05:22:12'),
(103, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-19 05:32:11', '2025-10-19 05:32:11', '2025-10-19 05:32:11'),
(104, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-19 05:35:39', '2025-10-19 05:35:39', '2025-10-19 05:35:39'),
(105, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-19 05:36:48', '2025-10-19 05:36:48', '2025-10-19 05:36:48'),
(106, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-19 05:37:52', '2025-10-19 05:37:52', '2025-10-19 05:37:52'),
(107, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-19 05:50:21', '2025-10-19 05:50:21', '2025-10-19 05:50:21'),
(108, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-19 05:52:07', '2025-10-19 05:52:07', '2025-10-19 05:52:07'),
(109, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-19 06:08:19', '2025-10-19 06:08:19', '2025-10-19 06:08:19'),
(110, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-19 06:16:05', '2025-10-19 06:16:05', '2025-10-19 06:16:05'),
(111, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-19 07:06:09', '2025-10-19 07:06:09', '2025-10-19 07:06:09'),
(112, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-19 08:34:01', '2025-10-19 08:34:01', '2025-10-19 08:34:01'),
(113, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-19 10:33:25', '2025-10-19 10:33:25', '2025-10-19 10:33:25'),
(114, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-19 10:39:09', '2025-10-19 10:39:09', '2025-10-19 10:39:09'),
(115, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-19 13:38:20', '2025-10-19 13:38:20', '2025-10-19 13:38:20'),
(116, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-19 14:38:49', '2025-10-19 14:38:49', '2025-10-19 14:38:49'),
(117, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-19 15:13:20', '2025-10-19 15:13:20', '2025-10-19 15:13:20'),
(118, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-20 15:10:17', '2025-10-20 15:10:17', '2025-10-20 15:10:17'),
(119, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-20 15:28:10', '2025-10-20 15:28:10', '2025-10-20 15:28:10'),
(120, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-22 13:27:52', '2025-10-22 13:27:52', '2025-10-22 13:27:52'),
(121, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-22 15:01:06', '2025-10-22 15:01:06', '2025-10-22 15:01:06'),
(122, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-23 11:55:51', '2025-10-23 11:55:51', '2025-10-23 11:55:51'),
(123, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-23 14:09:27', '2025-10-23 14:09:27', '2025-10-23 14:09:27'),
(124, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-23 14:14:48', '2025-10-23 14:14:48', '2025-10-23 14:14:48'),
(125, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-23 14:15:39', '2025-10-23 14:15:39', '2025-10-23 14:15:39'),
(126, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-23 14:53:44', '2025-10-23 14:53:44', '2025-10-23 14:53:44'),
(127, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-24 15:09:41', '2025-10-24 15:09:41', '2025-10-24 15:09:41'),
(128, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-24 15:42:41', '2025-10-24 15:42:41', '2025-10-24 15:42:41'),
(129, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-24 23:19:04', '2025-10-24 23:19:04', '2025-10-24 23:19:04'),
(130, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-24 23:20:46', '2025-10-24 23:20:46', '2025-10-24 23:20:46'),
(131, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-25 01:50:01', '2025-10-25 01:50:01', '2025-10-25 01:50:01'),
(132, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-25 02:20:13', '2025-10-25 02:20:13', '2025-10-25 02:20:13'),
(133, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-26 10:44:03', '2025-10-26 10:44:03', '2025-10-26 10:44:03'),
(134, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-27 14:31:38', '2025-10-27 14:31:38', '2025-10-27 14:31:38'),
(135, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-28 14:45:35', '2025-10-28 14:45:35', '2025-10-28 14:45:35'),
(136, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-28 14:50:26', '2025-10-28 14:50:26', '2025-10-28 14:50:26'),
(137, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-28 16:28:22', '2025-10-28 16:28:22', '2025-10-28 16:28:22'),
(138, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-29 16:46:45', '2025-10-29 16:46:45', '2025-10-29 16:46:45'),
(139, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-31 07:28:11', '2025-10-31 07:28:11', '2025-10-31 07:28:11'),
(140, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-31 12:06:51', '2025-10-31 12:06:51', '2025-10-31 12:06:51'),
(141, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-31 16:41:09', '2025-10-31 16:41:09', '2025-10-31 16:41:09'),
(142, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-11-01 14:16:19', '2025-11-01 14:16:19', '2025-11-01 14:16:19'),
(143, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-11-02 00:11:51', '2025-11-02 00:11:51', '2025-11-02 00:11:51'),
(147, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-03 10:57:26', '2025-11-03 10:57:26', '2025-11-03 10:57:26'),
(154, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-04 00:31:49', '2025-11-04 00:31:49', '2025-11-04 00:31:49'),
(156, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-04 00:32:26', '2025-11-04 00:32:26', '2025-11-04 00:32:26'),
(159, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-04 01:10:00', '2025-11-04 01:10:00', '2025-11-04 01:10:00'),
(161, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-04 01:16:35', '2025-11-04 01:16:35', '2025-11-04 01:16:35'),
(163, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-05 07:10:19', '2025-11-05 07:10:19', '2025-11-05 07:10:19'),
(164, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-06 15:23:24', '2025-11-06 15:23:24', '2025-11-06 15:23:24'),
(165, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-06 16:50:38', '2025-11-06 16:50:38', '2025-11-06 16:50:38'),
(166, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-06 16:52:35', '2025-11-06 16:52:35', '2025-11-06 16:52:35'),
(168, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-06 17:02:14', '2025-11-06 17:02:14', '2025-11-06 17:02:14'),
(177, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-20 14:04:23', '2025-11-20 14:04:23', '2025-11-20 14:04:23'),
(178, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-20 14:04:48', '2025-11-20 14:04:48', '2025-11-20 14:04:48'),
(180, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-20 14:15:53', '2025-11-20 14:15:53', '2025-11-20 14:15:53'),
(182, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-20 14:16:51', '2025-11-20 14:16:51', '2025-11-20 14:16:51'),
(183, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-20 17:06:05', '2025-11-20 17:06:05', '2025-11-20 17:06:05'),
(187, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-20 17:25:58', '2025-11-20 17:25:58', '2025-11-20 17:25:58'),
(189, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-20 17:30:46', '2025-11-20 17:30:46', '2025-11-20 17:30:46'),
(191, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-20 17:39:23', '2025-11-20 17:39:23', '2025-11-20 17:39:23'),
(193, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-20 17:40:20', '2025-11-20 17:40:20', '2025-11-20 17:40:20'),
(198, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-20 22:39:56', '2025-11-20 22:39:56', '2025-11-20 22:39:56'),
(199, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-21 06:12:21', '2025-11-21 06:12:21', '2025-11-21 06:12:21'),
(202, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-21 12:14:14', '2025-11-21 12:14:14', '2025-11-21 12:14:14'),
(204, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 14:11:31', '2025-11-23 14:11:31', '2025-11-23 14:11:31'),
(206, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 01:07:53', '2025-11-26 01:07:53', '2025-11-26 01:07:53'),
(208, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 01:41:49', '2025-11-26 01:41:49', '2025-11-26 01:41:49'),
(211, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 02:10:35', '2025-11-26 02:10:35', '2025-11-26 02:10:35'),
(213, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 02:39:37', '2025-11-26 02:39:37', '2025-11-26 02:39:37'),
(214, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 02:41:33', '2025-11-26 02:41:33', '2025-11-26 02:41:33'),
(216, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 02:51:26', '2025-11-26 02:51:26', '2025-11-26 02:51:26'),
(220, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 03:00:42', '2025-11-26 03:00:42', '2025-11-26 03:00:42'),
(222, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 03:07:51', '2025-11-26 03:07:51', '2025-11-26 03:07:51'),
(224, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 03:23:13', '2025-11-26 03:23:13', '2025-11-26 03:23:13'),
(228, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 04:56:31', '2025-11-26 04:56:31', '2025-11-26 04:56:31'),
(230, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 04:57:37', '2025-11-26 04:57:37', '2025-11-26 04:57:37'),
(232, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 05:22:20', '2025-11-26 05:22:20', '2025-11-26 05:22:20'),
(256, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-05 03:31:27', '2026-02-05 03:31:27', '2026-02-05 03:31:27'),
(257, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-05 03:34:40', '2026-02-05 03:34:40', '2026-02-05 03:34:40'),
(259, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-05 03:44:21', '2026-02-05 03:44:21', '2026-02-05 03:44:21'),
(261, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-05 04:04:49', '2026-02-05 04:04:49', '2026-02-05 04:04:49'),
(263, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-05 04:06:12', '2026-02-05 04:06:12', '2026-02-05 04:06:12'),
(265, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-05 04:15:53', '2026-02-05 04:15:53', '2026-02-05 04:15:53'),
(267, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-05 04:17:02', '2026-02-05 04:17:02', '2026-02-05 04:17:02'),
(268, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-05 04:17:31', '2026-02-05 04:17:31', '2026-02-05 04:17:31'),
(270, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-05 04:21:29', '2026-02-05 04:21:29', '2026-02-05 04:21:29'),
(271, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-05 04:21:52', '2026-02-05 04:21:52', '2026-02-05 04:21:52'),
(272, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-05 04:23:17', '2026-02-05 04:23:17', '2026-02-05 04:23:17'),
(273, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-05 04:28:57', '2026-02-05 04:28:57', '2026-02-05 04:28:57'),
(274, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-05 06:19:42', '2026-02-05 06:19:42', '2026-02-05 06:19:42'),
(275, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-05 06:26:28', '2026-02-05 06:26:28', '2026-02-05 06:26:28'),
(276, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-05 06:27:11', '2026-02-05 06:27:11', '2026-02-05 06:27:11'),
(277, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-30 03:19:18', '2026-03-30 03:19:18', '2026-03-30 03:19:18'),
(278, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-31 06:47:59', '2026-03-31 06:47:59', '2026-03-31 06:47:59'),
(279, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-31 06:48:51', '2026-03-31 06:48:51', '2026-03-31 06:48:51'),
(280, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-31 06:54:31', '2026-03-31 06:54:31', '2026-03-31 06:54:31'),
(281, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-31 07:01:12', '2026-03-31 07:01:12', '2026-03-31 07:01:12'),
(282, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-31 07:02:29', '2026-03-31 07:02:29', '2026-03-31 07:02:29'),
(283, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-31 07:03:35', '2026-03-31 07:03:35', '2026-03-31 07:03:35'),
(284, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-03-31 07:04:01', '2026-03-31 07:04:01', '2026-03-31 07:04:01'),
(285, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-01 04:11:32', '2026-04-01 04:11:32', '2026-04-01 04:11:32'),
(286, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-01 07:31:24', '2026-04-01 07:31:24', '2026-04-01 07:31:24'),
(287, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-01 13:26:21', '2026-04-01 13:26:21', '2026-04-01 13:26:21'),
(288, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-01 13:46:17', '2026-04-01 13:46:17', '2026-04-01 13:46:17'),
(289, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-01 14:46:48', '2026-04-01 14:46:48', '2026-04-01 14:46:48'),
(290, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-02 04:34:26', '2026-04-02 04:34:26', '2026-04-02 04:34:26'),
(291, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-02 04:35:01', '2026-04-02 04:35:01', '2026-04-02 04:35:01'),
(292, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-02 04:35:54', '2026-04-02 04:35:54', '2026-04-02 04:35:54'),
(293, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-02 06:56:54', '2026-04-02 06:56:54', '2026-04-02 06:56:54'),
(294, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-02 11:38:09', '2026-04-02 11:38:09', '2026-04-02 11:38:09'),
(295, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-03 15:15:44', '2026-04-03 15:15:44', '2026-04-03 15:15:44'),
(296, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-03 16:16:37', '2026-04-03 16:16:37', '2026-04-03 16:16:37'),
(297, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-03 16:20:33', '2026-04-03 16:20:33', '2026-04-03 16:20:33'),
(298, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-03 16:20:46', '2026-04-03 16:20:46', '2026-04-03 16:20:46'),
(299, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-03 16:27:06', '2026-04-03 16:27:06', '2026-04-03 16:27:06'),
(300, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-03 16:43:46', '2026-04-03 16:43:46', '2026-04-03 16:43:46'),
(301, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-03 16:44:17', '2026-04-03 16:44:17', '2026-04-03 16:44:17'),
(302, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-03 16:44:58', '2026-04-03 16:44:58', '2026-04-03 16:44:58'),
(303, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-03 17:06:43', '2026-04-03 17:06:43', '2026-04-03 17:06:43'),
(304, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-03 17:06:51', '2026-04-03 17:06:51', '2026-04-03 17:06:51'),
(305, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-03 17:38:57', '2026-04-03 17:38:57', '2026-04-03 17:38:57'),
(306, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-03 17:42:41', '2026-04-03 17:42:41', '2026-04-03 17:42:41'),
(307, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 00:08:38', '2026-04-04 00:08:38', '2026-04-04 00:08:38'),
(308, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 00:50:40', '2026-04-04 00:50:40', '2026-04-04 00:50:40'),
(309, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 06:23:42', '2026-04-04 06:23:42', '2026-04-04 06:23:42'),
(310, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 10:59:24', '2026-04-04 10:59:24', '2026-04-04 10:59:24'),
(311, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 11:04:05', '2026-04-04 11:04:05', '2026-04-04 11:04:05'),
(312, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 12:11:40', '2026-04-04 12:11:40', '2026-04-04 12:11:40'),
(313, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 12:12:34', '2026-04-04 12:12:34', '2026-04-04 12:12:34'),
(314, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 12:18:31', '2026-04-04 12:18:31', '2026-04-04 12:18:31'),
(315, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 12:20:52', '2026-04-04 12:20:52', '2026-04-04 12:20:52'),
(316, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 12:21:38', '2026-04-04 12:21:38', '2026-04-04 12:21:38'),
(317, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 12:22:06', '2026-04-04 12:22:06', '2026-04-04 12:22:06'),
(318, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 12:29:45', '2026-04-04 12:29:45', '2026-04-04 12:29:45'),
(319, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 12:30:06', '2026-04-04 12:30:06', '2026-04-04 12:30:06'),
(320, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 12:34:18', '2026-04-04 12:34:18', '2026-04-04 12:34:18'),
(321, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 12:41:55', '2026-04-04 12:41:55', '2026-04-04 12:41:55'),
(322, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 12:58:04', '2026-04-04 12:58:04', '2026-04-04 12:58:04'),
(323, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 13:03:42', '2026-04-04 13:03:42', '2026-04-04 13:03:42'),
(324, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 13:09:18', '2026-04-04 13:09:18', '2026-04-04 13:09:18'),
(325, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 13:09:33', '2026-04-04 13:09:33', '2026-04-04 13:09:33'),
(326, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 13:12:41', '2026-04-04 13:12:41', '2026-04-04 13:12:41'),
(327, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 15:42:04', '2026-04-04 15:42:04', '2026-04-04 15:42:04'),
(328, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 15:45:19', '2026-04-04 15:45:19', '2026-04-04 15:45:19'),
(329, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 15:47:55', '2026-04-04 15:47:55', '2026-04-04 15:47:55'),
(330, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 15:48:21', '2026-04-04 15:48:21', '2026-04-04 15:48:21'),
(331, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 15:51:22', '2026-04-04 15:51:22', '2026-04-04 15:51:22'),
(332, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 15:59:49', '2026-04-04 15:59:49', '2026-04-04 15:59:49'),
(333, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 16:21:33', '2026-04-04 16:21:33', '2026-04-04 16:21:33'),
(334, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 16:34:11', '2026-04-04 16:34:11', '2026-04-04 16:34:11'),
(335, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 17:05:04', '2026-04-04 17:05:04', '2026-04-04 17:05:04'),
(336, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 17:12:59', '2026-04-04 17:12:59', '2026-04-04 17:12:59'),
(337, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-04 17:54:39', '2026-04-04 17:54:39', '2026-04-04 17:54:39'),
(338, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-05 00:49:19', '2026-04-05 00:49:19', '2026-04-05 00:49:19'),
(339, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-05 00:51:01', '2026-04-05 00:51:01', '2026-04-05 00:51:01'),
(340, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-05 00:54:55', '2026-04-05 00:54:55', '2026-04-05 00:54:55'),
(341, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-05 01:02:28', '2026-04-05 01:02:28', '2026-04-05 01:02:28'),
(342, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-05 01:21:42', '2026-04-05 01:21:42', '2026-04-05 01:21:42'),
(343, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-05 01:21:54', '2026-04-05 01:21:54', '2026-04-05 01:21:54'),
(344, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-05 02:21:52', '2026-04-05 02:21:52', '2026-04-05 02:21:52'),
(345, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-05 04:06:32', '2026-04-05 04:06:32', '2026-04-05 04:06:32'),
(346, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-05 05:26:56', '2026-04-05 05:26:56', '2026-04-05 05:26:56'),
(347, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-05 07:01:28', '2026-04-05 07:01:28', '2026-04-05 07:01:28'),
(348, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-05 11:44:59', '2026-04-05 11:44:59', '2026-04-05 11:44:59'),
(349, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-05 11:57:59', '2026-04-05 11:57:59', '2026-04-05 11:57:59'),
(350, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-05 12:02:21', '2026-04-05 12:02:21', '2026-04-05 12:02:21'),
(351, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-05 15:08:57', '2026-04-05 15:08:57', '2026-04-05 15:08:57'),
(352, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-05 15:09:06', '2026-04-05 15:09:06', '2026-04-05 15:09:06'),
(353, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-05 15:11:05', '2026-04-05 15:11:05', '2026-04-05 15:11:05'),
(354, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-05 15:15:44', '2026-04-05 15:15:44', '2026-04-05 15:15:44'),
(355, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-05 15:29:38', '2026-04-05 15:29:38', '2026-04-05 15:29:38'),
(356, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-06 01:36:39', '2026-04-06 01:36:39', '2026-04-06 01:36:39'),
(357, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-06 01:56:07', '2026-04-06 01:56:07', '2026-04-06 01:56:07'),
(358, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-06 01:56:47', '2026-04-06 01:56:47', '2026-04-06 01:56:47'),
(359, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-06 05:36:30', '2026-04-06 05:36:30', '2026-04-06 05:36:30'),
(360, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 11:23:16', '2026-04-07 11:23:16', '2026-04-07 11:23:16'),
(361, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 12:01:03', '2026-04-07 12:01:03', '2026-04-07 12:01:03'),
(362, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 12:12:35', '2026-04-07 12:12:35', '2026-04-07 12:12:35'),
(363, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 12:13:41', '2026-04-07 12:13:41', '2026-04-07 12:13:41'),
(364, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 12:26:29', '2026-04-07 12:26:29', '2026-04-07 12:26:29'),
(365, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 12:27:44', '2026-04-07 12:27:44', '2026-04-07 12:27:44'),
(366, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 12:28:33', '2026-04-07 12:28:33', '2026-04-07 12:28:33'),
(367, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 12:38:25', '2026-04-07 12:38:25', '2026-04-07 12:38:25'),
(368, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 12:39:09', '2026-04-07 12:39:09', '2026-04-07 12:39:09'),
(369, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 13:12:43', '2026-04-07 13:12:43', '2026-04-07 13:12:43'),
(370, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 13:26:40', '2026-04-07 13:26:40', '2026-04-07 13:26:40'),
(371, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 13:35:10', '2026-04-07 13:35:10', '2026-04-07 13:35:10'),
(372, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 13:48:21', '2026-04-07 13:48:21', '2026-04-07 13:48:21'),
(373, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 13:50:19', '2026-04-07 13:50:19', '2026-04-07 13:50:19'),
(374, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 14:06:57', '2026-04-07 14:06:57', '2026-04-07 14:06:57'),
(375, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 14:11:07', '2026-04-07 14:11:07', '2026-04-07 14:11:07'),
(376, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 14:19:21', '2026-04-07 14:19:21', '2026-04-07 14:19:21'),
(377, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 14:20:32', '2026-04-07 14:20:32', '2026-04-07 14:20:32'),
(378, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 14:22:58', '2026-04-07 14:22:58', '2026-04-07 14:22:58'),
(379, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 14:23:17', '2026-04-07 14:23:17', '2026-04-07 14:23:17'),
(380, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 14:32:38', '2026-04-07 14:32:38', '2026-04-07 14:32:38'),
(381, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 14:35:55', '2026-04-07 14:35:55', '2026-04-07 14:35:55'),
(382, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 14:36:13', '2026-04-07 14:36:13', '2026-04-07 14:36:13'),
(383, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 15:11:37', '2026-04-07 15:11:37', '2026-04-07 15:11:37'),
(384, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 15:34:50', '2026-04-07 15:34:50', '2026-04-07 15:34:50'),
(385, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 15:49:15', '2026-04-07 15:49:15', '2026-04-07 15:49:15'),
(386, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 15:56:32', '2026-04-07 15:56:32', '2026-04-07 15:56:32'),
(387, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 16:02:07', '2026-04-07 16:02:07', '2026-04-07 16:02:07'),
(388, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 16:21:17', '2026-04-07 16:21:17', '2026-04-07 16:21:17');
INSERT INTO `logins` (`id`, `user_id`, `ip_address`, `user_agent`, `logged_in_at`, `created_at`, `updated_at`) VALUES
(389, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 16:21:54', '2026-04-07 16:21:54', '2026-04-07 16:21:54'),
(390, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 16:36:31', '2026-04-07 16:36:31', '2026-04-07 16:36:31'),
(391, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 16:37:11', '2026-04-07 16:37:11', '2026-04-07 16:37:11'),
(392, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 16:45:47', '2026-04-07 16:45:47', '2026-04-07 16:45:47'),
(393, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 16:46:49', '2026-04-07 16:46:49', '2026-04-07 16:46:49'),
(394, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 16:54:33', '2026-04-07 16:54:33', '2026-04-07 16:54:33'),
(395, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 16:55:42', '2026-04-07 16:55:42', '2026-04-07 16:55:42'),
(396, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 17:01:57', '2026-04-07 17:01:57', '2026-04-07 17:01:57'),
(397, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 17:03:50', '2026-04-07 17:03:50', '2026-04-07 17:03:50'),
(398, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 17:18:49', '2026-04-07 17:18:49', '2026-04-07 17:18:49'),
(399, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 17:28:17', '2026-04-07 17:28:17', '2026-04-07 17:28:17'),
(400, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 17:34:46', '2026-04-07 17:34:46', '2026-04-07 17:34:46'),
(401, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 17:36:02', '2026-04-07 17:36:02', '2026-04-07 17:36:02'),
(402, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 17:46:59', '2026-04-07 17:46:59', '2026-04-07 17:46:59'),
(403, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 17:47:23', '2026-04-07 17:47:23', '2026-04-07 17:47:23'),
(404, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 17:47:43', '2026-04-07 17:47:43', '2026-04-07 17:47:43'),
(405, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 17:47:54', '2026-04-07 17:47:54', '2026-04-07 17:47:54'),
(406, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-07 17:48:09', '2026-04-07 17:48:09', '2026-04-07 17:48:09'),
(407, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 01:04:17', '2026-04-08 01:04:17', '2026-04-08 01:04:17'),
(408, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 01:04:36', '2026-04-08 01:04:36', '2026-04-08 01:04:36'),
(409, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 01:23:04', '2026-04-08 01:23:04', '2026-04-08 01:23:04'),
(410, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 01:43:52', '2026-04-08 01:43:52', '2026-04-08 01:43:52'),
(411, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 01:44:22', '2026-04-08 01:44:22', '2026-04-08 01:44:22'),
(412, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 02:16:21', '2026-04-08 02:16:21', '2026-04-08 02:16:21'),
(413, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 03:20:38', '2026-04-08 03:20:38', '2026-04-08 03:20:38'),
(414, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 03:30:51', '2026-04-08 03:30:51', '2026-04-08 03:30:51'),
(415, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 03:31:16', '2026-04-08 03:31:16', '2026-04-08 03:31:16'),
(416, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 03:49:44', '2026-04-08 03:49:44', '2026-04-08 03:49:44'),
(417, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 10:04:08', '2026-04-08 10:04:08', '2026-04-08 10:04:08'),
(418, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 12:23:17', '2026-04-08 12:23:17', '2026-04-08 12:23:17'),
(419, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 12:40:56', '2026-04-08 12:40:56', '2026-04-08 12:40:56'),
(420, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 12:41:31', '2026-04-08 12:41:31', '2026-04-08 12:41:31'),
(421, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 13:32:00', '2026-04-08 13:32:00', '2026-04-08 13:32:00'),
(422, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 15:13:56', '2026-04-08 15:13:56', '2026-04-08 15:13:56'),
(423, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 15:14:27', '2026-04-08 15:14:27', '2026-04-08 15:14:27'),
(424, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 15:15:42', '2026-04-08 15:15:42', '2026-04-08 15:15:42'),
(425, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 15:29:43', '2026-04-08 15:29:43', '2026-04-08 15:29:43'),
(426, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 15:51:42', '2026-04-08 15:51:42', '2026-04-08 15:51:42'),
(427, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 15:52:48', '2026-04-08 15:52:48', '2026-04-08 15:52:48'),
(428, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:07:23', '2026-04-08 16:07:23', '2026-04-08 16:07:23'),
(429, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:10:34', '2026-04-08 16:10:34', '2026-04-08 16:10:34'),
(430, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:13:36', '2026-04-08 16:13:36', '2026-04-08 16:13:36'),
(431, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:22:40', '2026-04-08 16:22:40', '2026-04-08 16:22:40'),
(432, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:22:48', '2026-04-08 16:22:48', '2026-04-08 16:22:48'),
(433, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:23:22', '2026-04-08 16:23:22', '2026-04-08 16:23:22'),
(434, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:23:35', '2026-04-08 16:23:35', '2026-04-08 16:23:35'),
(435, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:27:05', '2026-04-08 16:27:05', '2026-04-08 16:27:05'),
(436, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:27:37', '2026-04-08 16:27:37', '2026-04-08 16:27:37'),
(437, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:31:43', '2026-04-08 16:31:43', '2026-04-08 16:31:43'),
(438, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:32:52', '2026-04-08 16:32:52', '2026-04-08 16:32:52'),
(439, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:35:30', '2026-04-08 16:35:30', '2026-04-08 16:35:30'),
(440, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:35:45', '2026-04-08 16:35:45', '2026-04-08 16:35:45'),
(441, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:37:04', '2026-04-08 16:37:04', '2026-04-08 16:37:04'),
(442, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:40:04', '2026-04-08 16:40:04', '2026-04-08 16:40:04'),
(443, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:40:15', '2026-04-08 16:40:15', '2026-04-08 16:40:15'),
(444, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:51:48', '2026-04-08 16:51:48', '2026-04-08 16:51:48'),
(445, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:52:00', '2026-04-08 16:52:00', '2026-04-08 16:52:00'),
(446, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:52:16', '2026-04-08 16:52:16', '2026-04-08 16:52:16'),
(447, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:54:40', '2026-04-08 16:54:40', '2026-04-08 16:54:40'),
(448, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:55:30', '2026-04-08 16:55:30', '2026-04-08 16:55:30'),
(449, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-08 16:58:27', '2026-04-08 16:58:27', '2026-04-08 16:58:27'),
(450, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-09 01:03:43', '2026-04-09 01:03:43', '2026-04-09 01:03:43'),
(451, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-09 01:15:42', '2026-04-09 01:15:42', '2026-04-09 01:15:42'),
(452, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-09 01:17:04', '2026-04-09 01:17:04', '2026-04-09 01:17:04'),
(453, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-09 01:20:05', '2026-04-09 01:20:05', '2026-04-09 01:20:05'),
(454, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-09 02:03:28', '2026-04-09 02:03:28', '2026-04-09 02:03:28'),
(455, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-09 02:04:44', '2026-04-09 02:04:44', '2026-04-09 02:04:44'),
(456, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-09 02:05:45', '2026-04-09 02:05:45', '2026-04-09 02:05:45'),
(457, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-09 02:06:20', '2026-04-09 02:06:20', '2026-04-09 02:06:20'),
(458, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-09 02:14:10', '2026-04-09 02:14:10', '2026-04-09 02:14:10'),
(459, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-09 02:16:34', '2026-04-09 02:16:34', '2026-04-09 02:16:34'),
(460, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-09 02:23:34', '2026-04-09 02:23:34', '2026-04-09 02:23:34'),
(461, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 01:14:48', '2026-04-10 01:14:48', '2026-04-10 01:14:48'),
(462, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 01:20:33', '2026-04-10 01:20:33', '2026-04-10 01:20:33'),
(463, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 01:41:09', '2026-04-10 01:41:09', '2026-04-10 01:41:09'),
(464, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 01:51:11', '2026-04-10 01:51:11', '2026-04-10 01:51:11'),
(465, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 01:51:14', '2026-04-10 01:51:14', '2026-04-10 01:51:14'),
(466, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 01:51:17', '2026-04-10 01:51:17', '2026-04-10 01:51:17'),
(467, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 02:09:14', '2026-04-10 02:09:14', '2026-04-10 02:09:14'),
(468, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 02:10:13', '2026-04-10 02:10:13', '2026-04-10 02:10:13'),
(469, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 05:48:26', '2026-04-10 05:48:26', '2026-04-10 05:48:26'),
(470, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 06:20:59', '2026-04-10 06:20:59', '2026-04-10 06:20:59'),
(471, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 06:22:14', '2026-04-10 06:22:14', '2026-04-10 06:22:14'),
(472, 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 06:22:22', '2026-04-10 06:22:22', '2026-04-10 06:22:22'),
(473, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '2026-04-10 06:22:34', '2026-04-10 06:22:34', '2026-04-10 06:22:34');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_05_000000_create_categories_table', 1),
(5, '2025_01_06_000000_create_items_table', 1),
(6, '2025_05_10_052459_create_inventories_table', 1),
(7, '2025_06_19_121308_create_logins_table', 1),
(8, '2025_08_30_073720_create_suppliers_table', 1),
(9, '2025_08_30_105409_create_companies_table', 1),
(10, '2025_09_07_072147_create_borrowings_table', 1),
(11, '2025_09_07_134500_add_database_indexes', 1),
(15, '2025_09_07_134718_add_type_to_items_table', 2),
(16, '2025_09_07_184503_add_foreign_keys_to_items_table_final', 2),
(17, '2025_09_08_212207_create_borrowing_requests_table', 2),
(18, '2025_09_29_214154_add_user_id_and_keterangan_to_inventories_table', 3),
(19, '2025_09_29_222920_add_deleted_at_to_items_table', 4),
(20, '2025_01_10_145500_change_bio_column_to_text', 5),
(21, '2025_01_10_152500_remove_soft_delete_from_items', 6),
(22, '2025_01_10_164500_add_completed_at_to_borrowing_requests', 7),
(23, '2025_10_08_233101_add_kode_to_items_table', 8),
(24, '2025_10_09_180015_add_timestamps_to_users_table_if_missing', 9),
(26, '2025_10_09_231800_create_item_images_table', 10),
(27, '2025_11_04_072010_create_cache_table', 11),
(28, '2025_11_21_001010_create_borrowing_carts_table', 12),
(29, '2025_11_21_001917_add_batch_id_to_borrowing_requests_table', 13),
(30, '2025_11_26_095437_add_cancelled_status_to_borrowing_requests_table', 14),
(31, '2026_02_05_113335_create_notifications_table', 15),
(32, '2026_04_03_000001_create_settings_table', 16),
(33, '2026_04_03_000002_add_overdue_notified_at_to_borrowing_requests', 16),
(34, '2026_04_04_022329_add_company_settings_to_settings_table', 17),
(35, '2026_04_05_085944_add_stock_alert_settings_to_settings_table', 18),
(36, '2026_04_07_185358_add_operator_role_to_users_table', 19),
(37, '2026_04_07_210000_create_audit_logs_table', 20),
(38, '2026_04_07_220000_add_missing_columns_to_items_table', 21);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0d235a1a-06b8-4fc0-b312-73b9ae0b3c47', 'App\\Notifications\\OngoingBorrowingNotification', 'App\\Models\\User', 10, '{\"message\":\"Anda sedang meminjam barang \\\"TANG ORING\\\". Harap kembalikan sebelum tanggal 03 Apr 2026\",\"borrowing_request_id\":37,\"item_name\":\"TANG ORING\",\"due_date\":\"2026-04-03\",\"status\":\"ongoing\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/my-requests\"}', '2026-04-03 16:44:20', '2026-04-03 16:27:32', '2026-04-03 16:44:20'),
('0d52db5c-0030-4a1a-98f8-23b760b04ab7', 'App\\Notifications\\OngoingBorrowingNotification', 'App\\Models\\User', 10, '{\"type\":\"ongoing\",\"message\":\"Anda sedang meminjam barang \\\"IMPACT\\\". Harap kembalikan sebelum tanggal 09 Apr 2026\",\"borrowing_request_id\":null,\"borrowing_id\":19,\"item_name\":\"IMPACT\",\"due_date\":\"2026-04-09\",\"status\":\"ongoing\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/dashboard\"}', '2026-04-09 02:23:37', '2026-04-08 16:30:47', '2026-04-09 02:23:37'),
('11d8159f-e96b-47e6-b02a-5b24bb9402e9', 'App\\Notifications\\BorrowingRejectedNotification', 'App\\Models\\User', 10, '{\"message\":\"Permintaan peminjaman Anda untuk barang \\\"LEM AIBON\\\" telah ditolak.\",\"borrowing_request_id\":38,\"item_name\":\"LEM AIBON\",\"status\":\"rejected\",\"admin_notes\":\"kk\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/show\\/38\"}', '2026-04-04 12:34:06', '2026-04-04 12:21:12', '2026-04-04 12:34:06'),
('162b0f39-3320-4916-bdcc-ce7022b8d968', 'App\\Notifications\\LowStockNotification', 'App\\Models\\User', 12, '{\"type\":\"low_stock\",\"message\":\"Stok item \\\"SOLDER\\\" hampir habis! Stok saat ini: 5 unit (batas peringatan: 5 unit).\",\"item_id\":48,\"item_name\":\"SOLDER\",\"stok\":5,\"threshold\":5,\"url\":\"http:\\/\\/localhost\\/admin\\/inventory\\/show\\/48\"}', NULL, '2026-04-03 16:56:17', '2026-04-03 16:56:17'),
('1a9589cc-f2b3-4dd2-b7c4-2a26c10f2a7e', 'App\\Notifications\\LowStockNotification', 'App\\Models\\User', 1, '{\"type\":\"low_stock\",\"message\":\"Stok item \\\"TANG ORING\\\" hampir habis! Stok saat ini: 1 unit (batas peringatan: 5 unit).\",\"item_id\":53,\"item_name\":\"TANG ORING\",\"stok\":1,\"threshold\":5,\"url\":\"http:\\/\\/localhost\\/admin\\/inventory\\/show\\/53\"}', '2026-04-03 18:37:14', '2026-04-03 16:56:17', '2026-04-03 18:37:14'),
('1aba1c74-099f-4652-afb7-938f4a8d1311', 'App\\Notifications\\BorrowingCompletedNotification', 'App\\Models\\User', 10, '{\"message\":\"Peminjaman barang \\\"SOLDER\\\" telah selesai.\",\"borrowing_request_id\":32,\"item_name\":\"SOLDER\",\"status\":\"completed\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/history\"}', '2026-04-03 16:27:11', '2026-02-05 06:22:41', '2026-04-03 16:27:11'),
('2ed23c49-7761-4679-bff6-f91811630d86', 'App\\Notifications\\OngoingBorrowingNotification', 'App\\Models\\User', 10, '{\"message\":\"Anda sedang meminjam barang \\\"LEM AIBON\\\". Harap kembalikan sebelum tanggal 02 Apr 2026\",\"borrowing_request_id\":34,\"item_name\":\"LEM AIBON\",\"due_date\":\"2026-04-02\",\"status\":\"ongoing\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/my-requests\"}', '2026-04-03 16:27:11', '2026-03-31 07:03:40', '2026-04-03 16:27:11'),
('3578aa81-35ce-4c46-9c86-22f50322a90b', 'App\\Notifications\\ItemOverdueNotification', 'App\\Models\\User', 10, '{\"type\":\"overdue\",\"message\":\"Barang \\\"TANG ORING\\\" belum dikembalikan. Sudah terlambat -1.3007425345486 hari dari tanggal 03 Apr 2026.\",\"borrowing_request_id\":37,\"item_name\":\"TANG ORING\",\"due_date\":\"2026-04-03\",\"days_overdue\":-1.3007425345486112,\"url\":\"http:\\/\\/localhost\\/user\\/borrowing\\/my-requests\"}', '2026-04-04 11:04:08', '2026-04-04 00:13:04', '2026-04-04 11:04:08'),
('3b85b7a2-85a5-467d-86b5-1b52a2975c26', 'App\\Notifications\\LowStockNotification', 'App\\Models\\User', 12, '{\"type\":\"low_stock\",\"message\":\"Stok item \\\"KUNCI LLL\\\" hampir habis! Stok saat ini: 5 unit (batas peringatan: 5 unit).\",\"item_id\":61,\"item_name\":\"KUNCI LLL\",\"stok\":5,\"threshold\":5,\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/inventory\\/show\\/61\"}', NULL, '2026-04-04 06:27:42', '2026-04-04 06:27:42'),
('3f524905-5d03-4293-aa9a-08a2e0a4d189', 'App\\Notifications\\BorrowingApprovedNotification', 'App\\Models\\User', 10, '{\"message\":\"Permintaan peminjaman Anda untuk barang \\\"SOLDER\\\" telah disetujui.\",\"borrowing_request_id\":33,\"item_name\":\"SOLDER\",\"status\":\"approved\",\"admin_notes\":\"okkk\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/show\\/33\"}', '2026-04-03 16:27:11', '2026-02-05 06:27:25', '2026-04-03 16:27:11'),
('44bdc843-d612-405f-8b10-38019c288a4d', 'App\\Notifications\\OngoingBorrowingNotification', 'App\\Models\\User', 10, '{\"message\":\"Anda sedang meminjam barang \\\"LEM AIBON\\\". Harap kembalikan sebelum tanggal 03 Apr 2026\",\"borrowing_request_id\":36,\"item_name\":\"LEM AIBON\",\"due_date\":\"2026-04-03\",\"status\":\"ongoing\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/my-requests\"}', '2026-04-03 16:44:20', '2026-04-03 16:27:32', '2026-04-03 16:44:20'),
('4daaa119-5b4d-4bbb-b878-f1608905f598', 'App\\Notifications\\LowStockNotification', 'App\\Models\\User', 1, '{\"type\":\"low_stock\",\"message\":\"Stok item \\\"KUNCI LLL\\\" hampir habis! Stok saat ini: 5 unit (batas peringatan: 5 unit).\",\"item_id\":61,\"item_name\":\"KUNCI LLL\",\"stok\":5,\"threshold\":5,\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/inventory\\/show\\/61\"}', '2026-04-04 06:45:29', '2026-04-04 06:27:42', '2026-04-04 06:45:29'),
('4ee6e2d5-5a02-4856-bc6d-b7e26a1e4485', 'App\\Notifications\\ItemOverdueNotification', 'App\\Models\\User', 10, '{\"type\":\"overdue\",\"message\":\"Barang \\\"LEM AIBON\\\" belum dikembalikan. Sudah terlambat -1.3007421726157 hari dari tanggal 03 Apr 2026.\",\"borrowing_request_id\":36,\"item_name\":\"LEM AIBON\",\"due_date\":\"2026-04-03\",\"days_overdue\":-1.3007421726157407,\"url\":\"http:\\/\\/localhost\\/user\\/borrowing\\/my-requests\"}', '2026-04-04 11:04:08', '2026-04-04 00:13:04', '2026-04-04 11:04:08'),
('5819e93e-aeba-46db-a0e2-4ba0a32f59ce', 'App\\Notifications\\LowStockNotification', 'App\\Models\\User', 11, '{\"type\":\"low_stock\",\"message\":\"Stok item \\\"KUNCI LLL\\\" hampir habis! Stok saat ini: 5 unit (batas peringatan: 5 unit).\",\"item_id\":61,\"item_name\":\"KUNCI LLL\",\"stok\":5,\"threshold\":5,\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/inventory\\/show\\/61\"}', NULL, '2026-04-04 06:27:42', '2026-04-04 06:27:42'),
('5f2920ff-ed9b-4c60-8484-64f057708e19', 'App\\Notifications\\OngoingBorrowingNotification', 'App\\Models\\User', 10, '{\"message\":\"Anda sedang meminjam barang \\\"TANG ORING\\\". Harap kembalikan sebelum tanggal 02 Apr 2026\",\"borrowing_request_id\":35,\"item_name\":\"TANG ORING\",\"due_date\":\"2026-04-02\",\"status\":\"ongoing\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/my-requests\"}', '2026-04-03 16:27:11', '2026-03-31 07:03:40', '2026-04-03 16:27:11'),
('61148110-7fc6-4b58-bd1f-abb010336b4b', 'App\\Notifications\\BorrowingCompletedNotification', 'App\\Models\\User', 10, '{\"type\":\"completed\",\"message\":\"Peminjaman barang \\\"IMPACT\\\" telah selesai.\",\"borrowing_request_id\":null,\"borrowing_id\":18,\"item_name\":\"IMPACT\",\"status\":\"completed\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/dashboard\"}', '2026-04-08 16:27:08', '2026-04-08 16:26:33', '2026-04-08 16:27:08'),
('6899d78d-dca9-45de-8050-6a56c56f796c', 'App\\Notifications\\BorrowingCompletedNotification', 'App\\Models\\User', 10, '{\"message\":\"Peminjaman barang \\\"LEM AIBON\\\" telah selesai.\",\"borrowing_request_id\":34,\"item_name\":\"LEM AIBON\",\"status\":\"completed\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/history\"}', '2026-04-03 16:27:11', '2026-03-31 07:04:40', '2026-04-03 16:27:11'),
('6e602936-7aab-4089-9ead-ffe8ca9e14cd', 'App\\Notifications\\BorrowingExpiredNotification', 'App\\Models\\User', 10, '{\"type\":\"expired\",\"message\":\"Permintaan peminjaman \\\"LEM AIBON\\\" telah otomatis dibatalkan karena tanggal pinjam (04 Apr 2026) sudah terlewat 2 hari tanpa persetujuan admin.\",\"borrowing_request_id\":41,\"item_name\":\"LEM AIBON\",\"borrow_date\":\"2026-04-04\",\"days_past\":2,\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/history\"}', '2026-04-05 12:01:33', '2026-04-05 12:01:30', '2026-04-05 12:01:33'),
('70096cba-392a-4056-806b-136ad8f81d63', 'App\\Notifications\\LowStockNotification', 'App\\Models\\User', 11, '{\"type\":\"low_stock\",\"message\":\"Stok item \\\"SOLDER\\\" hampir habis! Stok saat ini: 5 unit (batas peringatan: 5 unit).\",\"item_id\":48,\"item_name\":\"SOLDER\",\"stok\":5,\"threshold\":5,\"url\":\"http:\\/\\/localhost\\/admin\\/inventory\\/show\\/48\"}', NULL, '2026-04-03 16:56:17', '2026-04-03 16:56:17'),
('76ee2a29-80f9-48a0-9025-b0ba5c9d38b5', 'App\\Notifications\\BorrowingCompletedNotification', 'App\\Models\\User', 10, '{\"message\":\"Peminjaman barang \\\"TANG ORING\\\" telah selesai.\",\"borrowing_request_id\":40,\"item_name\":\"TANG ORING\",\"status\":\"completed\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/history\"}', '2026-04-06 01:56:11', '2026-04-05 15:29:54', '2026-04-06 01:56:11'),
('7a065dd9-a9ff-441e-bf44-0e36cf924df7', 'App\\Notifications\\BorrowingApprovedNotification', 'App\\Models\\User', 10, '{\"message\":\"Permintaan peminjaman Anda untuk barang \\\"TANG ORING\\\" telah disetujui.\",\"borrowing_request_id\":35,\"item_name\":\"TANG ORING\",\"status\":\"approved\",\"admin_notes\":\"kkk\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/show\\/35\"}', '2026-04-03 16:27:11', '2026-03-31 07:03:05', '2026-04-03 16:27:11'),
('80dfc279-d36f-4827-8091-9be47fb36b92', 'App\\Notifications\\LowStockNotification', 'App\\Models\\User', 1, '{\"type\":\"low_stock\",\"message\":\"Stok item \\\"SOLDER\\\" hampir habis! Stok saat ini: 5 unit (batas peringatan: 5 unit).\",\"item_id\":48,\"item_name\":\"SOLDER\",\"stok\":5,\"threshold\":5,\"url\":\"http:\\/\\/localhost\\/admin\\/inventory\\/show\\/48\"}', '2026-04-03 18:37:14', '2026-04-03 16:56:17', '2026-04-03 18:37:14'),
('8ea6fad2-c414-4def-ae42-62bdd087c3e4', 'App\\Notifications\\BorrowingApprovedNotification', 'App\\Models\\User', 10, '{\"message\":\"Permintaan peminjaman Anda untuk barang \\\"LEM AIBON\\\" telah disetujui.\",\"borrowing_request_id\":34,\"item_name\":\"LEM AIBON\",\"status\":\"approved\",\"admin_notes\":\"aa\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/show\\/34\"}', '2026-04-03 16:27:11', '2026-03-31 07:03:16', '2026-04-03 16:27:11'),
('92b212fa-c569-4f15-8200-e5ee8bfe646d', 'App\\Notifications\\BorrowingCompletedNotification', 'App\\Models\\User', 10, '{\"type\":\"completed\",\"message\":\"Peminjaman barang \\\"IMPACT\\\" telah selesai.\",\"borrowing_request_id\":null,\"borrowing_id\":19,\"item_name\":\"IMPACT\",\"status\":\"completed\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/dashboard\"}', '2026-04-09 02:23:37', '2026-04-08 16:30:53', '2026-04-09 02:23:37'),
('99bdaef2-2d41-4b45-992a-740ad3044b75', 'App\\Notifications\\BorrowingCompletedNotification', 'App\\Models\\User', 10, '{\"message\":\"Peminjaman barang \\\"SOLDER\\\" telah selesai.\",\"borrowing_request_id\":33,\"item_name\":\"SOLDER\",\"status\":\"completed\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/history\"}', '2026-04-03 16:27:11', '2026-02-05 06:28:16', '2026-04-03 16:27:11'),
('9ad8dc34-1b16-49be-9f98-e166a93a53d4', 'App\\Notifications\\BorrowingApprovedNotification', 'App\\Models\\User', 10, '{\"message\":\"Permintaan peminjaman Anda untuk barang \\\"TANG ORING\\\" telah disetujui.\",\"borrowing_request_id\":37,\"item_name\":\"TANG ORING\",\"status\":\"approved\",\"admin_notes\":\"oo\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/show\\/37\"}', '2026-04-03 16:27:11', '2026-04-02 04:36:47', '2026-04-03 16:27:11'),
('9e8625f4-d9cc-4bd2-9e8e-9f3756aa6a11', 'App\\Notifications\\BorrowingCompletedNotification', 'App\\Models\\User', 10, '{\"message\":\"Peminjaman barang \\\"LEM AIBON\\\" telah selesai.\",\"borrowing_request_id\":36,\"item_name\":\"LEM AIBON\",\"status\":\"completed\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/history\"}', '2026-04-04 11:04:08', '2026-04-04 06:28:48', '2026-04-04 11:04:08'),
('a4e4328d-19b6-446c-ba7f-f22a6bcbae08', 'App\\Notifications\\OngoingBorrowingNotification', 'App\\Models\\User', 10, '{\"type\":\"ongoing\",\"message\":\"Anda sedang meminjam barang \\\"LEM AIBON\\\". Harap kembalikan sebelum tanggal 09 Apr 2026\",\"borrowing_request_id\":null,\"borrowing_id\":17,\"item_name\":\"LEM AIBON\",\"due_date\":\"2026-04-09\",\"status\":\"ongoing\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/dashboard\"}', '2026-04-08 16:27:08', '2026-04-08 16:23:12', '2026-04-08 16:27:08'),
('a88901a0-747c-4df0-a35c-d1f4782a1af2', 'App\\Notifications\\LowStockNotification', 'App\\Models\\User', 1, '{\"type\":\"low_stock\",\"message\":\"Stok item \\\"IMPACT\\\" hampir habis! Stok saat ini: 2 unit (batas peringatan: 2 unit).\",\"item_id\":69,\"item_name\":\"IMPACT\",\"stok\":2,\"threshold\":2,\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/inventory\\/show\\/69\"}', '2026-04-08 15:50:42', '2026-04-08 15:47:01', '2026-04-08 15:50:42'),
('bc115013-b504-4146-b053-db56f963912f', 'App\\Notifications\\OngoingBorrowingNotification', 'App\\Models\\User', 10, '{\"type\":\"ongoing\",\"message\":\"Anda sedang meminjam barang \\\"IMPACT\\\". Harap kembalikan sebelum tanggal 09 Apr 2026\",\"borrowing_request_id\":null,\"borrowing_id\":18,\"item_name\":\"IMPACT\",\"due_date\":\"2026-04-09\",\"status\":\"ongoing\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/dashboard\"}', '2026-04-08 16:27:08', '2026-04-08 16:26:15', '2026-04-08 16:27:08'),
('c1dccf40-ca67-43aa-b49d-ed7ba7629118', 'App\\Notifications\\BorrowingRejectedNotification', 'App\\Models\\User', 10, '{\"message\":\"Permintaan peminjaman Anda untuk barang \\\"SOLDER\\\" telah ditolak.\",\"borrowing_request_id\":39,\"item_name\":\"SOLDER\",\"status\":\"rejected\",\"admin_notes\":\"q\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/show\\/39\"}', '2026-04-04 12:34:06', '2026-04-04 12:21:19', '2026-04-04 12:34:06'),
('c2d511e8-c15e-43ad-964e-bc2a4d7428c2', 'App\\Notifications\\BorrowingApprovedNotification', 'App\\Models\\User', 10, '{\"message\":\"Permintaan peminjaman Anda untuk barang \\\"LEM AIBON\\\" telah disetujui.\",\"borrowing_request_id\":36,\"item_name\":\"LEM AIBON\",\"status\":\"approved\",\"admin_notes\":\"kk\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/show\\/36\"}', '2026-04-03 16:27:11', '2026-04-02 04:36:27', '2026-04-03 16:27:11'),
('c7d94b6d-3d62-43be-88b6-15d2dc7b4dc4', 'App\\Notifications\\OngoingBorrowingNotification', 'App\\Models\\User', 10, '{\"message\":\"Anda sedang meminjam barang \\\"SOLDER\\\". Harap kembalikan sebelum tanggal 06 Feb 2026\",\"borrowing_request_id\":32,\"item_name\":\"SOLDER\",\"due_date\":\"2026-02-06\",\"status\":\"ongoing\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/my-requests\"}', '2026-04-03 16:27:11', '2026-02-05 04:44:31', '2026-04-03 16:27:11'),
('c7df6151-1fbc-4d26-b8d2-923ba852c754', 'App\\Notifications\\BorrowingCompletedNotification', 'App\\Models\\User', 10, '{\"message\":\"Peminjaman barang \\\"TANG ORING\\\" telah selesai.\",\"borrowing_request_id\":35,\"item_name\":\"TANG ORING\",\"status\":\"completed\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/history\"}', '2026-04-03 16:27:11', '2026-03-31 07:04:22', '2026-04-03 16:27:11'),
('c7fb9abb-f95a-4cf9-ae68-7f717724082d', 'App\\Notifications\\ItemOverdueNotification', 'App\\Models\\User', 10, '{\"type\":\"overdue\",\"message\":\"Barang \\\"TANG ORING\\\" belum dikembalikan. Sudah terlambat 1 hari dari tanggal 05 Apr 2026.\",\"borrowing_request_id\":40,\"item_name\":\"TANG ORING\",\"due_date\":\"2026-04-05\",\"days_overdue\":1,\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/my-requests\"}', '2026-04-05 15:25:07', '2026-04-05 15:25:03', '2026-04-05 15:25:07'),
('d14c5cc0-5df8-4f25-9181-cea97f37be9d', 'App\\Notifications\\LowStockNotification', 'App\\Models\\User', 12, '{\"type\":\"low_stock\",\"message\":\"Stok item \\\"TANG ORING\\\" hampir habis! Stok saat ini: 1 unit (batas peringatan: 5 unit).\",\"item_id\":53,\"item_name\":\"TANG ORING\",\"stok\":1,\"threshold\":5,\"url\":\"http:\\/\\/localhost\\/admin\\/inventory\\/show\\/53\"}', NULL, '2026-04-03 16:56:17', '2026-04-03 16:56:17'),
('e51962c1-dc5c-46ea-b9b6-c61ada29c786', 'App\\Notifications\\BorrowingApprovedNotification', 'App\\Models\\User', 10, '{\"message\":\"Permintaan peminjaman Anda untuk barang \\\"TANG ORING\\\" telah disetujui.\",\"borrowing_request_id\":40,\"item_name\":\"TANG ORING\",\"status\":\"approved\",\"admin_notes\":\"m\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/show\\/40\"}', '2026-04-04 12:34:06', '2026-04-04 12:21:29', '2026-04-04 12:34:06'),
('e5266dbf-b752-42bf-bf3e-bcb45a65ad71', 'App\\Notifications\\LowStockNotification', 'App\\Models\\User', 11, '{\"type\":\"low_stock\",\"message\":\"Stok item \\\"TANG ORING\\\" hampir habis! Stok saat ini: 1 unit (batas peringatan: 5 unit).\",\"item_id\":53,\"item_name\":\"TANG ORING\",\"stok\":1,\"threshold\":5,\"url\":\"http:\\/\\/localhost\\/admin\\/inventory\\/show\\/53\"}', NULL, '2026-04-03 16:56:17', '2026-04-03 16:56:17'),
('e6498be8-2596-4122-8fde-2000f1b51cc4', 'App\\Notifications\\BorrowingCompletedNotification', 'App\\Models\\User', 10, '{\"message\":\"Peminjaman barang \\\"TANG ORING\\\" telah selesai.\",\"borrowing_request_id\":37,\"item_name\":\"TANG ORING\",\"status\":\"completed\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/history\"}', '2026-04-04 11:04:08', '2026-04-04 06:28:35', '2026-04-04 11:04:08'),
('fb97789d-9816-49cf-9ae1-9ca5454386ba', 'App\\Notifications\\BorrowingExpiredNotification', 'App\\Models\\User', 10, '{\"type\":\"expired\",\"message\":\"Permintaan peminjaman \\\"SOLDER\\\" telah otomatis dibatalkan karena tanggal pinjam (04 Apr 2026) sudah terlewat 2 hari tanpa persetujuan admin.\",\"borrowing_request_id\":43,\"item_name\":\"SOLDER\",\"borrow_date\":\"2026-04-04\",\"days_past\":2,\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/history\"}', '2026-04-05 12:01:33', '2026-04-05 12:01:30', '2026-04-05 12:01:33'),
('fe4b3e25-14e4-48c7-a86a-97433523270e', 'App\\Notifications\\OngoingBorrowingNotification', 'App\\Models\\User', 10, '{\"message\":\"Anda sedang meminjam barang \\\"TANG ORING\\\". Harap kembalikan sebelum tanggal 05 Apr 2026\",\"borrowing_request_id\":40,\"item_name\":\"TANG ORING\",\"due_date\":\"2026-04-05\",\"status\":\"ongoing\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/user\\/borrowing\\/my-requests\"}', '2026-04-04 12:34:06', '2026-04-04 12:22:27', '2026-04-04 12:34:06');

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('qkbLgZINqsBbndmDDhkRHQYmw0FSmeOnBWQQ52WL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMkh4SG45eFdEZGdpZUFXUTBDeG9NQ2dIQk4zSklJMVE3RDI5dHNkVSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1775802201);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `type`, `label`, `description`, `created_at`, `updated_at`) VALUES
(1, 'max_borrow_days', '1', 'integer', 'Maksimal Hari Peminjaman', 'Jumlah hari maksimal untuk setiap peminjaman barang.', '2026-04-03 16:06:57', '2026-04-04 12:13:13'),
(2, 'max_items_per_user', '3', 'integer', 'Maksimal Item per User', 'Jumlah maksimal item yang boleh dipinjam user secara bersamaan (pending + approved).', '2026-04-03 16:06:57', '2026-04-03 16:06:57'),
(3, 'enable_overdue_reminder', '1', 'boolean', 'Aktifkan Pengingat Terlambat', 'Kirim notifikasi ke user ketika barang belum dikembalikan melewati tanggal rencana.', '2026-04-03 16:06:57', '2026-04-03 16:06:57'),
(4, 'enable_pending_reminder', '1', 'boolean', 'Aktifkan Pengingat Pengajuan Pending', 'Kirim notifikasi ke admin ketika ada pengajuan yang belum disetujui lebih dari 1 hari.', '2026-04-03 16:06:57', '2026-04-03 16:06:57'),
(5, 'low_stock_threshold', '1', 'integer', 'Batas Stok Minimum', 'Kirim notifikasi ke admin ketika stok item mencapai atau di bawah jumlah ini.', '2026-04-03 16:54:22', '2026-04-08 16:31:34'),
(6, 'enable_low_stock_alert', '1', 'boolean', 'Aktifkan Peringatan Stok Minimum', 'Kirim notifikasi ke admin secara otomatis ketika stok item hampir habis.', '2026-04-03 16:54:22', '2026-04-03 16:54:22'),
(7, 'company_name', 'PT. INDONESIA CHEMI-CON', 'string', 'Nama Perusahaan', 'Nama perusahaan yang ditampilkan di laporan.', '2026-04-03 19:24:03', '2026-04-10 01:16:04'),
(8, 'company_tagline', 'Inventory', 'string', 'Tagline / Keterangan', 'Tagline atau keterangan singkat perusahaan.', '2026-04-03 19:24:03', '2026-04-04 17:54:18'),
(9, 'company_address', 'Cikarang Selatan', 'string', 'Alamat Perusahaan', 'Alamat lengkap perusahaan.', '2026-04-03 19:24:03', '2026-04-10 01:16:04'),
(10, 'company_phone', '(021) 12345678', 'string', 'Nomor Telepon', 'Nomor telepon perusahaan.', '2026-04-03 19:24:03', '2026-04-10 01:16:04'),
(11, 'company_email', 'inc@gmail.com', 'string', 'Email Perusahaan', 'Alamat email resmi perusahaan.', '2026-04-03 19:24:03', '2026-04-10 01:16:04'),
(12, 'company_logo', 'images/company-logo.png', 'string', 'Logo Perusahaan', 'Path ke file logo perusahaan (diisi otomatis saat upload).', '2026-04-03 19:24:03', '2026-04-04 06:47:03');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `nama`, `company_name`, `contact_person`, `email`, `phone`, `address`, `status`, `created_at`, `updated_at`) VALUES
(16, 'PT. MANDOM INDONESIA', 'PT. MANDOM INDONESIA', 'GERI', 'mandom@gmail.com', '08929283838', 'jakarta', 'active', '2025-10-02 13:19:20', '2025-10-02 13:19:20'),
(17, 'HYUNDAI MOTOR INDONESIA', 'HYUNDAI MOTOR INDONESIA', 'ronald', 'hyundai@gmail.com', '089928299181', 'karawang', 'active', '2025-10-06 14:04:15', '2025-10-06 14:04:15'),
(18, 'PT. PATCO', 'PT. PATCO', 'SERI', 'patco@gmaill.com', '08929989398', 'cikarang selatan', 'active', '2025-10-06 14:05:02', '2025-10-06 14:05:02'),
(19, 'PT. EPSON', 'PT. EPSON', 'risa', 'epson@gmail.com', '0892827635', 'cikarang selatan', 'active', '2025-10-06 14:38:36', '2025-10-06 14:38:36'),
(20, 'PT. DENSO INDONESIA', 'PT. DENSO INDONESIA', 'IMAM', 'denso@gmail.com', '08299374884', 'cikarang barat', 'active', '2025-10-06 14:39:21', '2025-10-06 14:39:21'),
(21, 'HANKOOK', 'HANKOOK', 'DENI', 'hankook@gmail.com', '083993847', 'JAKARTA', 'active', '2025-10-07 15:40:37', '2025-10-14 16:14:41'),
(22, 'GEAR INDO', 'GEAR INDO', 'SUYANI', 'gr@gmail.com', '08928837388', 'karawang', 'active', '2025-10-14 16:12:28', '2025-10-14 16:12:28'),
(23, 'OMRON', 'OMRON', 'CARLO ANCELOTTI', 'omron@gmail.com', '081234567889', 'Cikarang Selatan', 'active', '2025-10-15 17:31:44', '2025-10-15 17:32:56'),
(24, 'PT. ULTRA MILK', 'PT. ULTRA MILK', 'soso', 'susu@gmail.com', '0839362536', 'tebet', 'active', '2025-10-19 10:37:11', '2025-10-19 10:38:13'),
(25, 'PT. INDOFOOD INDONESIA TBK', 'PT. INDOFOOD INDONESIA TBK', 'AHMAD BUDI', 'indf@outlook.com', '0857788839', 'KARAWANG, JAWA BARAT, INDONESIA', 'active', '2025-11-10 15:29:31', '2025-11-10 15:31:17'),
(26, 'CV.MAJU TERUS', 'CV.MAJU TERUS', 'HARSO', 'cvmt@gmail.com', '08947635245', 'JAWA TIMUR', 'active', '2026-04-08 15:33:35', '2026-04-08 15:34:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user','operator') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `role`, `is_active`, `email`, `bio`, `password`, `profil`, `created_at`, `updated_at`) VALUES
(1, 'cipung', 'admin', 1, 'admin@artilia.com', 'System Administrator with full access to all features', '$2y$12$f4Cg5lhSbSBtNE35DY1tTeUv1OGMm3I84ps4Phs1.k4YiOHj2iE36', 'images/profiles/1760862867_68f4a293cc3b2.jpg', '0000-00-00 00:00:00', '2025-11-03 03:56:18'),
(10, 'messi', 'user', 1, 'messi@gmailcom', 'userr', '$2y$12$sgxtmUre2vp4pWRUSV1IxOsvvR5r3Jvw03WSK0k7y0YGt8bvXL8Oy', 'images/profiles/1760026084_68e7dde47c54f.jpg', '2025-10-09 16:08:04', '2026-04-10 06:23:18'),
(15, 'staff', 'operator', 1, 'staff@gmail.com', 'staff', '$2y$12$FzJFlvr0Mz.Iv.HyeZtyY.6CeDg4vtbQSKEAQfXlKbvtjxjlRVhU2', 'images/profiles/1775564003_69d4f4e394877.jpg', '2026-04-07 12:13:23', '2026-04-09 02:05:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_foreign` (`user_id`),
  ADD KEY `audit_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  ADD KEY `audit_logs_action_module_index` (`action`,`module`),
  ADD KEY `audit_logs_created_at_index` (`created_at`);

--
-- Indexes for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `borrowings_item_id_foreign` (`item_id`),
  ADD KEY `borrowings_user_id_foreign` (`user_id`);

--
-- Indexes for table `borrowing_carts`
--
ALTER TABLE `borrowing_carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `borrowing_carts_user_id_item_id_unique` (`user_id`,`item_id`),
  ADD KEY `borrowing_carts_item_id_foreign` (`item_id`);

--
-- Indexes for table `borrowing_requests`
--
ALTER TABLE `borrowing_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `borrowing_requests_user_id_foreign` (`user_id`),
  ADD KEY `borrowing_requests_item_id_foreign` (`item_id`),
  ADD KEY `borrowing_requests_approved_by_foreign` (`approved_by`),
  ADD KEY `borrowing_requests_batch_id_index` (`batch_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_nama_index` (`name`),
  ADD KEY `categories_status_index` (`status`);

--
-- Indexes for table `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventories_item_id_tipe_index` (`item_id`,`tipe`),
  ADD KEY `inventories_status_index` (`status`),
  ADD KEY `inventories_user_id_foreign` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `items_stok_total_index` (`stok_total`),
  ADD KEY `items_supplier_id_category_id_index` (`supplier_id`,`category_id`),
  ADD KEY `items_nama_index` (`nama`),
  ADD KEY `items_category_id_foreign` (`category_id`);

--
-- Indexes for table `logins`
--
ALTER TABLE `logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `logins_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

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
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_name_index` (`name`),
  ADD KEY `users_email_index` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `borrowings`
--
ALTER TABLE `borrowings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `borrowing_carts`
--
ALTER TABLE `borrowing_carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `borrowing_requests`
--
ALTER TABLE `borrowing_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `logins`
--
ALTER TABLE `logins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=474;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD CONSTRAINT `borrowings_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrowings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `borrowing_carts`
--
ALTER TABLE `borrowing_carts`
  ADD CONSTRAINT `borrowing_carts_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrowing_carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `borrowing_requests`
--
ALTER TABLE `borrowing_requests`
  ADD CONSTRAINT `borrowing_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `borrowing_requests_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrowing_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `inventories_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `items_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `logins`
--
ALTER TABLE `logins`
  ADD CONSTRAINT `logins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
