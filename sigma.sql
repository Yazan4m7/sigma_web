-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2021 at 11:05 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sigma`
--
CREATE DATABASE IF NOT EXISTS `sigma` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sigma`;

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

DROP TABLE IF EXISTS `banks`;
CREATE TABLE `banks` (
  `id` bigint(20) NOT NULL,
  `bank_abbrev` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bank_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `bank_abbrev`, `bank_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'ARAB', 'البنك العربي', '2021-03-07 13:28:13', '2021-03-07 13:28:13', NULL),
(2, 'BOJ', 'بنك الاردن', '2021-03-07 13:30:23', '2021-03-07 13:30:23', NULL),
(3, 'JIB', 'البنك الأسلامي', '2021-03-07 13:30:39', '2021-03-07 13:30:39', NULL),
(4, 'JKBA', 'البنك الاردني الكويتي', '2021-03-07 13:31:07', '2021-03-07 13:39:32', NULL),
(5, 'JDIB', 'بنك الصفوة', '2021-03-07 13:34:38', '2021-03-07 13:34:38', NULL),
(6, 'UBSI', 'بنك الاتحاد', '2021-03-07 13:36:56', '2021-03-07 13:36:56', NULL),
(7, 'CAB', 'بنك القاهرة عمان', '2021-03-07 13:37:35', '2021-03-07 13:37:35', NULL),
(8, 'JIBA', 'البنك الإسلامي الأردني', '2021-03-07 13:40:08', '2021-03-07 13:40:08', NULL),
(9, 'RJHI', 'مصرف الراجحي', '2021-03-07 13:40:58', '2021-03-07 13:40:58', NULL),
(10, 'IIAB', 'البنك العربي الإسلامي الدولي', '2021-03-07 13:42:19', '2021-03-07 13:42:19', NULL),
(11, 'BLOM', 'بنك لبنان والمهجر', '2021-03-07 13:43:22', '2021-03-07 13:43:22', NULL),
(12, 'SGBJ', 'بنك سوسيته جنرال', '2021-03-07 13:43:50', '2021-03-07 13:43:50', NULL),
(13, 'EFBK', 'كابيتال بنك', '2021-03-07 13:46:39', '2021-03-07 13:46:39', NULL),
(14, 'HBTF', 'بنك الاسكان', '2021-03-22 13:25:45', '2021-03-22 13:25:45', NULL),
(15, 'ABC', 'بنك ABC', '2021-03-23 15:24:26', '2021-03-23 15:24:26', NULL),
(16, 'AHLI', 'البنك الاهلي', '2021-03-28 13:25:01', '2021-03-28 13:25:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cameras`
--

DROP TABLE IF EXISTS `cameras`;
CREATE TABLE `cameras` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8 NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cameras`
--

INSERT INTO `cameras` (`id`, `name`, `created_at`, `updated_at`) VALUES
(4, 'Cam_1', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

DROP TABLE IF EXISTS `cases`;
CREATE TABLE `cases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `case_id` varchar(191) CHARACTER SET utf8 DEFAULT NULL,
  `patient_name` varchar(191) CHARACTER SET utf8 DEFAULT NULL,
  `initial_delivery_date` datetime DEFAULT NULL,
  `actual_delivery_date` timestamp NULL DEFAULT NULL,
  `current_status` varchar(191) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `total_status` varchar(191) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `voucher_status` int(11) NOT NULL DEFAULT 0,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `finisher` bigint(20) DEFAULT NULL,
  `impression_type` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_returned` tinyint(4) NOT NULL DEFAULT 0,
  `is_a_remake` tinyint(4) NOT NULL DEFAULT 0,
  `is_rejected` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `case_logs`
--

DROP TABLE IF EXISTS `case_logs`;
CREATE TABLE `case_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `case_id` bigint(20) UNSIGNED NOT NULL,
  `stage` int(11) NOT NULL,
  `is_completion` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `case_tags`
--

DROP TABLE IF EXISTS `case_tags`;
CREATE TABLE `case_tags` (
  `id` bigint(20) NOT NULL,
  `case_id` bigint(20) NOT NULL,
  `tag_id` bigint(20) NOT NULL,
  `added_by` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8 NOT NULL,
  `phone` varchar(191) CHARACTER SET utf8 NOT NULL,
  `address` varchar(191) CHARACTER SET utf8 NOT NULL,
  `balance` double DEFAULT 0,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `phone`, `address`, `balance`, `active`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'يزن ابو ليلى', '0788160099', 'المنارة', 490, 1, NULL, '2021-10-27 02:32:28', '2021-11-16 15:40:28');

-- --------------------------------------------------------

--
-- Table structure for table `client_discounts`
--

DROP TABLE IF EXISTS `client_discounts`;
CREATE TABLE `client_discounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `discount` double NOT NULL,
  `type` tinyint(1) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

DROP TABLE IF EXISTS `colors`;
CREATE TABLE `colors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(191) CHARACTER SET utf8 NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
CREATE TABLE `devices` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL,
  `hidden` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `device_logs`
--

DROP TABLE IF EXISTS `device_logs`;
CREATE TABLE `device_logs` (
  `id` bigint(20) NOT NULL,
  `case_id` bigint(20) NOT NULL,
  `sintered_with` bigint(20) DEFAULT NULL,
  `Impressed_with` bigint(20) DEFAULT NULL,
  `zircon_milled_with` bigint(20) DEFAULT NULL,
  `emax_wax_milled_with` bigint(20) DEFAULT NULL,
  `printed_with` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

DROP TABLE IF EXISTS `discounts`;
CREATE TABLE `discounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `case_id` bigint(20) UNSIGNED NOT NULL,
  `reason` bigint(20) UNSIGNED NOT NULL,
  `discount` double NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unit_num` varchar(191) CHARACTER SET utf8 DEFAULT NULL,
  `type` bigint(20) UNSIGNED NOT NULL,
  `color` varchar(191) CHARACTER SET utf8 NOT NULL,
  `style` varchar(191) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `is_modification` tinyint(4) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unit_failed` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `original_job_id` bigint(20) UNSIGNED NOT NULL,
  `note_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rate` int(11) NOT NULL,
  `note` varchar(191) CHARACTER SET utf8 DEFAULT NULL,
  `patient_name` varchar(191) CHARACTER SET utf8 NOT NULL,
  `case_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `id` bigint(20) NOT NULL,
  `case_id` bigint(20) NOT NULL,
  `path` varchar(255) NOT NULL,
  `added_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `impression_types`
--

DROP TABLE IF EXISTS `impression_types`;
CREATE TABLE `impression_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8 NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `impression_types`
--

INSERT INTO `impression_types` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'TRIOS', NULL, NULL, NULL),
(2, 'Impression', NULL, NULL, NULL),
(3, 'STL File', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `internal_transactions`
--

DROP TABLE IF EXISTS `internal_transactions`;
CREATE TABLE `internal_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_user` bigint(20) UNSIGNED DEFAULT NULL,
  `to_user` bigint(20) UNSIGNED DEFAULT NULL,
  `from_doc` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` double NOT NULL,
  `date` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `is_collected` tinyint(1) NOT NULL DEFAULT 0,
  `to_bank` int(11) DEFAULT NULL,
  `payment_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `amount` double NOT NULL,
  `case_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unit_num` varchar(191) CHARACTER SET utf8 DEFAULT NULL,
  `type` bigint(20) UNSIGNED NOT NULL,
  `color` varchar(191) CHARACTER SET utf8 NOT NULL,
  `style` varchar(191) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `case_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) DEFAULT NULL,
  `stage` int(20) DEFAULT NULL,
  `assignee` tinyint(6) DEFAULT NULL,
  `delivery_accepted` bigint(20) DEFAULT NULL,
  `milling_lab` bigint(20) DEFAULT NULL,
  `unit_price` double DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unit_failed` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `is_a_redo` tinyint(4) NOT NULL DEFAULT 0,
  `is_a_remake` tinyint(4) NOT NULL DEFAULT 0,
  `is_modification` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_types`
--

DROP TABLE IF EXISTS `job_types`;
CREATE TABLE `job_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8 NOT NULL,
  `teeth_or_jaw` tinyint(6) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `job_types`
--

INSERT INTO `job_types` (`id`, `name`, `teeth_or_jaw`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Crown', 0, NULL, '2021-10-27 02:34:43', '2021-10-27 02:34:43'),
(2, 'Veneer', 0, NULL, '2021-10-27 02:34:50', '2021-10-27 02:34:50'),
(3, 'Inlay', 0, NULL, '2021-10-27 02:34:59', '2021-10-27 02:34:59'),
(4, '3D Model', 1, NULL, '2021-10-27 02:36:52', '2021-10-27 02:37:23'),
(5, 'Night Guard', 1, NULL, '2021-10-27 02:36:59', '2021-10-27 02:36:59');

-- --------------------------------------------------------

--
-- Table structure for table `labs`
--

DROP TABLE IF EXISTS `labs`;
CREATE TABLE `labs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8 NOT NULL,
  `phone` varchar(191) CHARACTER SET utf8 NOT NULL,
  `address` varchar(191) CHARACTER SET utf8 NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `labs`
--

INSERT INTO `labs` (`id`, `name`, `phone`, `address`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Lab 1', '123', '', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

DROP TABLE IF EXISTS `materials`;
CREATE TABLE `materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8 NOT NULL,
  `price` double NOT NULL,
  `design` tinyint(4) NOT NULL,
  `mill` tinyint(4) NOT NULL,
  `print_3d` tinyint(4) NOT NULL,
  `sinter_furnace` tinyint(4) NOT NULL,
  `press_furnace` tinyint(4) NOT NULL,
  `finish` tinyint(4) NOT NULL,
  `qc` tinyint(4) NOT NULL,
  `delivery` tinyint(4) NOT NULL,
  `restricted` tinyint(4) NOT NULL DEFAULT 0,
  `count_as_unit` tinyint(6) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `name`, `price`, `design`, `mill`, `print_3d`, `sinter_furnace`, `press_furnace`, `finish`, `qc`, `delivery`, `restricted`, `count_as_unit`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Zirconia', 60, 1, 1, 0, 1, 0, 1, 1, 1, 0, 1, NULL, '2021-10-27 02:35:40', '2021-11-06 18:07:50'),
(2, 'E.max', 65, 1, 1, 0, 0, 1, 1, 1, 1, 0, 1, NULL, '2021-10-27 02:36:01', '2021-10-27 02:36:01'),
(3, 'Acrylic', 15, 1, 1, 0, 1, 0, 1, 1, 1, 0, 1, NULL, '2021-10-27 02:36:25', '2021-10-27 13:36:27'),
(4, 'Dental Model', 20, 1, 0, 1, 0, 0, 1, 1, 1, 0, 0, NULL, '2021-10-27 02:37:54', '2021-11-16 06:31:30'),
(5, 'Night Guard', 100, 1, 0, 1, 0, 0, 1, 1, 1, 0, 0, NULL, '2021-10-27 02:38:18', '2021-11-11 15:01:15'),
(6, 'test', 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL, '2021-11-02 18:59:03', '2021-11-02 18:59:03');

-- --------------------------------------------------------

--
-- Table structure for table `material_jobtypes`
--

DROP TABLE IF EXISTS `material_jobtypes`;
CREATE TABLE `material_jobtypes` (
  `id` bigint(20) NOT NULL,
  `material_id` bigint(20) NOT NULL,
  `jobtype_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `material_jobtypes`
--

INSERT INTO `material_jobtypes` (`id`, `material_id`, `jobtype_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 2, 1, '2021-10-27 02:36:01', '2021-10-27 02:36:01', NULL),
(5, 2, 2, '2021-10-27 02:36:01', '2021-10-27 02:36:01', NULL),
(6, 2, 3, '2021-10-27 02:36:01', '2021-10-27 02:36:01', NULL),
(10, 4, 4, '2021-10-27 02:37:54', '2021-11-16 06:31:30', '2021-11-16 06:31:30'),
(12, 3, 1, '2021-10-27 13:36:27', '2021-10-27 13:36:27', NULL),
(13, 3, 2, '2021-10-27 13:36:27', '2021-10-27 13:36:27', NULL),
(14, 3, 3, '2021-10-27 13:36:27', '2021-10-27 13:36:27', NULL),
(15, 6, 5, '2021-11-02 18:59:03', '2021-11-02 18:59:03', NULL),
(31, 1, 1, '2021-11-06 18:07:54', '2021-11-06 18:07:54', NULL),
(32, 1, 2, '2021-11-06 18:07:54', '2021-11-06 18:07:54', NULL),
(33, 5, 5, '2021-11-11 15:01:15', '2021-11-11 15:01:15', NULL),
(34, 4, 4, '2021-11-16 06:31:30', '2021-11-16 06:31:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) CHARACTER SET utf8 NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE `notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `case_id` bigint(20) UNSIGNED NOT NULL,
  `note` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `written_by` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) CHARACTER SET utf8 NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8 DEFAULT NULL,
  `scopes` text CHARACTER SET utf8 DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) CHARACTER SET utf8 NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `scopes` text CHARACTER SET utf8 DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE `oauth_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `name` varchar(191) CHARACTER SET utf8 NOT NULL,
  `secret` varchar(100) CHARACTER SET utf8 NOT NULL,
  `redirect` text CHARACTER SET utf8 NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) CHARACTER SET utf8 NOT NULL,
  `access_token_id` varchar(100) CHARACTER SET utf8 NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(191) CHARACTER SET utf8 NOT NULL,
  `token` varchar(191) CHARACTER SET utf8 NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amount` double NOT NULL,
  `notes` varchar(191) CHARACTER SET utf8 DEFAULT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `collector` bigint(20) UNSIGNED NOT NULL,
  `is_credit_note` tinyint(4) NOT NULL DEFAULT 0,
  `from_bank` bigint(20) DEFAULT NULL,
  `additional_notes` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8 NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Designing', NULL, '2019-11-05 11:42:40', '2019-11-05 11:42:40'),
(2, 'Milling', NULL, '2019-11-05 11:42:40', '2019-11-05 11:42:40'),
(3, '3D Printing', NULL, '2019-11-05 11:42:40', '2019-11-05 11:42:40'),
(4, 'Sintering Furnace', NULL, '2019-11-11 07:26:29', '2019-11-11 07:26:29'),
(5, 'Pressing Furnace', NULL, '2019-11-05 11:42:40', '2019-11-05 11:42:40'),
(6, 'Finish & Build up', NULL, '2019-11-07 14:26:50', '2019-11-07 14:26:50'),
(7, 'Quality Control', NULL, NULL, NULL),
(8, 'Delivery', NULL, NULL, NULL),
(9, 'Accountant', NULL, NULL, NULL),
(100, 'Create a new case', NULL, NULL, NULL),
(101, 'Assign cases to delivery drivers', NULL, NULL, NULL),
(102, 'Edit Cases', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `printing_tasks`
--

DROP TABLE IF EXISTS `printing_tasks`;
CREATE TABLE `printing_tasks` (
  `id` bigint(20) NOT NULL,
  `case_id` bigint(20) NOT NULL,
  `printed_by` bigint(20) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `finished_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receipt_vouchers`
--

DROP TABLE IF EXISTS `receipt_vouchers`;
CREATE TABLE `receipt_vouchers` (
  `id` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `recieved_by` bigint(20) DEFAULT NULL,
  `brought_by` bigint(20) DEFAULT NULL,
  `recieved_at` timestamp NULL DEFAULT NULL,
  `signed` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `redone_orders`
--

DROP TABLE IF EXISTS `redone_orders`;
CREATE TABLE `redone_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `redone_to_stage` int(11) DEFAULT NULL,
  `cause` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `details` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `redone_by` int(11) NOT NULL,
  `desinged_by` bigint(20) DEFAULT NULL,
  `milled_by` bigint(20) DEFAULT NULL,
  `furnaced_by` bigint(20) DEFAULT NULL,
  `finished_by` bigint(20) DEFAULT NULL,
  `units_redone` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rejected_jobs`
--

DROP TABLE IF EXISTS `rejected_jobs`;
CREATE TABLE `rejected_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unit_num` varchar(191) CHARACTER SET utf8 DEFAULT NULL,
  `type` bigint(20) UNSIGNED NOT NULL,
  `color` varchar(191) CHARACTER SET utf8 NOT NULL,
  `style` varchar(191) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rejected_order_records`
--

DROP TABLE IF EXISTS `rejected_order_records`;
CREATE TABLE `rejected_order_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rejected_order_id` int(11) NOT NULL,
  `original_order_id` bigint(20) NOT NULL,
  `cause` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `remade_order_records`
--

DROP TABLE IF EXISTS `remade_order_records`;
CREATE TABLE `remade_order_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `remade_order_id` int(11) NOT NULL,
  `original_order_id` bigint(20) NOT NULL,
  `cause` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remade_to` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `repeat_cases`
--

DROP TABLE IF EXISTS `repeat_cases`;
CREATE TABLE `repeat_cases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `repeat_causes`
--

DROP TABLE IF EXISTS `repeat_causes`;
CREATE TABLE `repeat_causes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reason` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stages_for_view_only`
--

DROP TABLE IF EXISTS `stages_for_view_only`;
CREATE TABLE `stages_for_view_only` (
  `id` bigint(20) NOT NULL,
  `stage_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stages_for_view_only`
--

INSERT INTO `stages_for_view_only` (`id`, `stage_name`) VALUES
(1, 'Design'),
(2, 'Milling'),
(3, '3D Printing'),
(4, 'Sintering Furnace'),
(5, 'Press Furnace'),
(6, 'Finishing'),
(7, 'QC'),
(8, 'Delivery');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` bigint(20) NOT NULL,
  `text` varchar(200) NOT NULL,
  `color` varchar(200) DEFAULT NULL,
  `hidden` tinyint(6) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `text`, `color`, `hidden`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Urgent', 'red', 0, NULL, NULL, NULL),
(2, 'Has Pictures', 'green', 1, NULL, NULL, NULL),
(3, 'Time-consuming', 'red', 0, NULL, NULL, NULL),
(4, 'VIP Client', 'blue', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(191) CHARACTER SET utf8 NOT NULL,
  `last_name` varchar(191) CHARACTER SET utf8 NOT NULL,
  `username` varchar(191) CHARACTER SET utf8 NOT NULL,
  `email` varchar(191) CHARACTER SET utf8 DEFAULT NULL,
  `phone` varchar(191) CHARACTER SET utf8 NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8 NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `included_in_reports` tinyint(6) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `phone`, `email_verified_at`, `password`, `is_admin`, `status`, `included_in_reports`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(52, 'tester', 'admin', 'tester', 'me@sigma.com', '0788160099', NULL, '$2y$10$dr0IeeBuyMJkDIcX3Htt8O6yLcoT2Q8SUWrfaZcjnvnwUxCk4e8Pm', 1, 1, 1, NULL, NULL, '2021-10-26 21:24:46', '2021-11-09 15:52:27'),
(53, 'Yazan', 'Abulaila', 'yazan', 'yazan4m7@gmail.com', '0788160099', NULL, '$2y$10$q0TC/M5lqRIdeEyMaLod6.6kj.sI957c9Ukqv7KegDLiZy4a5jMkm', 1, 1, 1, NULL, NULL, '2021-11-09 15:53:21', '2021-11-11 09:59:07'),
(54, 'Sereen', 'Al-Daken', 'sereen', 'sereen@sigma.com', 'idontknow', NULL, '$2y$10$iX7j8JizBwAL4RG4XBPEC.ltgD0KkZMgvJvcwUfpOfXDsMEF0b2sW', 0, 1, 1, NULL, NULL, '2021-11-09 15:56:05', '2021-11-11 16:04:26'),
(55, 'Saif', 'Al-issa', 'saif', 'saif@sigma.com', '911', NULL, '$2y$10$1PQ.GZlj19/YYmXbCGLD9OX/uo0qjo8D90zmkAkAYF0DqSfNERdUy', 0, 1, 1, NULL, NULL, '2021-11-09 16:56:17', '2021-11-11 14:39:29');

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

DROP TABLE IF EXISTS `user_permissions`;
CREATE TABLE `user_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_permissions`
--

INSERT INTO `user_permissions` (`id`, `user_id`, `permission_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1223, 55, 4, NULL, '2021-11-11 14:39:29', '2021-11-11 14:39:29'),
(1224, 55, 5, NULL, '2021-11-11 14:39:29', '2021-11-11 14:39:29'),
(1225, 55, 6, NULL, '2021-11-11 14:39:29', '2021-11-11 14:39:29'),
(1226, 55, 7, NULL, '2021-11-11 14:39:29', '2021-11-11 14:39:29'),
(1227, 55, 8, NULL, '2021-11-11 14:39:29', '2021-11-11 14:39:29'),
(1228, 55, 9, NULL, '2021-11-11 14:39:29', '2021-11-11 14:39:29'),
(1229, 55, 100, NULL, '2021-11-11 14:39:29', '2021-11-11 14:39:29'),
(1240, 54, 1, NULL, '2021-11-11 16:04:26', '2021-11-11 16:04:26'),
(1241, 54, 2, NULL, '2021-11-11 16:04:26', '2021-11-11 16:04:26'),
(1242, 54, 3, NULL, '2021-11-11 16:04:26', '2021-11-11 16:04:26'),
(1243, 54, 4, NULL, '2021-11-11 16:04:26', '2021-11-11 16:04:26'),
(1244, 54, 5, NULL, '2021-11-11 16:04:26', '2021-11-11 16:04:26'),
(1245, 54, 6, NULL, '2021-11-11 16:04:26', '2021-11-11 16:04:26'),
(1246, 54, 7, NULL, '2021-11-11 16:04:26', '2021-11-11 16:04:26'),
(1247, 54, 8, NULL, '2021-11-11 16:04:26', '2021-11-11 16:04:26'),
(1248, 54, 100, NULL, '2021-11-11 16:04:26', '2021-11-11 16:04:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cameras`
--
ALTER TABLE `cameras`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_doctor_id_foreign` (`doctor_id`),
  ADD KEY `orders_created_by_foreign` (`created_by`);

--
-- Indexes for table `case_logs`
--
ALTER TABLE `case_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_logs_order_id_foreign` (`case_id`),
  ADD KEY `order_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `case_tags`
--
ALTER TABLE `case_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_discounts`
--
ALTER TABLE `client_discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discounts_doctor_id_foreign` (`client_id`),
  ADD KEY `discounts_material_id_foreign` (`material_id`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device_logs`
--
ALTER TABLE `device_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discounts_doctor_id_foreign` (`case_id`),
  ADD KEY `discounts_material_id_foreign` (`reason`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `failed_jobs_material_id_foreign` (`material_id`),
  ADD KEY `failed_jobs_type_foreign` (`type`),
  ADD KEY `failed_jobs_order_id_foreign` (`order_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `impression_types`
--
ALTER TABLE `impression_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `internal_transactions`
--
ALTER TABLE `internal_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_order_id_foreign` (`case_id`),
  ADD KEY `invoices_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_material_id_foreign` (`material_id`),
  ADD KEY `jobs_type_foreign` (`type`),
  ADD KEY `jobs_order_id_foreign` (`case_id`);

--
-- Indexes for table `job_types`
--
ALTER TABLE `job_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `labs`
--
ALTER TABLE `labs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_jobtypes`
--
ALTER TABLE `material_jobtypes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_personal_access_clients_client_id_index` (`client_id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_logs_doctor_id_foreign` (`doctor_id`),
  ADD KEY `payment_logs_collector_foreign` (`collector`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `printing_tasks`
--
ALTER TABLE `printing_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipt_vouchers`
--
ALTER TABLE `receipt_vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `redone_orders`
--
ALTER TABLE `redone_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rejected_jobs`
--
ALTER TABLE `rejected_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_material_id_foreign` (`material_id`),
  ADD KEY `jobs_type_foreign` (`type`);

--
-- Indexes for table `rejected_order_records`
--
ALTER TABLE `rejected_order_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `remade_order_records`
--
ALTER TABLE `remade_order_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `repeat_cases`
--
ALTER TABLE `repeat_cases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `repeat_cases_user_id_foreign` (`user_id`),
  ADD KEY `repeat_cases_order_id_foreign` (`order_id`);

--
-- Indexes for table `repeat_causes`
--
ALTER TABLE `repeat_causes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_permissions_user_id_foreign` (`user_id`),
  ADD KEY `user_permissions_permission_id_foreign` (`permission_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `cameras`
--
ALTER TABLE `cameras`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `case_logs`
--
ALTER TABLE `case_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `case_tags`
--
ALTER TABLE `case_tags`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `client_discounts`
--
ALTER TABLE `client_discounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=810;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `device_logs`
--
ALTER TABLE `device_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1081;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `impression_types`
--
ALTER TABLE `impression_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `internal_transactions`
--
ALTER TABLE `internal_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1074;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3816;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `job_types`
--
ALTER TABLE `job_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `labs`
--
ALTER TABLE `labs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `material_jobtypes`
--
ALTER TABLE `material_jobtypes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `printing_tasks`
--
ALTER TABLE `printing_tasks`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `receipt_vouchers`
--
ALTER TABLE `receipt_vouchers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1425;

--
-- AUTO_INCREMENT for table `redone_orders`
--
ALTER TABLE `redone_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `rejected_jobs`
--
ALTER TABLE `rejected_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=414;

--
-- AUTO_INCREMENT for table `rejected_order_records`
--
ALTER TABLE `rejected_order_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=313;

--
-- AUTO_INCREMENT for table `remade_order_records`
--
ALTER TABLE `remade_order_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=244;

--
-- AUTO_INCREMENT for table `repeat_cases`
--
ALTER TABLE `repeat_cases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `repeat_causes`
--
ALTER TABLE `repeat_causes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1249;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `case_logs`
--
ALTER TABLE `case_logs`
  ADD CONSTRAINT `order_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `client_discounts`
--
ALTER TABLE `client_discounts`
  ADD CONSTRAINT `discounts_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD CONSTRAINT `failed_jobs_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `failed_jobs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `failed_jobs_type_foreign` FOREIGN KEY (`type`) REFERENCES `job_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jobs_order_id_foreign` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jobs_type_foreign` FOREIGN KEY (`type`) REFERENCES `job_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payment_logs_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rejected_jobs`
--
ALTER TABLE `rejected_jobs`
  ADD CONSTRAINT `Rjobs_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Rjobs_type_foreign` FOREIGN KEY (`type`) REFERENCES `job_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `repeat_cases`
--
ALTER TABLE `repeat_cases`
  ADD CONSTRAINT `repeat_cases_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `repeat_cases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD CONSTRAINT `user_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
