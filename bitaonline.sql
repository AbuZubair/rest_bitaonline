-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.36-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             10.3.0.5771
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for bitaonline
CREATE DATABASE IF NOT EXISTS `bitaonline` /*!40100 DEFAULT CHARACTER SET latin7 */;
USE `bitaonline`;

-- Dumping structure for table bitaonline.app_program
CREATE TABLE IF NOT EXISTS `app_program` (
  `program_id` int(11) NOT NULL AUTO_INCREMENT,
  `program_name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `program_parent_id` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `link` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `level_program` int(11) DEFAULT NULL,
  `counter` int(11) NOT NULL,
  `is_active` enum('Y','N') COLLATE latin1_general_ci DEFAULT 'Y',
  `icon` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`program_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table bitaonline.app_program: ~9 rows (approximately)
DELETE FROM `app_program`;
/*!40000 ALTER TABLE `app_program` DISABLE KEYS */;
INSERT INTO `app_program` (`program_id`, `program_name`, `program_parent_id`, `link`, `level_program`, `counter`, `is_active`, `icon`) VALUES
	(1, 'Beranda', NULL, 'beranda', 1, 1, 'Y', '<ion-icon name="home-outline"></ion-icon>'),
	(2, 'Akademik', NULL, 'akademik', 1, 2, 'Y', '<ion-icon name="school-outline"></ion-icon>'),
	(3, 'User', NULL, 'user', 1, 3, 'Y', '<ion-icon name="person-circle-outline"></ion-icon>'),
	(4, 'Setting', NULL, 'setting', 1, 4, 'Y', '<ion-icon name="settings-outline"></ion-icon>'),
	(5, 'Pengajuan Judul', NULL, 'judul', 1, 2, 'Y', '<ion-icon name="reader-outline"></ion-icon>'),
	(6, 'Persetujuan Judul', NULL, 'approvaljudul', 1, 2, 'Y', '<ion-icon name="checkmark-circle-outline"></ion-icon>'),
	(7, 'Pengajuan Jadwal Bimbingan', '', 'bimbingan', 1, 3, 'Y', '<ion-icon name="checkmark-circle-outline"></ion-icon>'),
	(8, 'Persetujuan Jadwal Bimbingan', '', 'approvalbimbingan', 1, 3, 'Y', '<ion-icon name="checkmark-circle-outline"></ion-icon>'),
	(9, 'Upload File', '', 'upload', 1, 4, 'Y', '<ion-icon name="push-outline"></ion-icon>');
/*!40000 ALTER TABLE `app_program` ENABLE KEYS */;

-- Dumping structure for table bitaonline.chat
CREATE TABLE IF NOT EXISTS `chat` (
  `chat_id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_sender_id` int(11) DEFAULT '0',
  `chat_receiver_id` int(11) DEFAULT '0',
  `chat_content` text NOT NULL,
  `created_date` timestamp NULL DEFAULT NULL,
  `is_read` enum('Y','N') DEFAULT 'N',
  `room_id` int(11) NOT NULL,
  PRIMARY KEY (`chat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bitaonline.chat: ~8 rows (approximately)
DELETE FROM `chat`;
/*!40000 ALTER TABLE `chat` DISABLE KEYS */;
INSERT INTO `chat` (`chat_id`, `chat_sender_id`, `chat_receiver_id`, `chat_content`, `created_date`, `is_read`, `room_id`) VALUES
	(119, 14, 16, 'Hi', '2020-04-22 09:52:38', 'Y', 37),
	(120, 16, 14, 'Hai', '2020-04-22 09:52:38', 'Y', 37),
	(121, 14, 16, 'Apa kabar', '2020-04-23 00:00:14', 'Y', 37),
	(122, 14, 16, 'Baik saja', '2020-04-23 00:01:28', 'Y', 37),
	(123, 16, 14, 'Oke lah', '2020-04-23 00:02:28', 'Y', 37),
	(124, 16, 17, 'Halo', '2020-04-23 19:47:24', 'N', 38),
	(125, 16, 14, 'poyik', '2020-04-23 20:22:55', 'Y', 37),
	(126, 17, 16, 'Halo', '2020-04-23 19:47:24', 'Y', 38);
/*!40000 ALTER TABLE `chat` ENABLE KEYS */;

-- Dumping structure for table bitaonline.files
CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul_id` int(11) NOT NULL,
  `filename` varchar(2000) NOT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table bitaonline.files: ~2 rows (approximately)
DELETE FROM `files`;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
INSERT INTO `files` (`id`, `judul_id`, `filename`, `created_date`) VALUES
	(1, 4, 'Proposal Bita Online Polibatam_ ATIGA tim.pdf', '2020-04-24 13:35:18'),
	(2, 4, 'ca8683c0-3597-415d-840f-cfb17176e957.pdf', '2020-04-24 19:22:09');
/*!40000 ALTER TABLE `files` ENABLE KEYS */;

-- Dumping structure for table bitaonline.jadwal_bimbingan
CREATE TABLE IF NOT EXISTS `jadwal_bimbingan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dospem` int(11) NOT NULL,
  `mahasiswa` int(11) NOT NULL,
  `jadwal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table bitaonline.jadwal_bimbingan: ~8 rows (approximately)
DELETE FROM `jadwal_bimbingan`;
/*!40000 ALTER TABLE `jadwal_bimbingan` DISABLE KEYS */;
INSERT INTO `jadwal_bimbingan` (`id`, `dospem`, `mahasiswa`, `jadwal`, `status`, `type`, `created_date`, `updated_date`) VALUES
	(1, 16, 14, '2020-04-23 17:39:18', 2, 'bimbingan', '2020-04-23 17:39:18', '2020-04-23 17:39:18'),
	(8, 16, 14, '2020-04-23 17:56:32', 2, 'bimbingan', '2020-04-23 17:56:32', '2020-04-23 17:56:32'),
	(9, 16, 14, '2020-04-23 17:47:54', 2, 'bimbingan', '2020-04-23 17:47:54', '2020-04-23 17:47:54'),
	(10, 16, 14, '2020-04-23 17:42:52', 2, 'bimbingan', '2020-04-23 17:42:52', '2020-04-23 17:42:52'),
	(12, 16, 17, '2020-04-23 19:10:35', 1, 'bimbingan', '2020-04-23 19:10:35', '2020-04-23 19:10:35'),
	(13, 16, 17, '2020-04-23 17:43:07', 1, 'bimbingan', '2020-04-23 17:43:07', '2020-04-23 17:43:07'),
	(14, 16, 17, '2020-04-23 17:55:50', 2, 'bimbingan', '2020-04-23 17:55:50', '2020-04-23 17:55:50'),
	(15, 16, 17, '2020-04-25 21:54:43', 1, 'bimbingan', '2020-04-25 21:54:43', '2020-04-25 21:54:43');
/*!40000 ALTER TABLE `jadwal_bimbingan` ENABLE KEYS */;

-- Dumping structure for table bitaonline.judul
CREATE TABLE IF NOT EXISTS `judul` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `judul` varchar(2000) NOT NULL,
  `deskripsi` varchar(2000) NOT NULL,
  `dospem` int(11) NOT NULL,
  `dospem_string` varchar(500) NOT NULL,
  `approval` int(11) NOT NULL DEFAULT '0',
  `nilai_akhir` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table bitaonline.judul: ~2 rows (approximately)
DELETE FROM `judul`;
/*!40000 ALTER TABLE `judul` DISABLE KEYS */;
INSERT INTO `judul` (`id`, `user_id`, `judul`, `deskripsi`, `dospem`, `dospem_string`, `approval`, `nilai_akhir`, `created_date`, `updated_date`) VALUES
	(4, 14, 'Judul TA [Revisi]', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book', 16, 'Rahmat', 2, NULL, '2020-04-23 19:12:20', '2020-04-23 19:12:20'),
	(5, 17, 'Judul TA', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book', 16, 'Rahmat', 1, 0, '2020-04-25 13:04:16', '2020-04-25 09:56:31');
/*!40000 ALTER TABLE `judul` ENABLE KEYS */;

-- Dumping structure for table bitaonline.judul_detail
CREATE TABLE IF NOT EXISTS `judul_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul_id` int(11) NOT NULL,
  `komen` varchar(2000) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table bitaonline.judul_detail: ~3 rows (approximately)
DELETE FROM `judul_detail`;
/*!40000 ALTER TABLE `judul_detail` DISABLE KEYS */;
INSERT INTO `judul_detail` (`id`, `judul_id`, `komen`, `created_date`) VALUES
	(1, 4, 'Ini Bagus', '2020-04-21 15:25:02'),
	(4, 5, 'Bagus', '2020-04-23 12:29:08'),
	(5, 5, 'Kurang', '2020-04-23 12:30:40');
/*!40000 ALTER TABLE `judul_detail` ENABLE KEYS */;

-- Dumping structure for table bitaonline.keys
CREATE TABLE IF NOT EXISTS `keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `is_private_key` tinyint(1) NOT NULL DEFAULT '0',
  `ip_addresses` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bitaonline.keys: ~36 rows (approximately)
DELETE FROM `keys`;
/*!40000 ALTER TABLE `keys` DISABLE KEYS */;
INSERT INTO `keys` (`id`, `user_id`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`, `date_created`) VALUES
	(1, 1, 'ac7beb27bc26c3965cabf6ca08f77661e6657a60', 1, 0, 0, '%', '2020-04-20 18:18:52'),
	(5, 10, '5a55abb7beb355b2e23efd912d0e6ece7350800d', 1, 0, 0, '::1', '2020-04-19 13:37:46'),
	(6, 11, 'e6460e28239b03af105a5dc0b6d30857e24c47b3', 1, 0, 0, '::1', '2019-11-02 22:43:24'),
	(7, 12, '391ef029d1aeca97a864d2c5ce1c3641902c0b5d', 1, 0, 0, '::1', '2019-11-02 22:44:33'),
	(8, 13, '1be7bd6ce645c64c90a574f386684fd60f6252d8', 1, 0, 0, '::1', '2019-11-02 22:49:06'),
	(9, 14, '2fa112de2a97278341b15f7f0c34c1eebc4b3793', 1, 0, 0, '::1', '2020-04-25 10:54:24'),
	(10, 15, '8c2a5c7fd643f610fd5ac436af507e7f8b12224c', 1, 0, 0, '::1', '2020-04-21 12:25:13'),
	(11, 16, '765a66237362be04ad6f7662505037bf4ebe07f0', 1, 0, 0, '::1', '2020-04-25 09:09:40'),
	(12, 17, '13fb335d1680280ac1a7c4ecc093faaefc6b4680', 1, 0, 0, '::1', '2020-01-26 23:28:55'),
	(13, 18, '9549be50394e60ad04b47b9781ffc792f6a29f3c', 1, 0, 0, '::1', '2020-04-25 10:18:21'),
	(14, 19, 'b754ac3a41a50444b2c6fbc661bdcf7ea1489a15', 1, 0, 0, '::1', '2020-01-27 01:42:20'),
	(15, 21, '7a92b77184a663dc86166570eb03b9609137e568', 1, 0, 0, '::1', '2020-01-27 18:41:48'),
	(16, 22, 'df0b35d6fa9c70b6586876d8a8c00f8462d1e6f8', 1, 0, 0, '::1', '2020-01-27 18:44:05'),
	(17, 23, '23bcb0d0ab7a9051e255e2b3d04aa79fc9a97710', 1, 0, 0, '::1', '2020-01-27 18:52:29'),
	(18, 24, '373ede49cb214a27ecc57fda4ce160ef643c3734', 1, 0, 0, '::1', '2020-04-06 15:02:52'),
	(19, 25, '9db995cb3cc4646ebdc252a6e23bccf927f264a7', 1, 0, 0, '::1', '2020-04-06 15:05:20'),
	(20, 2, '168b494888b711662219e43c46078e7e6a6d5a11', 1, 0, 0, '::1', '2020-04-20 16:38:52'),
	(21, 3, '168b494888b711662219e43c46078e7e6a6d5a11', 1, 0, 0, '::1', '2020-04-20 16:38:53'),
	(22, 4, '684cefdb337ae7654c889608237447673478de24', 1, 0, 0, '::1', '2020-04-20 16:39:23'),
	(23, 5, 'f95bdb12d2988c770e0557b9bdf973928c52711d', 1, 0, 0, '::1', '2020-04-20 16:42:04'),
	(24, 6, 'f95bdb12d2988c770e0557b9bdf973928c52711d', 1, 0, 0, '::1', '2020-04-20 17:01:20'),
	(25, 7, 'f95bdb12d2988c770e0557b9bdf973928c52711d', 1, 0, 0, '::1', '2020-04-20 17:05:03'),
	(26, 8, 'f95bdb12d2988c770e0557b9bdf973928c52711d', 1, 0, 0, '::1', '2020-04-20 17:09:51'),
	(27, 9, 'f95bdb12d2988c770e0557b9bdf973928c52711d', 1, 0, 0, '::1', '2020-04-20 17:27:12'),
	(28, 10, 'f95bdb12d2988c770e0557b9bdf973928c52711d', 1, 0, 0, '::1', '2020-04-20 17:29:40'),
	(29, 11, 'f95bdb12d2988c770e0557b9bdf973928c52711d', 1, 0, 0, '::1', '2020-04-20 17:34:48'),
	(30, 12, 'f95bdb12d2988c770e0557b9bdf973928c52711d', 1, 0, 0, '::1', '2020-04-20 18:27:47'),
	(31, 13, '168b494888b711662219e43c46078e7e6a6d5a11', 1, 0, 0, '::1', '2020-04-20 18:40:20'),
	(32, 14, '2fa112de2a97278341b15f7f0c34c1eebc4b3793', 1, 0, 0, '::1', '2020-04-25 10:54:24'),
	(33, 17, '0d4e8b0db3b694e430cd7088f38bcbd75bd4f7b5', 1, 0, 0, '::1', '2020-04-21 12:32:49'),
	(34, 18, '9549be50394e60ad04b47b9781ffc792f6a29f3c', 1, 0, 0, '::1', '2020-04-25 10:18:21'),
	(35, 19, '7a8f8614b482d33239d9459e3259f690dbf2c318', 1, 0, 0, '::1', '2020-04-21 23:30:32'),
	(36, 1, '7b2dae37856ca262b9a119148982e79702429845', 1, 0, 0, '::1', '2020-04-22 10:13:03'),
	(37, 2, '76ee9c735905b81463f10fb5f43350ad131c42ef', 1, 0, 0, '::1', '2020-04-22 10:13:04'),
	(38, 3, 'eed29180323bb16c29b1331fd19c47dceb56f906', 1, 0, 0, '::1', '2020-04-22 10:15:24'),
	(39, 19, 'abc8c18620e2363c4064220fe67fc53f77cf31e1', 1, 0, 0, '192.168.1.4', '2020-04-25 12:03:28'),
	(40, 20, 'afcaed74aa94d912303a0e90d6cf07d7208035c1', 1, 0, 0, '192.168.1.4', '2020-04-25 12:15:07');
/*!40000 ALTER TABLE `keys` ENABLE KEYS */;

-- Dumping structure for table bitaonline.level_user
CREATE TABLE IF NOT EXISTS `level_user` (
  `level_id` int(11) NOT NULL AUTO_INCREMENT,
  `level_name` varchar(50) NOT NULL,
  `description` text,
  `is_active` enum('Y','N') DEFAULT 'Y',
  `is_deleted` enum('Y','N') DEFAULT 'N',
  `created_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  KEY `PRIMARY KEY` (`level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table bitaonline.level_user: ~3 rows (approximately)
DELETE FROM `level_user`;
/*!40000 ALTER TABLE `level_user` DISABLE KEYS */;
INSERT INTO `level_user` (`level_id`, `level_name`, `description`, `is_active`, `is_deleted`, `created_date`, `created_by`, `updated_date`, `updated_by`) VALUES
	(1, 'admin', 'Admin', 'Y', 'N', NULL, NULL, '2019-07-16 05:24:32', '{"username":"albatsiq","nama":"Administrator"}'),
	(2, 'mahasiswa', 'Mahasiswa', 'Y', 'N', NULL, NULL, '2019-07-16 05:24:32', '{"username":"albatsiq","nama":"Administrator"}'),
	(3, 'dosen', 'Dosen', 'Y', 'N', NULL, NULL, '2019-07-16 05:24:32', '{"username":"albatsiq","nama":"Administrator"}');
/*!40000 ALTER TABLE `level_user` ENABLE KEYS */;

-- Dumping structure for table bitaonline.notification
CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(500) NOT NULL,
  `msg` text NOT NULL,
  `jadwal_id` int(11) DEFAULT NULL,
  `is_read` enum('Y','N') DEFAULT 'N',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table bitaonline.notification: ~8 rows (approximately)
DELETE FROM `notification`;
/*!40000 ALTER TABLE `notification` DISABLE KEYS */;
INSERT INTO `notification` (`id`, `user_id`, `type`, `msg`, `jadwal_id`, `is_read`, `created_date`) VALUES
	(4, 14, 'reminder', 'Bimbingan akan dilakukan pada Thursday, April 23 20 12:00:00', 10, 'Y', '2020-04-22 17:51:53'),
	(5, 14, 'reminder', 'Bimbingan akan dilakukan pada Friday, April 24 20 12:36:55', 8, 'Y', '2020-04-22 17:51:53'),
	(6, 14, 'reminder', 'Bimbingan akan dilakukan pada Saturday, April 25 20 12:36:55', 9, 'Y', '2020-04-22 17:51:53'),
	(7, 16, 'reminder', 'Bimbingan akan dilakukan pada Saturday, April 25 20 09:46:00', 12, 'Y', '2020-04-23 17:23:29'),
	(8, 16, 'reminder', 'Bimbingan akan dilakukan pada Thursday, April 23 20 11:26:00', 14, 'Y', '2020-04-23 17:29:29'),
	(9, 16, 'reminder', 'Bimbingan akan dilakukan pada Thursday, April 23 20 11:26:00', 15, 'Y', '2020-04-23 17:29:29'),
	(10, 20, 'last_bimb', 'Sudah lebih dari satu bulan tidak ada bimbingan', NULL, 'N', '2020-04-25 12:16:03'),
	(11, 18, 'last_bimb', 'Sudah lebih dari satu bulan tidak ada bimbingan', NULL, 'N', '2020-04-25 21:46:36');
/*!40000 ALTER TABLE `notification` ENABLE KEYS */;

-- Dumping structure for table bitaonline.room_chat
CREATE TABLE IF NOT EXISTS `room_chat` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_participant1_id` int(11) DEFAULT '0',
  `room_participant2_id` int(11) DEFAULT '0',
  `is_deleted` enum('Y','N') DEFAULT 'N',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_date` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bitaonline.room_chat: ~1 rows (approximately)
DELETE FROM `room_chat`;
/*!40000 ALTER TABLE `room_chat` DISABLE KEYS */;
INSERT INTO `room_chat` (`room_id`, `room_participant1_id`, `room_participant2_id`, `is_deleted`, `created_date`, `updated_date`) VALUES
	(37, 14, 16, 'N', '2020-04-23 20:22:55', '2020-04-23 20:22:55'),
	(38, 16, 17, 'N', '2020-04-23 19:47:24', '2020-04-23 19:47:24');
/*!40000 ALTER TABLE `room_chat` ENABLE KEYS */;

-- Dumping structure for table bitaonline.token
CREATE TABLE IF NOT EXISTS `token` (
  `token` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `token_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table bitaonline.token: ~0 rows (approximately)
DELETE FROM `token`;
/*!40000 ALTER TABLE `token` DISABLE KEYS */;
/*!40000 ALTER TABLE `token` ENABLE KEYS */;

-- Dumping structure for table bitaonline.user
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `password` varchar(2000) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `phone_no` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `level_id` int(11) NOT NULL DEFAULT '3',
  `is_active` enum('Y','N') CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT 'N',
  `is_deleted` enum('Y','N') CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT 'N',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_date` timestamp NULL DEFAULT NULL,
  `last_logon` timestamp NULL DEFAULT NULL,
  `security_code` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table bitaonline.user: ~5 rows (approximately)
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`user_id`, `username`, `password`, `fullname`, `phone_no`, `level_id`, `is_active`, `is_deleted`, `created_date`, `updated_date`, `last_logon`, `security_code`) VALUES
	(14, 'andri', '$2a$08$O35Qk2GlKj/2iOAC3aEZf.V7SU96FH3L92SWb6lQ3Aeg1GKOwJAfW', 'Andri Thasfari', '', 2, 'Y', 'N', '2020-04-25 22:05:58', '2020-04-25 12:11:49', '2020-04-25 21:53:52', '7765'),
	(16, 'hamin', '$2a$08$O35Qk2GlKj/2iOAC3aEZf.V7SU96FH3L92SWb6lQ3Aeg1GKOwJAfW', 'Hamin', '', 3, 'Y', 'N', '2020-04-25 22:05:59', NULL, '2020-04-25 21:54:18', '7572'),
	(17, 'artika', '$2a$08$O35Qk2GlKj/2iOAC3aEZf.V7SU96FH3L92SWb6lQ3Aeg1GKOwJAfW', 'Artika P S', '', 2, 'Y', 'N', '2020-04-25 22:06:00', '2020-04-25 12:11:35', '2020-04-23 10:32:34', '7572'),
	(18, 'admin', '$2a$08$O35Qk2GlKj/2iOAC3aEZf.V7SU96FH3L92SWb6lQ3Aeg1GKOwJAfW', 'Admin', '', 1, 'Y', 'N', '2020-04-25 22:06:00', NULL, '2020-04-25 21:46:26', NULL),
	(20, 'anes', '$2a$08$8Zmno8uwy1tIqz9edXZQp.6f0KqRv7P5hUoEUHsz8xz9Y2qvN3tL6', 'Anes Yuliza', '', 2, 'Y', 'N', '2020-04-25 22:06:01', NULL, '2020-04-25 12:15:39', '1566');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

-- Dumping structure for table bitaonline.user_profile
CREATE TABLE IF NOT EXISTS `user_profile` (
  `fullname` varchar(50) DEFAULT NULL,
  `pob` varchar(50) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `path_photo` varchar(50) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `jurusan` varchar(500) NOT NULL,
  `angkatan` varchar(500) NOT NULL,
  `nim` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table bitaonline.user_profile: ~2 rows (approximately)
DELETE FROM `user_profile`;
/*!40000 ALTER TABLE `user_profile` DISABLE KEYS */;
INSERT INTO `user_profile` (`fullname`, `pob`, `dob`, `address`, `phone`, `gender`, `path_photo`, `created_date`, `updated_date`, `user_id`, `jurusan`, `angkatan`, `nim`) VALUES
	('Artika', 'Jakarta', '2020-04-19', 'Tes', '085646', 'P', 'ava_akhwat.jpg', NULL, '2020-04-19 23:11:04', 17, 'Ti', '2000', '1234567'),
	('Andri Thasfari', 'jkt', '2013-04-20', 'Jl..... terus aja ', '081223016413', 'L', 'ava_ikhwan.jpg', NULL, '2020-04-24 11:57:12', 14, 'Ti', '2000', '241412'),
	('Hamin', 'jkt', '1999-04-21', 'jln', '081223016410', 'L', '', NULL, '2020-04-21 12:26:54', 16, 'TI', '2000', '1213242'),
	('Admin', 'jkt', '2007-04-25', 'jkt', '081223016413', 'L', '', NULL, '2020-04-25 10:24:50', 18, '', '', '');
/*!40000 ALTER TABLE `user_profile` ENABLE KEYS */;

-- Dumping structure for table bitaonline.user_role
CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level_id` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `program_id` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `role` varchar(50) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Dumping data for table bitaonline.user_role: ~13 rows (approximately)
DELETE FROM `user_role`;
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` (`id`, `level_id`, `program_id`, `role`) VALUES
	(1, '1', '1', 'C,R,U,D'),
	(2, '1', '2', 'C,R,U,D'),
	(3, '1', '3', 'C,R,U,D'),
	(4, '1', '4', 'C,R,U,D'),
	(5, '2', '1', 'C,R,U,D'),
	(6, '2', '5', 'C,R,U,D'),
	(7, '2', '7', 'C,R,U,D'),
	(8, '2', '9', 'C,R,U,D'),
	(9, '2', '4', 'C,R,U,D'),
	(10, '3', '1', 'C,R,U,D'),
	(11, '3', '6', 'C,R,U,D'),
	(12, '3', '8', 'C,R,U,D'),
	(13, '3', '4', 'C,R,U,D');
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
