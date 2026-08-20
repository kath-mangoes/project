-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2025 at 06:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u720889503_barangay`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_audit`
--

CREATE TABLE `tbl_audit` (
  `audit_id` int(11) NOT NULL,
  `res_id` int(11) DEFAULT NULL,
  `brgyOfficer_id` int(11) DEFAULT NULL,
  `requestType` varchar(50) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `role` varchar(11) NOT NULL,
  `details` varchar(255) NOT NULL,
  `processedBy` varchar(100) NOT NULL,
  `dateTimeCreated` datetime DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL,
  `lastEdited` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_audit`
--

INSERT INTO `tbl_audit` (`audit_id`, `res_id`, `brgyOfficer_id`, `requestType`, `user_id`, `role`, `details`, `processedBy`, `dateTimeCreated`, `status`, `lastEdited`) VALUES
(544, 5, NULL, 'First Time Job Seeker', '1', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-05-03 03:34:52', 'Certificate Status U', '2025-05-03 03:34:52'),
(545, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-05-03 04:48:14', '', '2025-05-03 04:48:14'),
(820, NULL, NULL, '', '5', 'Resident', 'Logged in', '', '2025-08-29 05:35:15', '', '2025-08-29 05:35:15'),
(821, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-08-29 05:38:50', '', '2025-08-29 05:38:50'),
(822, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-08-29 06:15:16', '', '2025-08-29 06:15:16'),
(823, NULL, NULL, '', '2', 'Resident', 'Logged in', '', '2025-08-29 06:19:30', '', '2025-08-29 06:19:30'),
(824, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-08-29 06:25:21', '', '2025-08-29 06:25:21'),
(825, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-08-29 06:27:31', '', '2025-08-29 06:27:31'),
(826, 29, NULL, 'Good Moral', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-29 14:29:06', 'Certificate Status U', '2025-08-29 14:29:06'),
(827, 29, NULL, 'Barangay Clearance', '2', 'admin', 'Clearance Status Updated to: Approved', 'admin', '2025-08-29 14:47:36', 'Clearance Status Upd', '2025-08-29 14:47:36'),
(828, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-08-29 07:00:57', '', '2025-08-29 07:00:57'),
(829, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-08-29 07:06:08', '', '2025-08-29 07:06:08'),
(830, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-08-29 07:16:06', '', '2025-08-29 07:16:06'),
(831, NULL, NULL, '', '0', 'barangay_of', 'Logged in', '', '2025-08-29 07:21:44', '', '2025-08-29 07:21:44'),
(832, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-08-29 08:33:08', '', '2025-08-29 08:33:08'),
(833, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-08-29 10:59:48', '', '2025-08-29 10:59:48'),
(834, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-08-29 11:22:10', '', '2025-08-29 11:22:10'),
(835, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-08-30 02:41:03', '', '2025-08-30 02:41:03'),
(836, NULL, NULL, '', '2', 'Resident', 'Logged in', '', '2025-08-30 12:48:14', '', '2025-08-30 12:48:14'),
(837, NULL, NULL, '', '5', 'Registratio', 'Registration Initiated', '', '2025-08-30 13:13:45', '', '2025-08-30 13:13:45'),
(838, NULL, NULL, '', '5', 'Resident', 'Registered in tbl_user and tbl_resident', '', '2025-08-30 13:14:15', '', '2025-08-30 13:14:15'),
(839, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-08-30 13:16:14', '', '2025-08-30 13:16:14'),
(840, NULL, NULL, '', '5', 'Resident', 'Logged in', '', '2025-08-30 13:18:11', '', '2025-08-30 13:18:11'),
(841, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-30 21:32:40', 'Certificate Status U', '2025-08-30 21:32:40'),
(842, NULL, NULL, '', '4', 'Resident', 'Logged in', '', '2025-08-30 13:40:02', '', '2025-08-30 13:40:02'),
(843, NULL, NULL, '', '4', 'Resident', 'Logged out', '', '2025-08-30 14:01:24', '', '2025-08-30 14:01:24'),
(844, NULL, NULL, '', '2', 'Resident', 'Logged in', '', '2025-08-30 14:01:29', '', '2025-08-30 14:01:29'),
(845, 46, NULL, 'Calamity', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-30 23:12:50', 'Certificate Status U', '2025-08-30 23:12:50'),
(846, 29, NULL, 'First Time Job Seeker', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-30 23:27:43', 'Certificate Status U', '2025-08-30 23:27:43'),
(847, 29, NULL, 'Calamity', '2', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-30 23:28:09', 'Certificate Status U', '2025-08-30 23:28:09'),
(848, 29, NULL, 'Calamity', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-30 23:28:19', 'Certificate Status U', '2025-08-30 23:28:19'),
(849, 29, NULL, 'First Time Job Seeker', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-30 23:28:32', 'Certificate Status U', '2025-08-30 23:28:32'),
(850, 29, NULL, 'First Time Job Seeker', '2', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-30 23:28:52', 'Certificate Status U', '2025-08-30 23:28:52'),
(851, 29, NULL, 'First Time Job Seeker', '2', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-30 23:28:59', 'Certificate Status U', '2025-08-30 23:28:59'),
(852, 29, NULL, 'First Time Job Seeker', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-30 23:29:08', 'Certificate Status U', '2025-08-30 23:29:08'),
(853, 29, NULL, 'Calamity', '2', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-30 23:29:51', 'Certificate Status U', '2025-08-30 23:29:51'),
(854, 29, NULL, 'Calamity', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-30 23:30:04', 'Certificate Status U', '2025-08-30 23:30:04'),
(855, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-08-30 16:07:14', '', '2025-08-30 16:07:14'),
(856, 29, NULL, 'Calamity', '2', 'admin', 'Certificate Status Updated to: Pending', 'admin', '2025-08-31 00:07:57', 'Certificate Status U', '2025-08-31 00:07:57'),
(857, 29, NULL, 'Calamity', '2', 'admin', 'Certificate Status Updated to: Pending', 'admin', '2025-08-31 00:08:05', 'Certificate Status U', '2025-08-31 00:08:05'),
(858, 29, NULL, 'Calamity', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 00:08:20', 'Certificate Status U', '2025-08-31 00:08:20'),
(859, 29, NULL, 'First Time Job Seeker', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 00:11:36', 'Certificate Status U', '2025-08-31 00:11:36'),
(860, 29, NULL, 'First Time Job Seeker', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 00:12:30', 'Certificate Status U', '2025-08-31 00:12:30'),
(861, NULL, NULL, '', '6', 'Registratio', 'Registration Initiated', '', '2025-08-30 16:27:12', '', '2025-08-30 16:27:12'),
(862, NULL, NULL, '', '6', 'Resident', 'Registered in tbl_user and tbl_resident', '', '2025-08-30 16:28:24', '', '2025-08-30 16:28:24'),
(863, NULL, NULL, '', '4', 'Resident', 'Logged in', '', '2025-08-30 16:28:38', '', '2025-08-30 16:28:38'),
(864, NULL, NULL, '', '4', 'Resident', 'Logged out', '', '2025-08-30 16:28:41', '', '2025-08-30 16:28:41'),
(865, NULL, NULL, '', '2', 'Resident', 'Logged in', '', '2025-08-30 16:28:45', '', '2025-08-30 16:28:45'),
(866, NULL, NULL, '', '2', 'Resident', 'Logged out', '', '2025-08-30 16:28:47', '', '2025-08-30 16:28:47'),
(867, NULL, NULL, '', '4', 'Resident', 'Logged in', '', '2025-08-30 16:28:52', '', '2025-08-30 16:28:52'),
(868, NULL, NULL, '', '4', 'Resident', 'Logged out', '', '2025-08-30 16:28:55', '', '2025-08-30 16:28:55'),
(869, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-08-30 16:29:01', '', '2025-08-30 16:29:01'),
(870, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-08-30 16:29:34', '', '2025-08-30 16:29:34'),
(871, NULL, NULL, '', '6', 'Resident', 'Logged in', '', '2025-08-30 16:29:39', '', '2025-08-30 16:29:39'),
(872, NULL, NULL, '', '6', 'Resident', 'Logged out', '', '2025-08-30 16:31:00', '', '2025-08-30 16:31:00'),
(873, 29, NULL, 'First Time Job Seeker', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 00:35:12', 'Certificate Status U', '2025-08-31 00:35:12'),
(874, NULL, NULL, '', '5', 'Resident', 'Logged in', '', '2025-08-30 16:47:36', '', '2025-08-30 16:47:36'),
(875, 29, NULL, 'First Time Job Seeker', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 00:52:14', 'Certificate Status U', '2025-08-31 00:52:14'),
(876, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 01:29:56', 'Certificate Status U', '2025-08-31 01:29:56'),
(877, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 01:30:08', 'Certificate Status U', '2025-08-31 01:30:08'),
(878, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 01:32:21', 'Certificate Status U', '2025-08-31 01:32:21'),
(879, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 01:32:46', 'Certificate Status U', '2025-08-31 01:32:46'),
(880, NULL, NULL, '', '2', 'Resident', 'Logged in', '', '2025-08-31 03:40:24', '', '2025-08-31 03:40:24'),
(881, NULL, NULL, '', '2', 'Resident', 'Logged out', '', '2025-08-31 03:45:33', '', '2025-08-31 03:45:33'),
(882, NULL, NULL, '', '7', 'Registratio', 'Registration Initiated', '', '2025-08-31 03:46:38', '', '2025-08-31 03:46:38'),
(883, NULL, NULL, '', '7', 'Resident', 'Registered in tbl_user and tbl_resident', '', '2025-08-31 03:47:04', '', '2025-08-31 03:47:04'),
(884, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-08-31 03:47:13', '', '2025-08-31 03:47:13'),
(885, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-08-31 03:48:00', '', '2025-08-31 03:48:00'),
(886, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-08-31 11:18:18', '', '2025-08-31 11:18:18'),
(887, 29, NULL, 'Good Moral', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 19:32:18', 'Certificate Status U', '2025-08-31 19:32:18'),
(888, 29, NULL, 'Good Moral', '2', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 19:33:42', 'Certificate Status U', '2025-08-31 19:33:42'),
(889, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 19:33:51', 'Certificate Status U', '2025-08-31 19:33:51'),
(890, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 19:33:59', 'Certificate Status U', '2025-08-31 19:33:59'),
(891, 29, NULL, 'First Time Job Seeker', '2', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 19:34:07', 'Certificate Status U', '2025-08-31 19:34:07'),
(892, 29, NULL, 'Calamity', '2', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 19:34:15', 'Certificate Status U', '2025-08-31 19:34:15'),
(893, 29, NULL, 'First Time Job Seeker', '2', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 19:34:21', 'Certificate Status U', '2025-08-31 19:34:21'),
(894, 29, NULL, 'First Time Job Seeker', '2', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 19:34:27', 'Certificate Status U', '2025-08-31 19:34:27'),
(895, 46, NULL, 'Calamity', '5', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 19:34:33', 'Certificate Status U', '2025-08-31 19:34:33'),
(896, 29, NULL, 'Good Moral', '2', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 19:34:44', 'Certificate Status U', '2025-08-31 19:34:44'),
(897, 29, NULL, 'Good Moral', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 19:35:21', 'Certificate Status U', '2025-08-31 19:35:21'),
(898, 46, NULL, 'Calamity', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 19:36:00', 'Certificate Status U', '2025-08-31 19:36:00'),
(899, 29, NULL, 'First Time Job Seeker', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 19:38:00', 'Certificate Status U', '2025-08-31 19:38:00'),
(900, 29, NULL, 'First Time Job Seeker', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 19:38:22', 'Certificate Status U', '2025-08-31 19:38:22'),
(901, 29, NULL, 'Calamity', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 19:55:01', 'Certificate Status U', '2025-08-31 19:55:01'),
(902, 29, NULL, 'Calamity', '2', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 19:55:29', 'Certificate Status U', '2025-08-31 19:55:29'),
(903, 29, NULL, 'First Time Job Seeker', '2', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 19:55:34', 'Certificate Status U', '2025-08-31 19:55:34'),
(904, 29, NULL, 'First Time Job Seeker', '2', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 19:55:41', 'Certificate Status U', '2025-08-31 19:55:41'),
(905, 46, NULL, 'Calamity', '5', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 19:55:47', 'Certificate Status U', '2025-08-31 19:55:47'),
(906, 29, NULL, 'Good Moral', '2', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 19:55:54', 'Certificate Status U', '2025-08-31 19:55:54'),
(907, 29, NULL, 'Good Moral', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 19:56:02', 'Certificate Status U', '2025-08-31 19:56:02'),
(908, 46, NULL, 'Calamity', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 19:56:17', 'Certificate Status U', '2025-08-31 19:56:17'),
(909, 29, NULL, 'First Time Job Seeker', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 19:56:38', 'Certificate Status U', '2025-08-31 19:56:38'),
(910, 29, NULL, 'First Time Job Seeker', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 19:56:55', 'Certificate Status U', '2025-08-31 19:56:55'),
(911, NULL, NULL, '', '5', 'Resident', 'Logged in', '', '2025-08-31 11:59:53', '', '2025-08-31 11:59:53'),
(912, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 20:02:03', 'Certificate Status U', '2025-08-31 20:02:03'),
(913, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 20:02:48', 'Certificate Status U', '2025-08-31 20:02:48'),
(914, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 20:11:42', 'Certificate Status U', '2025-08-31 20:11:42'),
(915, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 20:11:48', 'Certificate Status U', '2025-08-31 20:11:48'),
(916, 29, NULL, 'Calamity', '2', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 20:14:21', 'Certificate Status U', '2025-08-31 20:14:21'),
(917, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 20:16:53', 'Certificate Status U', '2025-08-31 20:16:53'),
(918, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 20:16:58', 'Certificate Status U', '2025-08-31 20:16:58'),
(919, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 20:17:46', 'Certificate Status U', '2025-08-31 20:17:46'),
(920, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 20:27:49', 'Certificate Status U', '2025-08-31 20:27:49'),
(921, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 20:28:00', 'Certificate Status U', '2025-08-31 20:28:00'),
(922, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 20:32:54', 'Certificate Status U', '2025-08-31 20:32:54'),
(923, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 20:49:16', 'Certificate Status U', '2025-08-31 20:49:16'),
(924, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 20:50:57', 'Certificate Status U', '2025-08-31 20:50:57'),
(925, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 20:55:37', 'Certificate Status U', '2025-08-31 20:55:37'),
(926, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 20:57:23', 'Certificate Status U', '2025-08-31 20:57:23'),
(927, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 20:58:09', 'Certificate Status U', '2025-08-31 20:58:09'),
(928, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 20:59:10', 'Certificate Status U', '2025-08-31 20:59:10'),
(929, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 20:59:38', 'Certificate Status U', '2025-08-31 20:59:38'),
(930, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:00:20', 'Certificate Status U', '2025-08-31 21:00:20'),
(931, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 21:00:45', 'Certificate Status U', '2025-08-31 21:00:45'),
(932, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:00:52', 'Certificate Status U', '2025-08-31 21:00:52'),
(933, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 21:01:07', 'Certificate Status U', '2025-08-31 21:01:07'),
(934, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:01:13', 'Certificate Status U', '2025-08-31 21:01:13'),
(935, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:02:34', 'Certificate Status U', '2025-08-31 21:02:34'),
(936, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:03:10', 'Certificate Status U', '2025-08-31 21:03:10'),
(937, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:04:15', 'Certificate Status U', '2025-08-31 21:04:15'),
(938, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:13:14', 'Certificate Status U', '2025-08-31 21:13:14'),
(939, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:16:11', 'Certificate Status U', '2025-08-31 21:16:11'),
(940, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:16:22', 'Certificate Status U', '2025-08-31 21:16:22'),
(941, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:19:14', 'Certificate Status U', '2025-08-31 21:19:14'),
(942, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:21:32', 'Certificate Status U', '2025-08-31 21:21:32'),
(943, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:26:25', 'Certificate Status U', '2025-08-31 21:26:25'),
(944, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:27:07', 'Certificate Status U', '2025-08-31 21:27:07'),
(945, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:28:19', 'Certificate Status U', '2025-08-31 21:28:19'),
(946, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:29:33', 'Certificate Status U', '2025-08-31 21:29:33'),
(947, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:34:19', 'Certificate Status U', '2025-08-31 21:34:19'),
(948, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:35:44', 'Certificate Status U', '2025-08-31 21:35:44'),
(949, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:35:58', 'Certificate Status U', '2025-08-31 21:35:58'),
(950, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:38:05', 'Certificate Status U', '2025-08-31 21:38:05'),
(951, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:38:13', 'Certificate Status U', '2025-08-31 21:38:13'),
(952, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:42:20', 'Certificate Status U', '2025-08-31 21:42:20'),
(953, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:42:28', 'Certificate Status U', '2025-08-31 21:42:28'),
(954, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:42:48', 'Certificate Status U', '2025-08-31 21:42:48'),
(955, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:44:11', 'Certificate Status U', '2025-08-31 21:44:11'),
(956, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:44:40', 'Certificate Status U', '2025-08-31 21:44:40'),
(957, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:46:56', 'Certificate Status U', '2025-08-31 21:46:56'),
(958, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:47:05', 'Certificate Status U', '2025-08-31 21:47:05'),
(959, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-08-31 13:47:32', '', '2025-08-31 13:47:32'),
(960, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:48:07', 'Certificate Status U', '2025-08-31 21:48:07'),
(961, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-08-31 13:48:30', '', '2025-08-31 13:48:30'),
(962, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:49:02', 'Certificate Status U', '2025-08-31 21:49:02'),
(963, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:49:34', 'Certificate Status U', '2025-08-31 21:49:34'),
(964, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-08-31 13:53:48', '', '2025-08-31 13:53:48'),
(965, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:54:46', 'Certificate Status U', '2025-08-31 21:54:46'),
(966, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 21:54:55', 'Certificate Status U', '2025-08-31 21:54:55'),
(967, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 22:49:13', 'Certificate Status U', '2025-08-31 22:49:13'),
(968, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 22:49:20', 'Certificate Status U', '2025-08-31 22:49:20'),
(969, 46, NULL, 'Calamity', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 22:49:29', 'Certificate Status U', '2025-08-31 22:49:29'),
(970, 46, NULL, 'Calamity', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 22:49:39', 'Certificate Status U', '2025-08-31 22:49:39'),
(971, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:14:17', 'Certificate Status U', '2025-08-31 23:14:17'),
(972, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:16:03', 'Certificate Status U', '2025-08-31 23:16:03'),
(973, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:16:17', 'Certificate Status U', '2025-08-31 23:16:17'),
(974, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:17:00', 'Certificate Status U', '2025-08-31 23:17:00'),
(975, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:17:07', 'Certificate Status U', '2025-08-31 23:17:07'),
(976, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:20:20', 'Certificate Status U', '2025-08-31 23:20:20'),
(977, 46, NULL, 'Calamity', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:22:13', 'Certificate Status U', '2025-08-31 23:22:13'),
(978, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-08-31 23:22:20', 'Certificate Status U', '2025-08-31 23:22:20'),
(979, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:22:26', 'Certificate Status U', '2025-08-31 23:22:26'),
(980, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:37:13', 'Certificate Status U', '2025-08-31 23:37:13'),
(981, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:37:21', 'Certificate Status U', '2025-08-31 23:37:21'),
(982, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:38:52', 'Certificate Status U', '2025-08-31 23:38:52'),
(983, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:39:01', 'Certificate Status U', '2025-08-31 23:39:01'),
(984, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:39:15', 'Certificate Status U', '2025-08-31 23:39:15'),
(985, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:42:31', 'Certificate Status U', '2025-08-31 23:42:31'),
(986, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:42:39', 'Certificate Status U', '2025-08-31 23:42:39'),
(987, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:48:03', 'Certificate Status U', '2025-08-31 23:48:03'),
(988, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:48:10', 'Certificate Status U', '2025-08-31 23:48:10'),
(989, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:55:54', 'Certificate Status U', '2025-08-31 23:55:54'),
(990, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:56:00', 'Certificate Status U', '2025-08-31 23:56:00'),
(991, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:59:13', 'Certificate Status U', '2025-08-31 23:59:13'),
(992, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:59:18', 'Certificate Status U', '2025-08-31 23:59:18'),
(993, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-08-31 23:59:23', 'Certificate Status U', '2025-08-31 23:59:23'),
(994, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:01:26', 'Certificate Status U', '2025-09-01 00:01:26'),
(995, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:02:17', 'Certificate Status U', '2025-09-01 00:02:17'),
(996, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-09-01 00:03:57', 'Certificate Status U', '2025-09-01 00:03:57'),
(997, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:06:36', 'Certificate Status U', '2025-09-01 00:06:36'),
(998, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-09-01 00:06:51', 'Certificate Status U', '2025-09-01 00:06:51'),
(999, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:06:58', 'Certificate Status U', '2025-09-01 00:06:58'),
(1000, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:07:28', 'Certificate Status U', '2025-09-01 00:07:28'),
(1001, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:07:36', 'Certificate Status U', '2025-09-01 00:07:36'),
(1002, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:07:44', 'Certificate Status U', '2025-09-01 00:07:44'),
(1003, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:11:19', 'Certificate Status U', '2025-09-01 00:11:19'),
(1004, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:11:26', 'Certificate Status U', '2025-09-01 00:11:26'),
(1005, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-09-01 00:14:28', 'Certificate Status U', '2025-09-01 00:14:28'),
(1006, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:14:34', 'Certificate Status U', '2025-09-01 00:14:34'),
(1007, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:15:19', 'Certificate Status U', '2025-09-01 00:15:19'),
(1008, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:15:26', 'Certificate Status U', '2025-09-01 00:15:26'),
(1009, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:15:33', 'Certificate Status U', '2025-09-01 00:15:33'),
(1010, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:17:30', 'Certificate Status U', '2025-09-01 00:17:30'),
(1011, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:18:03', 'Certificate Status U', '2025-09-01 00:18:03'),
(1012, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:18:10', 'Certificate Status U', '2025-09-01 00:18:10'),
(1013, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:20:08', 'Certificate Status U', '2025-09-01 00:20:08'),
(1014, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:22:27', 'Certificate Status U', '2025-09-01 00:22:27'),
(1015, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:22:27', 'Certificate Status U', '2025-09-01 00:22:27'),
(1016, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:22:34', 'Certificate Status U', '2025-09-01 00:22:34'),
(1017, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:22:34', 'Certificate Status U', '2025-09-01 00:22:34'),
(1018, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-09-01 00:24:46', 'Certificate Status U', '2025-09-01 00:24:46'),
(1019, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-09-01 00:24:46', 'Certificate Status U', '2025-09-01 00:24:46'),
(1020, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:24:52', 'Certificate Status U', '2025-09-01 00:24:52'),
(1021, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:24:52', 'Certificate Status U', '2025-09-01 00:24:52'),
(1022, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:25:19', 'Certificate Status U', '2025-09-01 00:25:19'),
(1023, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:25:19', 'Certificate Status U', '2025-09-01 00:25:19'),
(1024, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:25:24', 'Certificate Status U', '2025-09-01 00:25:24'),
(1025, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:25:24', 'Certificate Status U', '2025-09-01 00:25:24'),
(1026, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:25:33', 'Certificate Status U', '2025-09-01 00:25:33'),
(1027, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:25:33', 'Certificate Status U', '2025-09-01 00:25:33'),
(1028, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:25:59', 'Certificate Status U', '2025-09-01 00:25:59'),
(1029, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:25:59', 'Certificate Status U', '2025-09-01 00:25:59'),
(1030, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:26:04', 'Certificate Status U', '2025-09-01 00:26:04'),
(1031, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:26:04', 'Certificate Status U', '2025-09-01 00:26:04'),
(1032, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:32:59', 'Certificate Status U', '2025-09-01 00:32:59'),
(1033, 46, NULL, 'Good Moral', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:32:59', 'Certificate Status U', '2025-09-01 00:32:59'),
(1034, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:33:08', 'Certificate Status U', '2025-09-01 00:33:08'),
(1035, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:33:08', 'Certificate Status U', '2025-09-01 00:33:08'),
(1036, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:33:15', 'Certificate Status U', '2025-09-01 00:33:15'),
(1037, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:33:15', 'Certificate Status U', '2025-09-01 00:33:15'),
(1038, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:33:52', 'Certificate Status U', '2025-09-01 00:33:52'),
(1039, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:33:52', 'Certificate Status U', '2025-09-01 00:33:52'),
(1040, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:33:57', 'Certificate Status U', '2025-09-01 00:33:57'),
(1041, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 00:33:57', 'Certificate Status U', '2025-09-01 00:33:57'),
(1042, NULL, NULL, '', '2', 'Resident', 'Logged in', '', '2025-08-31 18:41:24', '', '2025-08-31 18:41:24'),
(1043, NULL, NULL, '', '2', 'Resident', 'Logged in', '', '2025-09-01 05:54:06', '', '2025-09-01 05:54:06'),
(1044, NULL, NULL, '', '2', 'Resident', 'Logged out', '', '2025-09-01 05:54:54', '', '2025-09-01 05:54:54'),
(1045, NULL, NULL, '', '5', 'Resident', 'Logged in', '', '2025-09-01 05:56:47', '', '2025-09-01 05:56:47'),
(1046, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-01 06:57:41', '', '2025-09-01 06:57:41'),
(1047, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-09-01 14:57:54', 'Certificate Status U', '2025-09-01 14:57:54'),
(1048, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Denied', 'admin', '2025-09-01 14:57:54', 'Certificate Status U', '2025-09-01 14:57:54'),
(1049, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 14:57:59', 'Certificate Status U', '2025-09-01 14:57:59'),
(1050, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 14:57:59', 'Certificate Status U', '2025-09-01 14:57:59'),
(1051, NULL, NULL, '', '5', 'Resident', 'Logged in', '', '2025-09-01 06:58:59', '', '2025-09-01 06:58:59'),
(1052, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 14:59:53', 'Certificate Status U', '2025-09-01 14:59:53'),
(1053, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 14:59:53', 'Certificate Status U', '2025-09-01 14:59:53'),
(1054, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 15:00:01', 'Certificate Status U', '2025-09-01 15:00:01'),
(1055, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 15:00:01', 'Certificate Status U', '2025-09-01 15:00:01'),
(1056, 46, NULL, 'First Time Job Seeker', '5', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-01 15:25:07', 'Certificate Status U', '2025-09-01 15:25:07'),
(1057, NULL, NULL, '', '2', 'Resident', 'Logged in', '', '2025-09-01 09:46:58', '', '2025-09-01 09:46:58'),
(1058, NULL, NULL, '', '2', 'Resident', 'Logged out', '', '2025-09-01 10:46:17', '', '2025-09-01 10:46:17'),
(1059, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-01 10:46:41', '', '2025-09-01 10:46:41'),
(1060, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-01 11:15:05', '', '2025-09-01 11:15:05'),
(1061, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-01 11:39:35', '', '2025-09-01 11:39:35'),
(1062, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-02 03:51:42', '', '2025-09-02 03:51:42'),
(1063, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-02 04:01:18', '', '2025-09-02 04:01:18'),
(1064, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-09-02 04:11:00', '', '2025-09-02 04:11:00'),
(1065, NULL, NULL, '', '5', 'Resident', 'Logged in', '', '2025-09-02 04:13:10', '', '2025-09-02 04:13:10'),
(1066, NULL, NULL, '', '5', 'Resident', 'Logged out', '', '2025-09-02 04:15:26', '', '2025-09-02 04:15:26'),
(1067, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-02 04:16:09', '', '2025-09-02 04:16:09'),
(1068, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-02 04:42:27', '', '2025-09-02 04:42:27'),
(1069, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-02 04:55:52', '', '2025-09-02 04:55:52'),
(1070, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-09-02 13:01:35', '', '2025-09-02 13:01:35'),
(1071, NULL, NULL, '', '2', 'Resident', 'Logged in', '', '2025-09-02 13:02:00', '', '2025-09-02 13:02:00'),
(1072, NULL, NULL, '', '2', 'Resident', 'Logged in', '', '2025-09-02 14:26:06', '', '2025-09-02 14:26:06'),
(1073, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-05 20:02:40', '', '2025-09-05 20:02:40'),
(1074, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-05 21:58:16', '', '2025-09-05 21:58:16'),
(1075, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-09-05 22:00:17', '', '2025-09-05 22:00:17'),
(1076, NULL, NULL, '', '8', 'Registratio', 'Registration Initiated', '', '2025-09-05 22:02:19', '', '2025-09-05 22:02:19'),
(1077, NULL, NULL, '', '8', 'Resident', 'Registered in tbl_user and tbl_resident', '', '2025-09-05 22:02:48', '', '2025-09-05 22:02:48'),
(1078, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-05 22:03:41', '', '2025-09-05 22:03:41'),
(1079, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-09-05 22:04:20', '', '2025-09-05 22:04:20'),
(1080, NULL, NULL, '', '8', 'Resident', 'Logged in', '', '2025-09-05 22:04:24', '', '2025-09-05 22:04:24'),
(1081, NULL, NULL, '', '8', 'Resident', 'Logged out', '', '2025-09-05 22:05:40', '', '2025-09-05 22:05:40'),
(1082, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-05 22:05:47', '', '2025-09-05 22:05:47'),
(1083, 49, NULL, 'Good Moral', '8', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-05 22:06:09', 'Certificate Status U', '2025-09-05 22:06:09'),
(1084, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-09-05 22:07:59', '', '2025-09-05 22:07:59'),
(1085, NULL, NULL, '', '8', 'Resident', 'Logged in', '', '2025-09-05 22:08:04', '', '2025-09-05 22:08:04'),
(1086, NULL, NULL, '', '8', 'Resident', 'Logged out', '', '2025-09-05 22:08:57', '', '2025-09-05 22:08:57'),
(1087, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-05 22:09:05', '', '2025-09-05 22:09:05'),
(1088, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-09-05 22:11:02', '', '2025-09-05 22:11:02'),
(1089, NULL, NULL, '', '8', 'Resident', 'Logged in', '', '2025-09-05 22:11:05', '', '2025-09-05 22:11:05'),
(1090, NULL, NULL, '', '8', 'Resident', 'Logged out', '', '2025-09-05 22:12:03', '', '2025-09-05 22:12:03'),
(1091, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-05 22:12:12', '', '2025-09-05 22:12:12'),
(1092, NULL, NULL, '', '8', 'Resident', 'Logged in', '', '2025-09-05 22:32:08', '', '2025-09-05 22:32:08'),
(1093, 49, NULL, 'Calamity', '8', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-05 23:02:04', 'Certificate Status U', '2025-09-05 23:02:04'),
(1094, 49, NULL, 'Good Moral', '8', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-05 23:03:36', 'Certificate Status U', '2025-09-05 23:03:36'),
(1095, 49, NULL, 'First Time Job Seeker', '8', 'admin', 'Certificate Status Updated to: Pending', 'admin', '2025-09-05 23:49:17', 'Certificate Status U', '2025-09-05 23:49:17'),
(1096, 49, NULL, 'First Time Job Seeker', '8', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-05 23:59:32', 'Certificate Status U', '2025-09-05 23:59:32'),
(1097, NULL, NULL, '', '8', 'Resident', 'Logged out', '', '2025-09-06 02:47:30', '', '2025-09-06 02:47:30'),
(1098, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-06 14:07:38', '', '2025-09-06 14:07:38'),
(1099, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-09-06 15:47:00', '', '2025-09-06 15:47:00'),
(1100, NULL, NULL, '', '8', 'Resident', 'Logged in', '', '2025-09-06 15:47:03', '', '2025-09-06 15:47:03'),
(1101, NULL, NULL, '', '8', 'Resident', 'Logged out', '', '2025-09-06 16:01:50', '', '2025-09-06 16:01:50'),
(1102, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-06 16:01:55', '', '2025-09-06 16:01:55'),
(1103, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-09-06 16:14:48', '', '2025-09-06 16:14:48'),
(1104, NULL, NULL, '', '8', 'Resident', 'Logged in', '', '2025-09-06 16:14:51', '', '2025-09-06 16:14:51'),
(1105, NULL, NULL, '', '8', 'Resident', 'Logged out', '', '2025-09-06 16:16:58', '', '2025-09-06 16:16:58'),
(1106, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-06 16:17:23', '', '2025-09-06 16:17:23'),
(1107, NULL, NULL, '', '8', 'Resident', 'Logged in', '', '2025-09-06 16:20:38', '', '2025-09-06 16:20:38'),
(1108, 49, NULL, 'ID Issuance', '8', 'admin', 'Barangay ID Status Updated to: Approved', 'admin', '2025-09-06 16:21:46', 'ID Status Updated', '2025-09-06 16:21:46'),
(1109, 49, NULL, 'ID Issuance', '8', 'admin', 'Barangay ID Status Updated to: Approved', 'admin', '2025-09-06 16:36:31', 'ID Status Updated', '2025-09-06 16:36:31'),
(1110, 49, NULL, 'ID Issuance', '8', 'admin', 'Barangay ID Status Updated to: Approved', 'admin', '2025-09-06 16:37:55', 'ID Status Updated', '2025-09-06 16:37:55'),
(1111, 49, NULL, 'ID Issuance', '8', 'admin', 'Barangay ID Status Updated to: Approved', 'admin', '2025-09-06 16:48:24', 'ID Status Updated', '2025-09-06 16:48:24'),
(1112, 49, NULL, 'ID Issuance', '8', 'admin', 'Barangay ID Status Updated to: Approved', 'admin', '2025-09-06 16:48:24', 'ID Status Updated', '2025-09-06 16:48:24'),
(1113, 49, NULL, 'ID Issuance', '8', 'admin', 'Barangay ID Status Updated to: Approved', 'admin', '2025-09-06 17:07:11', 'ID Status Updated', '2025-09-06 17:07:11'),
(1114, 49, NULL, 'ID Issuance', '8', 'admin', 'Barangay ID Status Updated to: Approved', 'admin', '2025-09-06 17:07:19', 'ID Status Updated', '2025-09-06 17:07:19'),
(1115, 49, NULL, 'ID Issuance', '8', 'admin', 'Barangay ID Status Updated to: Approved', 'admin', '2025-09-06 17:12:48', 'ID Status Updated', '2025-09-06 17:12:48'),
(1116, 49, NULL, 'ID Issuance', '8', 'admin', 'Barangay ID Status Updated to: Approved', 'admin', '2025-09-06 17:13:32', 'ID Status Updated', '2025-09-06 17:13:32'),
(1117, 49, NULL, 'ID Issuance', '8', 'admin', 'Barangay ID Status Updated to: Approved', 'admin', '2025-09-06 17:22:49', 'ID Status Updated', '2025-09-06 17:22:49'),
(1118, 49, NULL, 'ID Issuance', '8', 'admin', 'Barangay ID Status Updated to: Approved', 'admin', '2025-09-06 17:25:04', 'ID Status Updated', '2025-09-06 17:25:04'),
(1120, 49, NULL, 'ID Issuance', '8', 'admin', 'Barangay ID Status Updated to: Approved', 'admin', '2025-09-06 17:38:39', 'ID Status Updated', '2025-09-06 17:38:39'),
(1121, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-10 23:01:30', '', '2025-09-10 23:01:30'),
(1122, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-10 23:10:05', '', '2025-09-10 23:10:05'),
(1123, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-09-10 23:33:03', '', '2025-09-10 23:33:03'),
(1124, NULL, NULL, '', '9', 'Registratio', 'Registration Initiated', '', '2025-09-10 23:39:46', '', '2025-09-10 23:39:46'),
(1125, NULL, NULL, '', '9', 'Resident', 'Registered in tbl_user and tbl_resident', '', '2025-09-10 23:39:59', '', '2025-09-10 23:39:59'),
(1126, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-10 23:40:30', '', '2025-09-10 23:40:30'),
(1127, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-09-10 23:40:49', '', '2025-09-10 23:40:49'),
(1128, NULL, NULL, '', '9', 'Resident', 'Logged in', '', '2025-09-10 23:41:11', '', '2025-09-10 23:41:11'),
(1129, NULL, NULL, '', '9', 'Resident', 'Logged out', '', '2025-09-10 23:49:32', '', '2025-09-10 23:49:32'),
(1130, NULL, NULL, '', '9', 'Resident', 'Logged in', '', '2025-09-10 23:54:59', '', '2025-09-10 23:54:59'),
(1131, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-14 08:55:08', '', '2025-09-14 08:55:08'),
(1132, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-15 11:33:59', '', '2025-09-15 11:33:59'),
(1133, NULL, NULL, '', '8', 'Resident', 'Logged in', '', '2025-09-15 12:07:20', '', '2025-09-15 12:07:20'),
(1134, 49, NULL, 'Good Moral', '8', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-15 12:51:37', 'Certificate Status U', '2025-09-15 12:51:37'),
(1135, 49, NULL, 'Barangay Clearance', '8', 'admin', 'Clearance Status Updated to: Approved', 'admin', '2025-09-15 14:43:19', 'Clearance Status Upd', '2025-09-15 14:43:19'),
(1136, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-15 18:24:49', '', '2025-09-15 18:24:49'),
(1137, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-16 22:07:14', '', '2025-09-16 22:07:14'),
(1138, 50, NULL, 'Good Moral', '9', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-16 22:07:35', 'Certificate Status U', '2025-09-16 22:07:35'),
(1139, NULL, NULL, '', '8', 'Resident', 'Logged in', '', '2025-09-16 22:07:58', '', '2025-09-16 22:07:58'),
(1140, NULL, NULL, '', '8', 'Resident', 'Logged out', '', '2025-09-16 23:02:05', '', '2025-09-16 23:02:05'),
(1141, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-16 23:03:09', '', '2025-09-16 23:03:09'),
(1142, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-09-16 23:03:14', '', '2025-09-16 23:03:14'),
(1143, NULL, NULL, '', '8', 'Resident', 'Logged in', '', '2025-09-16 23:06:36', '', '2025-09-16 23:06:36'),
(1144, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-19 15:13:19', '', '2025-09-19 15:13:19'),
(1145, NULL, NULL, '', '8', 'Resident', 'Logged in', '', '2025-09-19 15:13:36', '', '2025-09-19 15:13:36'),
(1146, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-09-19 15:26:56', '', '2025-09-19 15:26:56'),
(1147, NULL, NULL, '', '8', 'Resident', 'Logged in', '', '2025-09-19 15:27:00', '', '2025-09-19 15:27:00'),
(1148, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-21 20:08:25', '', '2025-09-21 20:08:25'),
(1149, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-09-21 20:08:50', '', '2025-09-21 20:08:50'),
(1150, NULL, NULL, '', '8', 'Resident', 'Logged in', '', '2025-09-21 20:08:54', '', '2025-09-21 20:08:54'),
(1151, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-21 20:19:33', '', '2025-09-21 20:19:33'),
(1152, 49, NULL, 'Calamity', '8', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-21 20:19:48', 'Certificate Status U', '2025-09-21 20:19:48'),
(1153, 49, NULL, 'Calamity', '8', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-21 20:29:49', 'Certificate Status U', '2025-09-21 20:29:49'),
(1154, 49, NULL, 'Calamity', '8', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-21 20:38:49', 'Certificate Status U', '2025-09-21 20:38:49'),
(1155, NULL, NULL, '', '8', 'Resident', 'Logged in', '', '2025-09-21 21:02:02', '', '2025-09-21 21:02:02'),
(1156, NULL, NULL, '', '8', 'Resident', 'Logged out', '', '2025-09-21 21:02:11', '', '2025-09-21 21:02:11'),
(1157, NULL, NULL, '', '8', 'Resident', 'Logged in', '', '2025-09-21 21:02:14', '', '2025-09-21 21:02:14'),
(1158, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-21 21:11:24', '', '2025-09-21 21:11:24'),
(1159, 49, NULL, 'Calamity', '8', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-09-21 21:11:38', 'Certificate Status U', '2025-09-21 21:11:38'),
(1160, NULL, NULL, '', '8', 'Resident', 'Logged out', '', '2025-09-21 21:13:21', '', '2025-09-21 21:13:21'),
(1161, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-21 21:18:27', '', '2025-09-21 21:18:27'),
(1162, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-09-21 21:28:58', '', '2025-09-21 21:28:58'),
(1163, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-21 21:31:46', '', '2025-09-21 21:31:46');
INSERT INTO `tbl_audit` (`audit_id`, `res_id`, `brgyOfficer_id`, `requestType`, `user_id`, `role`, `details`, `processedBy`, `dateTimeCreated`, `status`, `lastEdited`) VALUES
(1164, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-09-21 21:36:51', '', '2025-09-21 21:36:51'),
(1165, NULL, NULL, '', '1', 'Registratio', 'Registration Initiated', '', '2025-09-21 22:33:20', '', '2025-09-21 22:33:20'),
(1166, NULL, NULL, '', '1', 'Resident', 'Registered in tbl_user and tbl_resident', '', '2025-09-21 22:34:32', '', '2025-09-21 22:34:32'),
(1167, NULL, NULL, '', '2', 'Registratio', 'Registration Initiated', '', '2025-09-21 22:40:57', '', '2025-09-21 22:40:57'),
(1168, NULL, NULL, '', '2', 'Resident', 'Registered in tbl_user and tbl_resident', '', '2025-09-21 22:41:44', '', '2025-09-21 22:41:44'),
(1169, NULL, NULL, '', '2', 'Registratio', 'Registration Initiated', '', '2025-09-21 22:51:11', '', '2025-09-21 22:51:11'),
(1170, NULL, NULL, '', '2', 'Resident', 'Registered in tbl_user and tbl_resident', '', '2025-09-21 22:51:43', '', '2025-09-21 22:51:43'),
(1171, NULL, NULL, '', '2', 'Registratio', 'Registration Initiated', '', '2025-09-21 22:55:26', '', '2025-09-21 22:55:26'),
(1172, NULL, NULL, '', '2', 'Resident', 'Registered in tbl_user and tbl_resident', '', '2025-09-21 22:57:33', '', '2025-09-21 22:57:33'),
(1173, NULL, NULL, '', '2', 'Registratio', 'Registration Initiated', '', '2025-09-21 23:01:02', '', '2025-09-21 23:01:02'),
(1174, NULL, NULL, '', '2', 'Resident', 'Registered in tbl_user and tbl_resident', '', '2025-09-21 23:01:25', '', '2025-09-21 23:01:25'),
(1175, NULL, NULL, '', '2', 'Registratio', 'Registration Initiated', '', '2025-09-21 23:03:05', '', '2025-09-21 23:03:05'),
(1176, NULL, NULL, '', '2', 'Resident', 'Registered in tbl_user and tbl_resident', '', '2025-09-21 23:03:21', '', '2025-09-21 23:03:21'),
(1177, NULL, NULL, '', '2', 'Registratio', 'Registration Initiated', '', '2025-09-21 23:08:06', '', '2025-09-21 23:08:06'),
(1178, NULL, NULL, '', '2', 'Registratio', 'Registration Initiated', '', '2025-09-21 23:09:57', '', '2025-09-21 23:09:57'),
(1179, NULL, NULL, '', '2', 'Registratio', 'Registration Initiated', '', '2025-09-21 23:10:35', '', '2025-09-21 23:10:35'),
(1180, NULL, NULL, '', '2', 'Resident', 'Registered in tbl_user and tbl_resident', '', '2025-09-21 23:11:20', '', '2025-09-21 23:11:20'),
(1181, NULL, NULL, '', '2', 'Registratio', 'Registration Initiated', '', '2025-09-21 23:12:38', '', '2025-09-21 23:12:38'),
(1182, NULL, NULL, '', '2', 'Resident', 'Registered in tbl_user and tbl_resident', '', '2025-09-21 23:12:52', '', '2025-09-21 23:12:52'),
(1183, NULL, NULL, '', '2', 'Registratio', 'Registration Initiated', '', '2025-09-21 23:14:26', '', '2025-09-21 23:14:26'),
(1184, NULL, NULL, '', '2', 'Resident', 'Registered in tbl_user and tbl_resident', '', '2025-09-21 23:14:46', '', '2025-09-21 23:14:46'),
(1185, NULL, NULL, '', '2', 'Registratio', 'Registration Initiated', '', '2025-09-21 23:19:31', '', '2025-09-21 23:19:31'),
(1186, NULL, NULL, '', '2', 'Resident', 'Registered in tbl_user and tbl_resident', '', '2025-09-21 23:19:44', '', '2025-09-21 23:19:44'),
(1187, NULL, NULL, '', '3', 'Registratio', 'Registration Initiated', '', '2025-09-21 23:22:13', '', '2025-09-21 23:22:13'),
(1188, NULL, NULL, '', '3', 'Registratio', 'Registration Initiated', '', '2025-09-21 23:23:28', '', '2025-09-21 23:23:28'),
(1189, NULL, NULL, '', '3', 'Registratio', 'Registration Initiated', '', '2025-09-21 23:23:56', '', '2025-09-21 23:23:56'),
(1190, NULL, NULL, '', '3', 'Resident', 'Registered in tbl_user and tbl_resident', '', '2025-09-21 23:24:11', '', '2025-09-21 23:24:11'),
(1191, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-09-21 23:25:20', '', '2025-09-21 23:25:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_banned_users`
--

CREATE TABLE `tbl_banned_users` (
  `ban_id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `banned_by` varchar(50) NOT NULL,
  `reason` text DEFAULT NULL,
  `ban_date` datetime DEFAULT current_timestamp(),
  `lift_date` datetime DEFAULT NULL,
  `status` enum('Active','Lifted') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bid`
--

CREATE TABLE `tbl_bid` (
  `BID_id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `remarks` text NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `suffix` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `civilStatus` varchar(50) NOT NULL,
  `ID_No` varchar(20) NOT NULL,
  `precinctNumber` varchar(20) DEFAULT NULL,
  `bloodType` varchar(5) DEFAULT NULL,
  `birthday` date NOT NULL,
  `birthplace` varchar(100) NOT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `SSSGSIS_Number` varchar(20) DEFAULT NULL,
  `TIN_number` varchar(15) DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `dateApplied` datetime NOT NULL DEFAULT current_timestamp(),
  `dateIssued` date DEFAULT NULL,
  `dateExpiration` date DEFAULT NULL,
  `personTwoName` varchar(100) DEFAULT NULL,
  `personTwoAddress` varchar(255) DEFAULT NULL,
  `personTwoContactInfo` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_bid`
--

INSERT INTO `tbl_bid` (`BID_id`, `res_id`, `user_id`, `remarks`, `last_name`, `first_name`, `middle_name`, `suffix`, `address`, `civilStatus`, `ID_No`, `precinctNumber`, `bloodType`, `birthday`, `birthplace`, `height`, `weight`, `status`, `SSSGSIS_Number`, `TIN_number`, `document_path`, `dateApplied`, `dateIssued`, `dateExpiration`, `personTwoName`, `personTwoAddress`, `personTwoContactInfo`, `created_at`) VALUES
(9, 29, '2', '', 'Pompom', 'kath', '', '', 'Makati', 'Single', '', '', 'Unkno', '2004-02-04', '', 0.00, 0.00, 'Approved', '', '', '1754742296_68973e18e7948_08.40 - 09.40.png', '2025-08-09 12:24:56', NULL, NULL, '453323131231224234324324234', '3123123123124dfdgdfgdfgd', '13123232323', '2025-08-09 13:31:11'),
(10, 46, '5', '', 'Lee', 'Felix', '', '', '12 Yba', 'Single', '', '', 'Unkno', '2000-07-03', '', 0.00, 0.00, 'To Be Approved', '', '', '1756560275_68b2fb93cdf47_Screenshot (2).png', '2025-08-30 13:24:35', NULL, NULL, 'Honey', '12 Barro', '09288888888', '2025-09-06 08:43:28'),
(15, 49, '8', '', 'Padilla', 'James Reid', '', '', 'mexico pampanga', 'Single', '', '', 'Unkno', '2000-02-19', '', 0.00, 0.00, 'To Be Approved', '', '', '1757152755_68bc05f3304fa_padilla-resume.pdf', '2025-09-06 17:59:15', NULL, NULL, 'Joshua Anderson Padilla', 'None', '09454454741', '2025-09-06 09:59:15');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_blotter`
--

CREATE TABLE `tbl_blotter` (
  `blotter_id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `dateFiled` date NOT NULL,
  `caseNumber` varchar(20) NOT NULL,
  `complainant` varchar(100) NOT NULL,
  `respondent` varchar(100) NOT NULL,
  `victim` varchar(100) DEFAULT NULL,
  `witness` varchar(100) DEFAULT NULL,
  `natureOfCase` varchar(100) NOT NULL,
  `blotter_desc` text NOT NULL,
  `brgyOfficer_id` int(11) NOT NULL,
  `document_path` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_brgyofficer`
--

CREATE TABLE `tbl_brgyofficer` (
  `brgyOfficer_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `address` varchar(100) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `birthday` date NOT NULL,
  `startTerm` date NOT NULL,
  `endTerm` date DEFAULT NULL,
  `is_senior` enum('Yes','No') DEFAULT 'No',
  `is_pwd` enum('Yes','No') DEFAULT 'No',
  `status` enum('Pending','Active','Inactive') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_brgyofficer`
--

INSERT INTO `tbl_brgyofficer` (`brgyOfficer_id`, `email`, `user_id`, `first_name`, `middle_name`, `last_name`, `address`, `mobile`, `position`, `birthday`, `startTerm`, `endTerm`, `is_senior`, `is_pwd`, `status`, `created_at`) VALUES
(28, 'calitarte421@gmail.com', 'officer_68b154ea14f76', 'Chris', 'A', 'Karenina', 'Makati', '09464838399', 'BarangayCaptain', '2014-03-01', '0000-00-00', NULL, 'No', 'No', 'Active', '2025-08-29 07:21:26'),
(29, 'sample@gmail.com', 'officer_68b165b5b0925', 'Winter', 'resgdhgf', 'Esteban', 'Makati', '09810265312', 'BarangayTreasurer', '2025-08-06', '0000-00-00', NULL, 'No', 'No', 'Active', '2025-08-29 08:33:14');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_certification`
--

CREATE TABLE `tbl_certification` (
  `certification_id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `certificationType` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `purpose` text NOT NULL,
  `remarks` text DEFAULT NULL,
  `registeredVoter` tinyint(1) DEFAULT 0,
  `resident_status` tinyint(1) DEFAULT 0,
  `dateToday` date NOT NULL DEFAULT current_timestamp(),
  `dateApplied` datetime NOT NULL DEFAULT current_timestamp(),
  `dateReceived` date DEFAULT NULL,
  `residentSignature` varchar(255) DEFAULT NULL,
  `document_path` longtext NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `type_of_calamity` varchar(100) DEFAULT NULL,
  `calamity_date` date DEFAULT NULL,
  `calamity_time` varchar(255) DEFAULT NULL,
  `what_is_caused` text DEFAULT NULL,
  `location` text DEFAULT NULL,
  `calamity_purpose` text DEFAULT NULL,
  `requested_by` varchar(100) DEFAULT NULL,
  `calamity_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_certification`
--

INSERT INTO `tbl_certification` (`certification_id`, `res_id`, `user_id`, `certificationType`, `name`, `suffix`, `address`, `purpose`, `remarks`, `registeredVoter`, `resident_status`, `dateToday`, `dateApplied`, `dateReceived`, `residentSignature`, `document_path`, `status`, `created_at`, `type_of_calamity`, `calamity_date`, `calamity_time`, `what_is_caused`, `location`, `calamity_purpose`, `requested_by`, `calamity_notes`) VALUES
(110, 49, '8', 'Calamity', 'kathryn Padilla', NULL, 'mexico pampanga', 'Calamity', '', 0, 0, '2025-09-21', '2025-09-21 21:11:11', NULL, NULL, '1758460271_68cff96fc575b_494579798_693373853415594_8083316453582063649_n.jpg', 'Approved', '2025-09-21 13:11:38', 'Fire', '2025-09-21', '21:10', NULL, NULL, 'Fire Victim Purposes', 'andy anderson', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_clearance`
--

CREATE TABLE `tbl_clearance` (
  `clearance_id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `clearanceType` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `purpose` text NOT NULL,
  `remarks` text DEFAULT NULL,
  `registeredVoter` tinyint(1) DEFAULT 0,
  `birthday` date NOT NULL,
  `dateToday` date NOT NULL DEFAULT current_timestamp(),
  `dateApplied` datetime NOT NULL DEFAULT current_timestamp(),
  `dateReceived` date DEFAULT NULL,
  `residentSignature` varchar(255) DEFAULT NULL,
  `document_path` longtext NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_clearance`
--

INSERT INTO `tbl_clearance` (`clearance_id`, `res_id`, `user_id`, `clearanceType`, `name`, `address`, `purpose`, `remarks`, `registeredVoter`, `birthday`, `dateToday`, `dateApplied`, `dateReceived`, `residentSignature`, `document_path`, `status`, `created_at`) VALUES
(27, 29, '2', 'Barangay Clearance', 'kath Pompom', 'Makati', 'Hospital Requirement', 'wrtfygfd4564', 1, '0000-00-00', '2025-08-09', '2025-08-09 12:22:36', NULL, NULL, '1754742156_68973d8c963a6_AJAD_2005_2_1_2_6Briones.pdf', 'Approved', '2025-08-09 13:25:40'),
(28, 29, '2', 'Garbage Disposal Clearance', 'kath Pompom', 'Makati', 'Bank Transaction', 'hahahahaha', 1, '0000-00-00', '2025-08-09', '2025-08-09 12:23:20', NULL, NULL, '1754742200_68973db809c61_PANIS_PRELIM-ACT1_CSBE.docx,1754742200_68973db80be88_colored.png', 'Denied', '2025-08-09 13:25:04'),
(29, 29, '2', 'Declogging Clearance', 'kath Pompom', 'Makati', 'Transfer Residency', 'errererer', 1, '0000-00-00', '2025-08-09', '2025-08-09 12:24:02', NULL, NULL, '1754742242_68973de2bfc12_V1_CIT 4302_RUBRIC FOR GROUP PRESENTATION_1-1.xlsx', 'Approved', '2025-08-09 13:21:34'),
(30, 46, '5', 'Declogging Clearance', 'Felix Lee', '12 Yba', 'PWD ID', NULL, 0, '0000-00-00', '2025-08-30', '2025-08-30 13:21:14', NULL, NULL, '1756560074_68b2faca32417_Screenshot (2).png', 'To Be Approved', '2025-08-30 13:21:14'),
(31, 49, '8', 'Barangay Clearance', 'carlos Padilla', 'mexico pampanga', 'Maynilad Requirement', '', 0, '0000-00-00', '2025-09-15', '2025-09-15 14:39:07', NULL, NULL, '1757918347_68c7b48be687b_OIP.jpg', 'Approved', '2025-09-15 06:43:19');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_compgriev`
--

CREATE TABLE `tbl_compgriev` (
  `complaint_id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `dateFiled` date NOT NULL,
  `caseNumber` varchar(20) NOT NULL,
  `complainant` varchar(100) NOT NULL,
  `respondent` varchar(100) NOT NULL,
  `victim` varchar(100) DEFAULT NULL,
  `witness` varchar(100) DEFAULT NULL,
  `natureOfCase` varchar(100) NOT NULL,
  `comp_desc` varchar(255) DEFAULT NULL,
  `actionTaken` text DEFAULT NULL,
  `brgyOfficer_id` int(11) NOT NULL,
  `document_path` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_compgriev`
--

INSERT INTO `tbl_compgriev` (`complaint_id`, `res_id`, `user_id`, `dateFiled`, `caseNumber`, `complainant`, `respondent`, `victim`, `witness`, `natureOfCase`, `comp_desc`, `actionTaken`, `brgyOfficer_id`, `document_path`, `status`, `created_at`) VALUES
(15, 29, '2', '2025-08-29', 'CASE-20250829-9722', 'Kim ', 'Hello', 'Kathleen', 'Krixtine', 'Neighborly Dispute', '0', NULL, 1, '1756451857_68b1541115cf3_Screenshot 2025-08-15 232342.png', 'To Be Approved', '2025-08-29 07:17:37'),
(16, 46, '5', '2025-08-30', 'CASE-20250830-5723', 'Kana', 'Rash', '', '', 'Family Dispute', '0', NULL, 1, '1756560322_68b2fbc295508_meow.png', 'To Be Approved', '2025-08-30 13:25:22'),
(17, 46, '5', '2025-08-30', 'CASE-20250830-6599', 'Meowshi', 'Meow Lord', '', '', 'Family Dispute', '0', NULL, 1, '1756561366_68b2ffd6516f2_Screenshot 2025-08-06 103239.png', 'To Be Approved', '2025-08-30 13:42:46'),
(21, 46, '5', '2025-08-30', 'CASE-20250830-4232', 'Mareng', 'Ana', '', '', 'Neighborly Dispute', 'meow meow meow', NULL, 1, '1756563796_68b309549aa61_Screenshot (186).png', 'To Be Approved', '2025-08-30 14:23:16');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_event`
--

CREATE TABLE `tbl_event` (
  `event_id` int(11) NOT NULL,
  `brgyOfficer_id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `comments` varchar(255) NOT NULL,
  `comment_date` datetime NOT NULL,
  `dateCreated` datetime DEFAULT current_timestamp(),
  `lastEdited` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_event`
--

INSERT INTO `tbl_event` (`event_id`, `brgyOfficer_id`, `res_id`, `user_id`, `title`, `description`, `image`, `name`, `role`, `comments`, `comment_date`, `dateCreated`, `lastEdited`) VALUES
(9, 4, 0, '4', '4ps meeting', 'Join us for an important meeting where we’ll discuss updates, benefits, and opportunities available to you.\r\nYour participation is vital in ensuring we continue building a stronger community together!\r\n\r\nSo come NOW!', '1746298414_imho-community-governance.webp', '', '', '', '0000-00-00 00:00:00', '2025-04-16 22:01:56', '2025-06-04 18:45:23'),
(10, 4, 0, '4', 'Senior Citizen', 'Join us for an important event dedicated to enhancing your well-being and providing useful information.\r\nStay connected, stay informed, and let’s strengthen our community together!', '1746298513_senior-citizen-20151008.webp', '', '', '', '0000-00-00 00:00:00', '2025-04-17 18:04:18', '2025-05-03 18:59:51'),
(11, 4, 0, '4', 'Health Meeting', '🩺 Upcoming Health Meeting\r\nJoin us for an important health meeting focused on community wellness.\r\nEveryone is welcome—let’s work together for a healthier future!', '1746347549_123456.PNG', '', '', '', '0000-00-00 00:00:00', '2025-04-17 23:29:48', '2025-06-07 04:50:21');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_event_comments`
--

CREATE TABLE `tbl_event_comments` (
  `comment_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `comment` text NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `is_official` tinyint(1) DEFAULT 0,
  `comment_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_event_comments`
--

INSERT INTO `tbl_event_comments` (`comment_id`, `event_id`, `user_id`, `comment`, `position`, `is_official`, `comment_date`) VALUES
(22, 11, '5', 'hand', 'Resident', 1, '2025-08-30 14:33:31'),
(23, 11, '2', 'Mario', 'Resident', 1, '2025-08-30 15:13:40'),
(24, 11, '2', 'Mario?', 'Resident', 1, '2025-08-30 15:18:08'),
(25, 11, '2', 'mario', 'Resident', 1, '2025-08-30 15:19:31'),
(26, 10, '2', 'mario', 'Resident', 1, '2025-08-30 15:21:13'),
(27, 9, '2', 'MATIO', 'Resident', 1, '2025-08-30 15:28:22');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_feedback`
--

CREATE TABLE `tbl_feedback` (
  `feedback_id` int(11) NOT NULL,
  `res_id` int(11) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `brgyOfficer_id` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `action` text DEFAULT NULL,
  `action_by` varchar(100) DEFAULT NULL,
  `dateCreated` datetime DEFAULT current_timestamp(),
  `lastEdited` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_feedback`
--

INSERT INTO `tbl_feedback` (`feedback_id`, `res_id`, `user_id`, `brgyOfficer_id`, `feedback`, `action`, `action_by`, `dateCreated`, `lastEdited`) VALUES
(8, 46, '5', NULL, 'hat', NULL, NULL, '2025-08-30 13:31:55', NULL),
(9, 29, '2', NULL, 'Hello', NULL, NULL, '2025-08-30 15:33:47', NULL),
(10, 31, '4', NULL, 'Hello hihi', NULL, NULL, '2025-08-30 15:34:48', '2025-08-30 15:46:55');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_generated_documents`
--

CREATE TABLE `tbl_generated_documents` (
  `document_id` int(11) NOT NULL,
  `certification_id` int(11) DEFAULT NULL,
  `resident_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `document_type` enum('Certificate','Clearance','ID') NOT NULL,
  `document_subtype` varchar(50) NOT NULL,
  `purpose` varchar(50) NOT NULL,
  `other_purpose` text DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_household_head`
--

CREATE TABLE `tbl_household_head` (
  `household_head_id` int(11) NOT NULL,
  `user_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_created` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_household_head`
--

INSERT INTO `tbl_household_head` (`household_head_id`, `user_id`, `date_created`) VALUES
(9, '1', '2025-09-21 22:34:32'),
(11, '3', '2025-09-21 23:24:11');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_household_relation`
--

CREATE TABLE `tbl_household_relation` (
  `thr_id` int(11) NOT NULL,
  `thr_head_id` int(11) NOT NULL,
  `thr_user_id` int(11) NOT NULL,
  `thr_relationship` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_household_relation`
--

INSERT INTO `tbl_household_relation` (`thr_id`, `thr_head_id`, `thr_user_id`, `thr_relationship`) VALUES
(13, 9, 2, 'Relative');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

CREATE TABLE `tbl_notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `notification_type` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notifications`
--

INSERT INTO `tbl_notifications` (`notification_id`, `user_id`, `message`, `notification_type`, `date_created`, `is_read`) VALUES
(45, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-04-16 17:44:36', 1),
(46, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 00:34:28', 0),
(47, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 00:34:31', 0),
(48, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 00:43:06', 0),
(49, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 00:46:56', 0),
(50, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 00:50:11', 0),
(51, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 00:50:16', 0),
(52, '3', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-05-01 01:24:32', 0),
(53, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 01:24:44', 0),
(54, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 01:54:40', 0),
(55, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 02:06:29', 0),
(56, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 07:57:37', 0),
(57, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 07:59:18', 0),
(58, '3', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-05-01 08:00:17', 0),
(59, '3', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-05-01 08:00:25', 0),
(60, '3', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-05-01 08:01:42', 0),
(61, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 08:01:49', 0),
(62, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 08:01:57', 0),
(63, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 08:02:27', 0),
(64, '3', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-05-01 08:38:02', 0),
(65, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 08:38:08', 0),
(66, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 08:58:48', 0),
(67, '3', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-05-01 09:18:49', 0),
(68, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 09:29:12', 0),
(69, '3', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-05-01 12:02:57', 0),
(70, '3', 'Your clearance request has been updated to: Approved', 'clearance_update', '2025-05-01 12:11:38', 0),
(71, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 15:48:10', 0),
(72, '3', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-05-01 16:11:29', 0),
(73, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 16:11:37', 0),
(74, '1', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 19:57:11', 1),
(75, '1', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 20:05:24', 1),
(76, '1', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 21:17:39', 1),
(77, '1', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 21:38:39', 1),
(78, '1', 'Your barangay ID request has been updated to: Approved', 'bid_update', '2025-05-02 08:33:35', 0),
(79, '1', 'Your barangay ID request has been updated to: Approved', 'bid_update', '2025-05-02 08:33:37', 0),
(80, '1', 'Your barangay ID request has been updated to: Approved', 'bid_update', '2025-05-02 08:33:51', 0),
(81, '1', 'Your clearance request has been updated to: Approved', 'clearance_update', '2025-05-02 12:32:47', 0),
(82, '1', 'Your clearance request has been updated to: Approved', 'clearance_update', '2025-05-02 12:32:53', 0),
(83, '1', 'Your clearance request has been updated to: Approved', 'clearance_update', '2025-05-02 12:33:00', 0),
(84, '1', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-02 21:34:52', 0),
(85, '3', 'Your clearance request has been updated to: Approved', 'clearance_update', '2025-05-04 07:59:32', 0),
(86, '3', 'Your clearance request has been updated to: Approved', 'clearance_update', '2025-05-04 08:01:06', 0),
(87, '3', 'Your clearance request has been updated to: Approved', 'clearance_update', '2025-05-04 08:08:07', 0),
(88, '1', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-05-04 08:12:46', 0),
(89, '3', 'Your clearance request has been updated to: Approved', 'clearance_update', '2025-05-04 08:13:37', 0),
(90, '3', 'Your clearance request has been updated to: Approved', 'clearance_update', '2025-05-04 08:15:51', 0),
(91, '3', 'Your clearance request has been updated to: Approved', 'clearance_update', '2025-05-04 08:18:00', 0),
(92, '3', 'Your clearance request has been updated to: Approved', 'clearance_update', '2025-05-04 08:23:35', 0),
(93, '1', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-06-03 15:12:57', 0),
(94, '1', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-06-03 15:13:27', 0),
(113, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-29 06:29:06', 0),
(114, '2', 'Your clearance request has been updated to: Approved', 'clearance_update', '2025-08-29 06:47:36', 0),
(115, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-30 13:32:40', 0),
(116, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-30 15:12:50', 0),
(117, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-30 15:27:43', 0),
(118, '2', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-30 15:28:09', 0),
(119, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-30 15:28:19', 0),
(120, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-30 15:28:32', 0),
(121, '2', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-30 15:28:52', 0),
(122, '2', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-30 15:28:59', 0),
(123, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-30 15:29:08', 0),
(124, '2', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-30 15:29:51', 0),
(125, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-30 15:30:04', 0),
(126, '2', 'Your certificate request has been updated to: Pending', 'certification_update', '2025-08-30 16:07:57', 0),
(127, '2', 'Your certificate request has been updated to: Pending', 'certification_update', '2025-08-30 16:08:05', 0),
(128, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-30 16:08:20', 0),
(129, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-30 16:11:36', 0),
(130, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-30 16:12:30', 0),
(131, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-30 16:35:12', 0),
(132, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-30 16:52:14', 0),
(133, '5', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-30 17:29:56', 0),
(134, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-30 17:30:08', 0),
(135, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-30 17:32:21', 0),
(136, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-30 17:32:46', 0),
(137, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 11:32:18', 0),
(138, '2', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 11:33:42', 0),
(139, '5', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 11:33:51', 0),
(140, '5', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 11:33:59', 0),
(141, '2', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 11:34:07', 0),
(142, '2', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 11:34:15', 0),
(143, '2', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 11:34:21', 0),
(144, '2', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 11:34:27', 0),
(145, '5', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 11:34:33', 0),
(146, '2', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 11:34:44', 0),
(147, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 11:35:21', 0),
(148, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 11:36:00', 0),
(149, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 11:38:00', 0),
(150, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 11:38:22', 0),
(151, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 11:55:01', 0),
(152, '2', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 11:55:29', 0),
(153, '2', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 11:55:34', 0),
(154, '2', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 11:55:41', 0),
(155, '5', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 11:55:47', 0),
(156, '2', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 11:55:54', 0),
(157, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 11:56:02', 0),
(158, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 11:56:17', 0),
(159, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 11:56:38', 0),
(160, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 11:56:55', 0),
(161, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 12:02:03', 0),
(162, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 12:02:48', 0),
(163, '5', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 12:11:42', 0),
(164, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 12:11:48', 0),
(165, '2', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 12:14:21', 0),
(166, '5', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 12:16:53', 0),
(167, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 12:16:58', 0),
(168, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 12:17:46', 0),
(169, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 12:27:49', 0),
(170, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 12:28:00', 0),
(171, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 12:32:54', 0),
(172, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 12:49:16', 0),
(173, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 12:50:57', 0),
(174, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 12:55:37', 0),
(175, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 12:57:23', 0),
(176, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 12:58:09', 0),
(177, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 12:59:10', 0),
(178, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 12:59:38', 0),
(179, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:00:20', 0),
(180, '5', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 13:00:45', 0),
(181, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:00:52', 0),
(182, '5', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 13:01:07', 0),
(183, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:01:13', 0),
(184, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:02:34', 0),
(185, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:03:10', 0),
(186, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:04:15', 0),
(187, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:13:14', 0),
(188, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:16:11', 0),
(189, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:16:22', 0),
(190, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:19:14', 0),
(191, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:21:32', 0),
(192, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:26:25', 0),
(193, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:27:07', 0),
(194, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:28:19', 0),
(195, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:29:33', 0),
(196, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:34:19', 0),
(197, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:35:44', 0),
(198, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:35:58', 0),
(199, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:38:05', 0),
(200, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:38:13', 0),
(201, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:42:20', 0),
(202, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:42:28', 0),
(203, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:42:48', 0),
(204, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:44:11', 0),
(205, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:44:40', 0),
(206, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:46:56', 0),
(207, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:47:05', 0),
(208, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:48:07', 0),
(209, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:49:02', 0),
(210, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:49:34', 0),
(211, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:54:46', 0),
(212, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 13:54:55', 0),
(213, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 14:49:13', 0),
(214, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 14:49:20', 0),
(215, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 14:49:29', 0),
(216, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 14:49:39', 0),
(217, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:14:17', 0),
(218, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:16:03', 0),
(219, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:16:17', 0),
(220, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:17:00', 0),
(221, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:17:07', 0),
(222, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:20:20', 0),
(223, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:22:13', 0),
(224, '5', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 15:22:20', 0),
(225, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:22:26', 0),
(226, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:37:13', 0),
(227, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:37:21', 0),
(228, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:38:52', 0),
(229, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:39:01', 0),
(230, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:39:15', 0),
(231, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:42:31', 0),
(232, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:42:39', 0),
(233, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:48:03', 0),
(234, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:48:10', 0),
(235, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:55:54', 0),
(236, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:56:00', 0),
(237, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:59:13', 0),
(238, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:59:18', 0),
(239, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 15:59:23', 0),
(240, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:01:26', 0),
(241, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:02:17', 0),
(242, '5', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 16:03:57', 0),
(243, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:06:36', 0),
(244, '5', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 16:06:51', 0),
(245, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:06:58', 0),
(246, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:07:28', 0),
(247, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:07:36', 0),
(248, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:07:44', 0),
(249, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:11:19', 0),
(250, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:11:26', 0),
(251, '5', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 16:14:28', 0),
(252, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:14:34', 0),
(253, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:15:19', 0),
(254, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:15:26', 0),
(255, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:15:33', 0),
(256, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:17:30', 0),
(257, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:18:03', 0),
(258, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:18:10', 0),
(259, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:20:08', 0),
(260, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:22:27', 0),
(261, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:22:27', 0),
(262, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:22:34', 0),
(263, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:22:34', 0),
(264, '5', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 16:24:46', 0),
(265, '5', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-08-31 16:24:46', 0),
(266, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:24:52', 0),
(267, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:24:52', 0),
(268, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:25:19', 0),
(269, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:25:19', 0),
(270, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:25:24', 0),
(271, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:25:24', 0),
(272, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:25:33', 0),
(273, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:25:33', 0),
(274, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:25:59', 0),
(275, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:25:59', 0),
(276, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:26:04', 0),
(277, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:26:04', 0),
(278, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:32:59', 0),
(279, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:32:59', 0),
(280, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:33:08', 0),
(281, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:33:08', 0),
(282, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:33:15', 0),
(283, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:33:15', 0),
(284, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:33:52', 0),
(285, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:33:52', 0),
(286, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:33:57', 0),
(287, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-08-31 16:33:57', 0),
(288, '5', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-09-01 06:57:54', 0),
(289, '5', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-09-01 06:57:54', 0),
(290, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-09-01 06:57:59', 0),
(291, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-09-01 06:57:59', 0),
(292, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-09-01 06:59:53', 0),
(293, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-09-01 06:59:53', 0),
(294, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-09-01 07:00:01', 0),
(295, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-09-01 07:00:01', 0),
(296, '5', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-09-01 07:25:07', 0),
(297, '8', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-09-05 16:06:09', 0),
(298, '8', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-09-05 17:02:04', 0),
(299, '8', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-09-05 17:03:36', 0),
(300, '8', 'Your certificate request has been updated to: Pending', 'certification_update', '2025-09-05 17:49:17', 0),
(301, '8', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-09-05 17:59:32', 0),
(302, '8', 'Your barangay ID request has been updated to: Approved', 'bid_update', '2025-09-06 10:21:46', 0),
(303, '8', 'Your barangay ID request has been updated to: Approved', 'bid_update', '2025-09-06 10:36:31', 0),
(304, '8', 'Your barangay ID request has been updated to: Approved', 'bid_update', '2025-09-06 10:37:55', 0),
(305, '8', 'Your Barangay ID request has been updated to: Approved', 'bid_update', '2025-09-06 16:48:24', 0),
(306, '8', 'Your Barangay ID request has been updated to: Approved', 'bid_update', '2025-09-06 16:48:24', 0),
(307, '8', 'Your Barangay ID request has been updated to: Approved', 'bid_update', '2025-09-06 17:07:11', 0),
(308, '8', 'Your Barangay ID request has been updated to: Approved', 'bid_update', '2025-09-06 17:07:19', 0),
(309, '8', 'Your Barangay ID request has been updated to: Approved', 'bid_update', '2025-09-06 17:12:48', 0),
(310, '8', 'Your Barangay ID request has been updated to: Approved', 'bid_update', '2025-09-06 17:13:32', 0),
(311, '8', 'Your Barangay ID request has been updated to: Approved', 'bid_update', '2025-09-06 17:22:49', 0),
(312, '8', 'Your Barangay ID request has been updated to: Approved', 'bid_update', '2025-09-06 17:25:04', 1),
(313, '8', 'Your barangay ID request has been updated to: Approved', 'bid_update', '2025-09-06 11:29:46', 0),
(314, '8', 'Your barangay ID request has been updated to: Approved', 'bid_update', '2025-09-06 11:38:39', 0),
(315, '8', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-09-15 06:51:37', 0),
(316, '8', 'Your clearance request has been updated to: Approved', 'clearance_update', '2025-09-15 08:43:19', 1),
(317, '9', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-09-16 16:07:35', 0),
(318, '8', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-09-21 14:19:48', 1),
(319, '8', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-09-21 14:29:49', 1),
(320, '8', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-09-21 14:38:49', 0),
(321, '8', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-09-21 15:11:38', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_residentcop`
--

CREATE TABLE `tbl_residentcop` (
  `resident_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `birthdate` date NOT NULL,
  `birthplace` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `marital_status` enum('Single','Married','Widowed','Divorced','Separated') NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text NOT NULL,
  `precint_number` varchar(50) DEFAULT NULL,
  `voter_status` enum('Active','Inactive','Not Registered') DEFAULT 'Not Registered',
  `blood_type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-','Unknown') DEFAULT 'Unknown',
  `height` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `type_of_id` varchar(100) DEFAULT NULL,
  `id_number` varchar(100) DEFAULT NULL,
  `barangay_number` varchar(20) DEFAULT NULL,
  `SSSGSIS_Number` varchar(20) NOT NULL,
  `TIN_number` varchar(15) NOT NULL,
  `is_household_head` tinyint(1) DEFAULT 0,
  `household_head_name` varchar(255) DEFAULT NULL,
  `relationship_to_head` varchar(100) DEFAULT NULL,
  `is_senior_citizen` enum('Yes','No') DEFAULT 'No',
  `senior_document` varchar(255) DEFAULT NULL,
  `is_pwd` enum('Yes','No') DEFAULT 'No',
  `pwd_document` varchar(255) DEFAULT NULL,
  `is_registered_voter` enum('Yes','No') DEFAULT 'No',
  `voter_document` varchar(255) DEFAULT NULL,
  `proof_of_residency_document` varchar(255) DEFAULT NULL,
  `is_4ps_member` enum('Yes','No') DEFAULT 'No',
  `residency_tenure` enum('Temporary','Permanent') DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_residentcop`
--

INSERT INTO `tbl_residentcop` (`resident_id`, `user_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `birthdate`, `birthplace`, `gender`, `marital_status`, `phone_number`, `address`, `precint_number`, `voter_status`, `blood_type`, `height`, `weight`, `type_of_id`, `id_number`, `barangay_number`, `SSSGSIS_Number`, `TIN_number`, `is_household_head`, `household_head_name`, `relationship_to_head`, `is_senior_citizen`, `senior_document`, `is_pwd`, `pwd_document`, `is_registered_voter`, `voter_document`, `proof_of_residency_document`, `is_4ps_member`, `residency_tenure`, `occupation`, `email`, `image`, `created_at`, `updated_at`) VALUES
(5, 1, 'Nico', 'fghjkl', 'Robin', 'fuygjkh', '2007-01-17', 'Sampaloc City', 'Female', 'Single', '89465132879', '234567654321ewrsdgfhjk', '342sfsdfs', 'Not Registered', 'B+', 234.00, 32.00, 'UMID', '24354678', '324543245324543543', '423578oi87654', '234567897654', 0, '', '', 'Yes', NULL, 'No', '', 'No', NULL, '', 'No', '', 'N/A', 'panis.kathleennicole@ue.edu.ph', NULL, '2025-04-29 17:23:14', '2025-05-02 14:22:17'),
(8, 32407, 'Nami', '', 'Ocean', '', '2025-05-01', '', 'Female', 'Single', '01234567899', 'East Blue, Sampaloc Manila', NULL, 'Not Registered', 'Unknown', NULL, NULL, NULL, NULL, NULL, '', '', 0, '', '', 'No', '', 'No', '', 'No', '', '', 'No', NULL, 'N/A', 'storagekath@gmail.com', NULL, '2025-05-02 14:38:45', '2025-05-02 14:38:45'),
(9, 2, 'Ace', 'D', 'Portgas', '', '2025-05-01', '', 'Male', 'Single', '09999999999', '42356ryhgfasdfghjgf', NULL, 'Not Registered', 'Unknown', NULL, NULL, NULL, NULL, NULL, '', '', 0, '', '', 'No', '', 'No', '', 'No', '', '', 'No', NULL, 'N/A', 'nicolepanis421@gmail.com', NULL, '2025-05-02 20:50:06', '2025-05-02 20:50:06');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_residents`
--

CREATE TABLE `tbl_residents` (
  `res_id` int(11) NOT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `household_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `birthday` date NOT NULL,
  `birthplace` varchar(100) NOT NULL,
  `civilStatus` enum('Single','Married','Widowed','Divorced','Separated') NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` text NOT NULL,
  `precinctNumber` varchar(50) DEFAULT NULL,
  `residentStatus` varchar(20) NOT NULL,
  `voterStatus` enum('Active','Inactive','Not Registered') DEFAULT 'Not Registered',
  `bloodType` enum('A+','A-','B+','B-','AB+','AB-','O+','O-','Unknown') DEFAULT 'Unknown',
  `height` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `typeOfID` varchar(100) DEFAULT NULL,
  `IDNumber` varchar(100) DEFAULT NULL,
  `SSSGSIS_Number` varchar(20) DEFAULT NULL,
  `TIN_number` varchar(15) DEFAULT NULL,
  `barangay_number` varchar(20) DEFAULT NULL,
  `is_senior` enum('Yes','No') DEFAULT 'No',
  `is_pwd` enum('Yes','No') DEFAULT 'No',
  `is_4ps_member` enum('Yes','No') DEFAULT 'No',
  `suffix` varchar(20) DEFAULT NULL,
  `is_household_head` varchar(60) DEFAULT NULL,
  `household_head_name` varchar(255) DEFAULT NULL,
  `relationship_to_head` varchar(100) DEFAULT NULL,
  `senior_document` varchar(255) DEFAULT NULL,
  `pwd_document` varchar(255) DEFAULT NULL,
  `is_registered_voter` enum('Yes','No') DEFAULT 'No',
  `voter_document` varchar(255) DEFAULT NULL,
  `proof_of_residency_document` varchar(255) DEFAULT NULL,
  `residency_tenure` enum('Temporary','Permanent') DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_residents`
--

INSERT INTO `tbl_residents` (`res_id`, `user_id`, `household_id`, `first_name`, `middle_name`, `last_name`, `birthday`, `birthplace`, `civilStatus`, `mobile`, `gender`, `address`, `precinctNumber`, `residentStatus`, `voterStatus`, `bloodType`, `height`, `weight`, `typeOfID`, `IDNumber`, `SSSGSIS_Number`, `TIN_number`, `barangay_number`, `is_senior`, `is_pwd`, `is_4ps_member`, `suffix`, `is_household_head`, `household_head_name`, `relationship_to_head`, `senior_document`, `pwd_document`, `is_registered_voter`, `voter_document`, `proof_of_residency_document`, `residency_tenure`, `occupation`, `email`, `image`, `created_at`, `updated_at`) VALUES
(51, '1', 0, 'john', '', 'doe', '2020-09-21', '', 'Single', '09454454744', 'Female', 'mexico pampanga', NULL, '', 'Not Registered', 'Unknown', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'No', 'No', 'No', '', 'Yes', '', '', '', '', 'Yes', 'uploads/68d00cad6aa87__colo__naruto_coloring_normal___2016_by_naruttebayo67_d9vjdsx-pre.jpg', 'uploads/68d00cad6a841_492151840_3128831320602859_4159043562509539743_n.jpg', NULL, 'N/A', 'rodriguezryan325@gmail.com', NULL, '2025-09-21 14:34:32', '2025-09-21 16:27:24'),
(60, '2', 0, 'Joshua', 'Anderson', 'doe', '2000-09-21', '', 'Single', '09454454744', 'Male', 'mexico pampanga', NULL, '', 'Not Registered', 'Unknown', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'No', 'No', 'No', '', 'No', '9', 'Relative', '', '', 'No', '', 'uploads/68d017802f252_image-w856.webp', NULL, 'N/A', 'padillajoshuaanderson.pdm@gmail.com', NULL, '2025-09-21 15:19:44', '2025-09-21 16:27:55'),
(61, '3', 0, 'cevin', '', 'garnet', '2020-09-21', '', 'Married', '09454454744', 'Male', 'sta.rosa 2 marilao', NULL, '', 'Not Registered', 'Unknown', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 'No', 'No', '', 'Yes', '', '', '', '', 'No', '', 'uploads/68d0188971c90_494820713_532984179748342_8788347790273388241_n.png', NULL, 'N/A', 'masterparj@gmail.com', NULL, '2025-09-21 15:24:11', '2025-09-21 16:27:26');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT 'default.png',
  `email` varchar(255) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `address` text NOT NULL,
  `account_status` enum('Pending','Active','Inactive') DEFAULT 'Pending',
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `is_logged_in` tinyint(1) DEFAULT 0,
  `is_logged_in_time` datetime DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `terms` tinyint(1) DEFAULT 0,
  `suffix` varchar(20) DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `civilStatus` enum('Single','Married','Widowed','Divorced','Separated') NOT NULL,
  `household_head_id` int(11) NOT NULL,
  `is_household_head` enum('Yes','No') NOT NULL,
  `household_head_name` varchar(255) DEFAULT NULL,
  `relationship_to_head` varchar(100) DEFAULT NULL,
  `is_senior` enum('Yes','No') DEFAULT 'No',
  `senior_document` varchar(255) DEFAULT NULL,
  `is_pwd` enum('Yes','No') DEFAULT 'No',
  `pwd_document` varchar(255) DEFAULT NULL,
  `is_registered_voter` enum('Yes','No') DEFAULT 'No',
  `voter_document` varchar(255) DEFAULT NULL,
  `proof_of_residency` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT 'N/A',
  `birthday` date NOT NULL DEFAULT '2000-01-01'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `user_id`, `first_name`, `middle_name`, `last_name`, `password`, `image`, `email`, `mobile`, `address`, `account_status`, `role`, `is_logged_in`, `is_logged_in_time`, `remember_token`, `reset_token`, `reset_token_expiry`, `terms`, `suffix`, `gender`, `civilStatus`, `household_head_id`, `is_household_head`, `household_head_name`, `relationship_to_head`, `is_senior`, `senior_document`, `is_pwd`, `pwd_document`, `is_registered_voter`, `voter_document`, `proof_of_residency`, `occupation`, `birthday`) VALUES
(131, 'admin_689747ed65a8b', 'Chris', '', 'Pompom', '$2y$10$i9gTkQf3NpS0TBJQmewS7eZbNdX/nSxZD3gig0JYU2xwLvvtjSCqG', 'default.png', 'paniskathleen@gmail.com', NULL, '', 'Active', 'admin', 1, '2025-09-21 23:25:20', NULL, NULL, NULL, 0, NULL, 'Male', 'Single', 0, 'No', NULL, NULL, 'No', NULL, 'No', NULL, 'No', NULL, NULL, 'N/A', '2000-01-01'),
(133, 'U001', 'Chris', 'A', 'Karenina', '$2y$10$V41atPylhHr1qCssFBZkEeFZD4SHEl3lmDsLu8zQ6OGCRAhImIqtO', 'U001_profile_1756441999.png', 'shelfmind508@gmail.com', '32456789765', 'fdgghj,dgfhgh', 'Active', 'resident', 1, '2025-09-21 23:25:20', NULL, NULL, NULL, 1, NULL, 'Male', 'Single', 0, 'No', NULL, NULL, 'No', NULL, 'No', NULL, 'No', NULL, NULL, 'N/A', '2000-01-01'),
(135, 'officer_68b154ea14f76', 'Chris', '', 'Karenina', '$2y$10$p.MZw0jFTHtbwnRvPO2VquxIHEt77JMhx3NlkDxnTlCZbYxQdK1uW', 'default.png', 'calitarte421@gmail.com', NULL, '', 'Active', 'barangay_official', 1, '2025-09-21 23:25:20', NULL, NULL, NULL, 0, NULL, 'Male', 'Single', 0, 'No', NULL, NULL, 'No', NULL, 'No', NULL, 'No', NULL, NULL, 'N/A', '2000-01-01'),
(136, 'officer_68b165b5b0925', 'Winter', '', 'Esteban', '$2y$10$uxc64jcEteBn1/SpTHkY2OKZIITdx.2VpmnVUJocI/2FaeQNJnr9y', 'default.png', 'sample@gmail.com', NULL, '', 'Active', 'barangay_official', 1, '2025-09-21 23:25:20', NULL, NULL, NULL, 0, NULL, 'Male', 'Single', 0, 'No', NULL, NULL, 'No', NULL, 'No', NULL, 'No', NULL, NULL, 'N/A', '2000-01-01'),
(138, 'admin_68b2f995c6cde', 'Dorothy', '', 'Jian', '$2y$10$.GUn5Is/WM/VI5ne8Zv3NuRawi3IK5I4HNteJPUHmEq/LaZmNtdOq', 'default.png', 'dorothy@gmail.com', NULL, '', 'Active', 'admin', 1, '2025-09-21 23:25:20', NULL, NULL, NULL, 0, NULL, 'Male', 'Single', 0, 'Yes', NULL, NULL, 'No', NULL, 'No', NULL, 'No', NULL, NULL, 'N/A', '2000-01-01'),
(143, '1', 'john', '', 'doe', '$2y$10$VPQp5ETg9Yd06dUXPIpER.GjC0IkOEhro2iwt6t51l7RN7hQ.q4Ka', NULL, 'rodriguezryan325@gmail.com', '09454454744', 'mexico pampanga', 'Pending', 'Resident', 0, NULL, NULL, NULL, NULL, 0, '', 'Female', 'Single', 0, 'Yes', '', '', 'No', '', 'No', '', 'Yes', 'uploads/68d00cad6aa87__colo__naruto_coloring_normal___2016_by_naruttebayo67_d9vjdsx-pre.jpg', 'uploads/68d00cad6a841_492151840_3128831320602859_4159043562509539743_n.jpg', 'N/A', '2025-09-11'),
(153, '2', 'Joshua', 'Anderson', 'doe', '$2y$10$ohpLW.fZhfVG9wkSZhTMp.uv5gEIzTyJjk.FydGt3/2ZcMDMBRB..', NULL, 'padillajoshuaanderson.pdm@gmail.com', '09454454744', 'mexico pampanga', 'Pending', 'Resident', 0, NULL, NULL, NULL, NULL, 0, '', 'Male', 'Single', 0, 'No', '9', 'Relative', 'No', '', 'No', '', 'No', '', 'uploads/68d017802f252_image-w856.webp', 'N/A', '2025-09-21'),
(155, '3', 'cevin', '', 'garnet', '$2y$10$hSu03t9KPk3PRZjDrA6JdulAlsl7CcM7FcEWoRx.NHqzXuo5u1oj.', NULL, 'masterparj@gmail.com', '09454454744', 'sta.rosa 2 marilao', 'Pending', 'Resident', 0, NULL, NULL, NULL, NULL, 0, '', 'Male', 'Married', 0, 'Yes', '', '', '', '', 'No', '', 'No', '', 'uploads/68d0188971c90_494820713_532984179748342_8788347790273388241_n.png', 'N/A', '2025-09-21');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_cop`
--

CREATE TABLE `tbl_user_cop` (
  `userID` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `birthdate` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `marital_status` enum('Single','Married','Widowed','Divorced','Separated') NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `is_household_head` enum('Yes','No') NOT NULL,
  `household_head_name` varchar(255) DEFAULT NULL,
  `relationship_to_head` varchar(100) DEFAULT NULL,
  `address` text NOT NULL,
  `is_senior_citizen` enum('Yes','No') DEFAULT 'No',
  `senior_document` varchar(255) DEFAULT NULL,
  `is_pwd` enum('Yes','No') DEFAULT 'No',
  `pwd_document` varchar(255) DEFAULT NULL,
  `is_registered_voter` enum('Yes','No') DEFAULT 'No',
  `voter_document` varchar(255) DEFAULT NULL,
  `proof_of_residency` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT 'N/A',
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `image` varchar(255) DEFAULT 'default.png',
  `account_status` enum('Pending','Verified','Inactive') DEFAULT 'Pending',
  `is_logged_in_time` datetime DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `terms` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_logged_in` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user_cop`
--

INSERT INTO `tbl_user_cop` (`userID`, `user_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `birthdate`, `gender`, `marital_status`, `phone_number`, `is_household_head`, `household_head_name`, `relationship_to_head`, `address`, `is_senior_citizen`, `senior_document`, `is_pwd`, `pwd_document`, `is_registered_voter`, `voter_document`, `proof_of_residency`, `occupation`, `email`, `password`, `role`, `image`, `account_status`, `is_logged_in_time`, `remember_token`, `reset_token`, `reset_token_expiry`, `terms`, `created_at`, `updated_at`, `is_logged_in`) VALUES
(2, 'admin_680fd53ebc708', 'Luffy', '', 'Monkey', NULL, '0000-00-00', 'Male', 'Single', '32456789876', 'Yes', NULL, NULL, 'sadfghjkljhgfdsadfghj', 'No', '', 'No', NULL, 'No', '', NULL, 'N/A', 'paniskathleen@gmail.com', '$2y$10$lgHWS4OOmSP5Y81JE1tRxuMSXpYBRwE2ZcMZy0KsTUfG6e56f484C', 'admin', 'default.png', 'Verified', '2025-05-03 04:50:49', NULL, 'cf3bd3fcb05e6e59010033145f7bef4eb01fbe62e5be9e67c9d0210c0859052e', '2025-04-29 05:38:43', 0, '2025-04-28 19:21:34', '2025-05-02 20:50:49', 1),
(47, '1', 'Nico', '', 'Robin', '', '2025-04-02', 'Female', 'Single', '89465132879', 'Yes', '', '', '234567654321ewrsdgfhjk', 'No', '', 'Yes', '', 'No', '', '', 'N/A', 'panis.kathleennicole@ue.edu.ph', '$2y$10$5z5EFjBJmOE2gdQwFZcw0.C3m6zpmSRUsrE5UvMivXIs6glB37H7W', 'Resident', NULL, 'Verified', NULL, NULL, NULL, NULL, 0, '2025-04-29 17:23:14', '2025-05-02 20:38:16', 0),
(61, '2D352E9CFC68D9DF07D414CB0A2C307A', 'Ace', 'D', 'Portgas', '', '2025-05-01', 'Male', 'Single', '09999999999', 'Yes', '', '', '42356ryhgfasdfghjgf', 'No', '', 'No', '', 'No', '', '', 'N/A', 'nicolepanis421@gmail.com', '$2y$10$95v6vQDgR8UIPJS9mzCDXuEW26pnScdgkAnt9cyrN2ezLUADB34um', 'Resident', NULL, 'Pending', NULL, NULL, NULL, NULL, 0, '2025-05-02 20:50:06', '2025-05-02 20:50:06', 0);

-- --------------------------------------------------------

--
-- Table structure for table `web_officials`
--

CREATE TABLE `web_officials` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `committee` enum('Barangay Council','Barangay Committees','Sangguniang Kabataan') NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `web_officials`
--

INSERT INTO `web_officials` (`id`, `name`, `position`, `committee`, `email`, `image`, `created_at`) VALUES
(1, 'Felix C. Taguba', 'Barangay Captain', 'Barangay Council', 'Felixtaguba@gmail.com', 'Capture.PNG', '2025-04-25 23:57:25'),
(2, 'Imelda M. Saquing', 'Barangay Secretary', 'Barangay Council', 'ImeldaSaquing@gmail.com', 'ChatGPT Image Apr 23, 2025, 02_08_04 AM.png', '2025-04-25 23:58:01'),
(3, 'Analyne M. Bernardino', 'Barangay Treasure', 'Barangay Council', 'AnalyneBernardino@gmail.com', 'Analyne M. Bernardino.JPG', '2025-04-25 23:58:41'),
(4, 'Mario C. Bullo', 'Barangay Kagawad', 'Barangay Council', 'MarioBullo@gmail.com', 'Mario C. Bullo.JPG', '2025-04-25 23:59:07'),
(5, 'Ma. Eleizel D. Cruz', 'Barangay Kagawad', 'Barangay Council', 'MaEleizel@gmail.com', 'Eleizel D. Cruz.JPG', '2025-04-25 23:59:56'),
(6, 'Jesli T. Ramos', 'Barangay Kagawad', 'Barangay Council', 'JesliRamos@gmail.com', 'Jesli T. Ramos.JPG', '2025-04-26 00:00:25'),
(7, 'Liza A. Acosta', 'Barangay Kagawad', 'Barangay Council', 'LizaAcosta@gmail.com', 'Liza A. Acosta.JPG', '2025-04-26 00:01:01'),
(8, 'Elizabeth S. Armada', 'Barangay Kagawad', 'Barangay Council', 'ElizabethArmada@gmail.com', 'Elizabeth S. Armada.JPG', '2025-04-26 00:01:28'),
(9, 'Maricel M. Taguba', 'Barangay Kagawad', 'Barangay Council', 'MaricelTaguba@gmail.com', 'Maricel M. Taguba.JPG', '2025-04-26 00:01:57'),
(10, 'Nestor A. Frio', 'Barangay Kagawad', 'Barangay Council', 'NestorFrio@gmail.com', 'Nestor A Frio.JPG', '2025-04-26 00:02:24'),
(11, 'Luvisminda Young', 'Lupon Tagapamayapa', 'Barangay Committees', 'LuvismindaYoung@gmail.com', 'Luvisminda L. Young(new).png', '2025-04-26 00:21:28'),
(12, 'Shirley A. Armada', 'Lupon Tagapamayapa', 'Barangay Committees', 'ShirleyArmada@gmail.com', 'Shirley A. Armada(new).png', '2025-04-26 00:22:16'),
(13, 'Alfredo D. Empe Jr.', 'Lupon Tagapamayapa', 'Barangay Committees', 'AlfredoEmpe@gmail.com', 'Alfredo Teofisto Empe Jr(new).png', '2025-04-26 00:23:10'),
(14, 'Teresita S. Ventura', 'Lupon Tagapamayapa', 'Barangay Committees', 'TeresitaVentura@gmail.com', 'Teresita Ventura(new).png', '2025-04-26 00:23:36'),
(15, 'Edgar G. Montano', 'Lupon Tagapamayapa', 'Barangay Committees', 'EdgarMontano@gmail.com', 'Edgar Montano (new)).png', '2025-04-26 00:24:00'),
(16, 'Evelyn I. Tubig', 'Lupon Tagapamayapa', 'Barangay Committees', 'EvelynTubig@gmail.com', 'Evelyn I. Tubig(latest).png', '2025-04-26 00:24:29'),
(17, 'Julita A. Florendo', 'Lupon Tagapamayapa', 'Barangay Committees', 'JulitaFlorendo@gmail.com', 'Julita Florendo(new).png', '2025-04-26 00:25:00'),
(18, 'Leilani C. Diaz', 'Lupon Tagapamayapa', 'Barangay Committees', 'LeilaniDiaz@gmail.com', 'Leilani C. DIaz (latest).png', '2025-04-26 00:25:30'),
(19, 'Enecita P. Tolentino', 'Lupon Tagapamayapa', 'Barangay Committees', 'EnecitaTolentino@gmail.com', 'Enecita Tolentino(new).png', '2025-04-26 00:25:54'),
(20, 'Roberto C. Zarate', 'Lupon Tagapamayapa', 'Barangay Committees', 'RobertoZarate@gmail.com', 'Roberto Zarate(new).png', '2025-04-26 00:26:18'),
(21, 'Ronald Buentipo', 'Barangay Tanod', 'Barangay Committees', 'RonaldBuentipo@gmail.com', 'Ronald C. Buentipo.jpg', '2025-04-26 00:27:36'),
(22, 'Ruel E. Tadena', 'Barangay Tanod', 'Barangay Committees', 'RuelTadena@gmail.com', 'Ruel E. Tadena.png', '2025-04-26 00:27:59'),
(23, 'Jose Malen Jr.', 'Barangay Tanod', 'Barangay Committees', 'JoseMalen@gmail.com', 'Jose T. Mallen Jr..jpg', '2025-04-26 00:28:24'),
(24, 'Warren Bretana', 'Barangay Tanod', 'Barangay Committees', 'WarrenBretana@gmail.com', 'Warren P. Bretaña.jpg', '2025-04-26 00:28:45'),
(25, 'Mario M. Saquing', 'Barangay Tanod', 'Barangay Committees', 'MarioSaquing@gmail.com', 'Mario M. Saquing.jpg', '2025-04-26 00:29:24'),
(26, 'Marvin B. Bullo', 'Barangay Tanod', 'Barangay Committees', 'MarvinBullo@gmail.com', 'Marvin B. Bullo.jpg', '2025-04-26 00:29:54'),
(27, 'Ibrahim Taguinod', 'Barangay Tanod', 'Barangay Committees', 'IbrahimTaguinod@gmail.com', 'Ibrahim L. Taguinod.jpg', '2025-04-26 00:30:13'),
(28, 'Mervin G. Catabay', 'Barangay Tanod', 'Barangay Committees', 'MervinCatabay@gmail.com', 'Mervin G. Catabay.jpg', '2025-04-26 00:30:35'),
(29, 'Frederick Samson', 'Barangay Tanod', 'Barangay Committees', 'FrederickSamson@gmail.com', 'Frederick L. Samson.jpg', '2025-04-26 00:31:01'),
(30, 'Lester Anthony Bernardino', 'Barangay Tanod', 'Barangay Committees', 'LesterBernardino@gmail.com', 'Lester Anthony C. Bernardino.jpg', '2025-04-26 00:31:25'),
(31, 'Ronnel Angkal', 'Barangay Tanod', 'Barangay Committees', 'RonnelAngkal@gmail.com', 'Ronell Benedict F. Angkal.jpg', '2025-04-26 00:31:52'),
(32, 'Oscar Atencio', 'Barangay Tanod', 'Barangay Committees', 'OscarAtencio@gmail.com', 'Oscar N. Atencio.jpg', '2025-04-26 00:32:12'),
(33, 'Vivian Alcantara', 'Barangay Tanod', 'Barangay Committees', 'VivianAlcantara@gmail.com', 'Vivian T. Alcantara.jpg', '2025-04-26 00:32:33'),
(34, 'Rowel L. Apuli', 'Barangay Tanod', 'Barangay Committees', 'RowelApuli@gmail.com', 'Rowel L. Apuli.jpg', '2025-04-26 00:32:54'),
(35, 'Althea Pyra G. Sunodan', 'SK Kagawad', 'Sangguniang Kabataan', 'AltheaSunodan@gmail.com', 'SK Kag. Althea Pyra G. Sunodan.png', '2025-04-26 00:58:17'),
(36, 'Eirnell James V. Baloran', 'SK Kagawad', 'Sangguniang Kabataan', 'EirnellBaloran@gmail.com', 'SK Kag. Eirnell James V. Baloran.jpg', '2025-04-26 00:58:55'),
(37, 'Giea Atabay', 'SK Kagawad', 'Sangguniang Kabataan', 'GieaAtabay@gmail.com', 'SK Kag. Giea Atabay.jpg', '2025-04-26 00:59:26'),
(38, 'Jefrey V. Dagalea', 'SK Kagawad', 'Sangguniang Kabataan', 'JefreyDagalea@gmail.com', 'SK Kag. Jefrey V. Dagalea.jpg', '2025-04-26 01:00:00'),
(39, 'Joevin Russel R. Manalo', 'SK Kagawad', 'Sangguniang Kabataan', 'JoevinManalo@ggmail.com', 'SK Kag. Joevin Russel R. Manalo.jpg', '2025-04-26 01:00:38'),
(40, 'Carlo Jay M. Tolentino', 'SK Kagawad', 'Sangguniang Kabataan', 'CarloTolentino@gmail.com', 'Sk kagawad Carlo Jay M. Tolentino.jpg', '2025-04-26 01:01:09'),
(41, 'Mary Lyzle Gwhenne R. Lo', 'SK Secretary', 'Sangguniang Kabataan', 'MaryLo@gmail.com', 'SK Sec  Mary Lyzle Gwhenne R. Lo.jpg', '2025-04-26 01:01:53');

-- --------------------------------------------------------

--
-- Table structure for table `web_services`
--

CREATE TABLE `web_services` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(100) NOT NULL DEFAULT 'Barangay Certificate',
  `file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `web_services`
--

INSERT INTO `web_services` (`id`, `title`, `description`, `requirements`, `created_at`, `category`, `file`) VALUES
(12, 'Good Moral Character', 'The Certificate of Good Moral Character is an official document issued by the barangay that certifies an individual’s adherence to ethical standards and responsible behavior within the community.\r\n\r\nThe certificate serves as proof that the applicant has no record of involvement in any unlawful activities and has demonstrated respect for the law, fellow residents, and the values upheld by the barangay.\r\n\r\nResidents may request a Certificate of Good Moral Character by visiting the barangay hall or create an Account and submitting with the necessary requirements.', 'Inhabitants Form, Letter from the Admin, Valid ID, Log Sheet', '2025-04-25 19:53:08', 'Barangay Certificate', '680be824e7292_icon1.png'),
(13, 'First-time Job Seeker', 'The First-Time Job Seeker Certification is an official document issued by the barangay to certify that an applicant is seeking employment for the first time.\r\n\r\nIt is in accordance with Republic Act No. 11261, also known as the First-Time Jobseekers Assistance Act, which grants eligible individuals the opportunity to obtain government-issued pre-employment documents for free.\r\n\r\nApplicants may secure the First-Time Job Seeker Certification by visiting the barangay hall or create an Account and submitting the necessary documents.', 'Inhabitants Form,\r\nLetter from the Admin,\r\nOath of Undertaking,\r\nValid ID\r\nLog Sheet', '2025-04-25 20:10:07', 'Barangay Certificate', '680bec1f14b7c_icon2.png'),
(14, 'Calamity', 'The Calamity Certification is an official document issued by the barangay to certify that a resident, or property has been affected by a natural disaster or calamity. This certification serves as proof that the individual or family has suffered damage or loss due to the calamity and is often required to avail of financial assistance, insurance claims, or relief support from government agencies and non-governmental organizations.\r\n\r\nResidents may request a Certificate of Good Moral Character by visiting the barangay hall or create an Account and submitting with the necessary requirements.', 'Inhabitants Form, Valid ID, Log Sheet', '2025-04-25 21:02:04', 'Barangay Certificate', '680bf84c13dd9_icon3.png'),
(17, 'Blotter Report', 'A Blotter Report is an official record filed at the barangay to document incidents, complaints, or disputes involving residents within the community.\r\n\r\nIt serves as an initial record of incidents such as altercations, theft, domestic disputes, vandalism, and other violations that require barangay intervention or mediation.\r\n\r\nResidents may Report a blotter by visiting the barangay hall or create an Account and submitting with the necessary requirements.', 'Valid ID', '2025-04-25 21:54:25', 'Barangay Blotter and Complaint', '680c049110bc6_icon4.png'),
(18, 'Complaints & Grievance', 'Complaints and Grievance Filing is a formal process through which residents of the barangay can report concerns, disputes, or violations affecting their rights, safety, or well-being.\r\n\r\nThis process aims to resolve conflicts amicably through a conciliation hearing where both parties are summoned to discuss and negotiate a solution.\r\n\r\nResidents may file a Complaints and Grievance by visiting the barangay hall or create an Account and submitting with the necessary requirements.', 'Valid ID', '2025-04-25 21:56:18', 'Barangay Blotter and Complaint', '680c05023851e_icon5.png'),
(19, 'ID Issuance', 'Barangay ID Issuance is the process of providing residents with an official identification card that verifies their residency within the barangay.\r\n\r\n\r\nResidents can obtain a Barangay ID by visiting the barangay hall or create an Account and submitting with the necessary requirements.', 'Valid ID, Comelec Stub, Voters ID', '2025-04-25 22:01:15', 'Barangay Clearances and Services', '680c062b4d44f_icon6.png'),
(21, 'Garbage Disposal', 'Garbage Disposal Service is a barangay-managed program that ensures the proper collection, segregation, and disposal of household and community waste.\r\n\r\nResidents can requests a service by visiting the barangay hall or create an Account and submitting with the necessary requirements.', 'NO REQUIREMENTS', '2025-04-25 22:02:38', 'Barangay Clearances and Services', '680c067e7dc58_icon7.png'),
(22, 'Barangay Clearance', 'Barangay Clearance is an official document issued by the barangay that certifies an individual’s good standing and residency within the community.\r\n\r\nResidents can obtain a Barangay Clearance by visiting the barangay hall or create an Account and submitting with the necessary requirements.', 'Inhabitants Form, Valid ID, Letter from Admin', '2025-04-25 22:03:30', 'Barangay Clearances and Services', '680c06b26f022_icon8.png'),
(23, 'Declogging', 'Declogging is a barangay service that involves the removal of blockages from drainage systems, canals, and waterways to prevent flooding and ensure proper water flow.\r\n\r\nResidents can avail this service by visiting the barangay hall or create an Account and submitting with the necessary requirements.', 'Letter Request, Picture of the Canals', '2025-04-25 22:05:02', 'Barangay Clearances and Services', '680c070e70e48_icon9.png'),
(24, 'Community Development Program', 'Community Development Program is a barangay initiative aimed at improving the overall well-being of residents by promoting socio-economic growth, education, health, and environmental sustainability.\r\n\r\nResidents can avail or participate by visiting the barangay hall or create an Account and submitting with the necessary requirements.', 'Valid ID, Letter Request for Approval by Barangay Chairman', '2025-04-25 22:05:45', 'Barangay Clearances and Services', '680c0739ce6fc_icon10.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_audit`
--
ALTER TABLE `tbl_audit`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `res_id` (`res_id`),
  ADD KEY `brgyOfficer_id` (`brgyOfficer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_banned_users`
--
ALTER TABLE `tbl_banned_users`
  ADD PRIMARY KEY (`ban_id`);

--
-- Indexes for table `tbl_bid`
--
ALTER TABLE `tbl_bid`
  ADD PRIMARY KEY (`BID_id`),
  ADD KEY `res_id` (`res_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_blotter`
--
ALTER TABLE `tbl_blotter`
  ADD PRIMARY KEY (`blotter_id`),
  ADD KEY `res_id` (`res_id`),
  ADD KEY `brgyOfficer_id` (`brgyOfficer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_brgyofficer`
--
ALTER TABLE `tbl_brgyofficer`
  ADD PRIMARY KEY (`brgyOfficer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_certification`
--
ALTER TABLE `tbl_certification`
  ADD PRIMARY KEY (`certification_id`),
  ADD KEY `res_id` (`res_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_clearance`
--
ALTER TABLE `tbl_clearance`
  ADD PRIMARY KEY (`clearance_id`),
  ADD KEY `res_id` (`res_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_compgriev`
--
ALTER TABLE `tbl_compgriev`
  ADD PRIMARY KEY (`complaint_id`),
  ADD KEY `res_id` (`res_id`),
  ADD KEY `brgyOfficer_id` (`brgyOfficer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_event`
--
ALTER TABLE `tbl_event`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `brgyOfficer_id` (`brgyOfficer_id`),
  ADD KEY `res_id` (`res_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_event_comments`
--
ALTER TABLE `tbl_event_comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `res_id` (`res_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `brgyOfficer_id` (`brgyOfficer_id`);

--
-- Indexes for table `tbl_generated_documents`
--
ALTER TABLE `tbl_generated_documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `resident_id` (`resident_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_household_head`
--
ALTER TABLE `tbl_household_head`
  ADD PRIMARY KEY (`household_head_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_household_relation`
--
ALTER TABLE `tbl_household_relation`
  ADD PRIMARY KEY (`thr_id`),
  ADD KEY `thr_head_id` (`thr_head_id`);

--
-- Indexes for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_residentcop`
--
ALTER TABLE `tbl_residentcop`
  ADD PRIMARY KEY (`resident_id`),
  ADD KEY `tbl_resident_ibfk_1` (`user_id`);

--
-- Indexes for table `tbl_residents`
--
ALTER TABLE `tbl_residents`
  ADD PRIMARY KEY (`res_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_user_cop`
--
ALTER TABLE `tbl_user_cop`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `web_officials`
--
ALTER TABLE `web_officials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `web_services`
--
ALTER TABLE `web_services`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_audit`
--
ALTER TABLE `tbl_audit`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1192;

--
-- AUTO_INCREMENT for table `tbl_banned_users`
--
ALTER TABLE `tbl_banned_users`
  MODIFY `ban_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_bid`
--
ALTER TABLE `tbl_bid`
  MODIFY `BID_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_blotter`
--
ALTER TABLE `tbl_blotter`
  MODIFY `blotter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_brgyofficer`
--
ALTER TABLE `tbl_brgyofficer`
  MODIFY `brgyOfficer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tbl_certification`
--
ALTER TABLE `tbl_certification`
  MODIFY `certification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `tbl_clearance`
--
ALTER TABLE `tbl_clearance`
  MODIFY `clearance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `tbl_compgriev`
--
ALTER TABLE `tbl_compgriev`
  MODIFY `complaint_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbl_event`
--
ALTER TABLE `tbl_event`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_event_comments`
--
ALTER TABLE `tbl_event_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_generated_documents`
--
ALTER TABLE `tbl_generated_documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_household_head`
--
ALTER TABLE `tbl_household_head`
  MODIFY `household_head_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_household_relation`
--
ALTER TABLE `tbl_household_relation`
  MODIFY `thr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=322;

--
-- AUTO_INCREMENT for table `tbl_residentcop`
--
ALTER TABLE `tbl_residentcop`
  MODIFY `resident_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_residents`
--
ALTER TABLE `tbl_residents`
  MODIFY `res_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `tbl_user_cop`
--
ALTER TABLE `tbl_user_cop`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `web_officials`
--
ALTER TABLE `web_officials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `web_services`
--
ALTER TABLE `web_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_audit`
--
ALTER TABLE `tbl_audit`
  ADD CONSTRAINT `tbl_audit_ibfk_2` FOREIGN KEY (`brgyOfficer_id`) REFERENCES `tbl_brgyofficer` (`brgyOfficer_id`);

--
-- Constraints for table `tbl_event_comments`
--
ALTER TABLE `tbl_event_comments`
  ADD CONSTRAINT `tbl_event_comments_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `tbl_event` (`event_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_generated_documents`
--
ALTER TABLE `tbl_generated_documents`
  ADD CONSTRAINT `tbl_generated_documents_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `tbl_residentcop` (`resident_id`),
  ADD CONSTRAINT `tbl_generated_documents_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tbl_user_cop` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
