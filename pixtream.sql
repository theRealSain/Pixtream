-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 08, 2024 at 11:51 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pixtream`
--

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

DROP TABLE IF EXISTS `follows`;
CREATE TABLE IF NOT EXISTS `follows` (
  `id` int NOT NULL AUTO_INCREMENT,
  `follower` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `following` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `follower` (`follower`),
  KEY `following` (`following`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`id`, `follower`, `following`, `created_at`) VALUES
(48, 'asmith', 'jack', '2024-08-11 23:30:05'),
(50, 'jain_panengadan', 'jack', '2024-08-18 22:44:47'),
(56, 'jack', 'asmith', '2024-09-01 23:16:37'),
(57, 'jack', 'bjohnson', '2024-09-01 23:16:39'),
(58, 'jack', 'jain_panengadan', '2024-09-01 23:16:43'),
(59, 'asmith', 'bjohnson', '2024-09-08 22:54:17'),
(60, 'asmith', 'cdavis', '2024-09-08 22:54:17'),
(61, 'asmith', 'dbrown', '2024-09-08 22:54:18'),
(62, 'asmith', 'ewilliams', '2024-09-08 22:54:18'),
(63, 'asmith', 'fmiller', '2024-09-08 22:54:19'),
(64, 'jack', 'itaylor', '2024-09-08 23:17:03'),
(65, 'jack', 'hmoore', '2024-09-08 23:17:04');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender` varchar(255) NOT NULL,
  `receiver` varchar(255) NOT NULL,
  `message` longtext,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender`, `receiver`, `message`, `timestamp`) VALUES
(1, 'jack', 'asmith', 'adsasdsad', '2024-08-13 23:05:51'),
(2, 'jack', 'asmith', 'ASdasd', '2024-08-13 23:05:56'),
(3, 'asmith', 'jack', 'sdfsddfs', '2024-08-13 23:06:22'),
(6, 'jack', 'bjohnson', 'Hello', '2024-08-13 23:21:45'),
(10, 'jack', 'asmith', 'sad', '2024-08-13 23:40:27'),
(11, 'jack', 'asmith', 'asdasd', '2024-08-13 23:56:53'),
(12, 'jack', 'asmith', 'sain', '2024-08-18 22:49:10');

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

DROP TABLE IF EXISTS `photos`;
CREATE TABLE IF NOT EXISTS `photos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `photo_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `caption` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_photos_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `username`, `photo_path`, `caption`, `created_at`) VALUES
(19, 'jack', '4b5abc7e6eaa2e622b5b5ba745b0c464.png', 'New', '2024-08-20 23:20:20'),
(20, 'jack', 'a4a43e08e8f40bfc8f55841ae41ff035.png', 'logo', '2024-08-20 23:20:29'),
(21, 'jack', '64c7027746a48aab721d4c70de4982a7.jpg', 'tree', '2024-08-20 23:21:03'),
(22, 'jack', '84114815124613cfb3dcdb3348ec4a69.webp', 'snake', '2024-08-20 23:21:31'),
(24, 'jack', '98d14ebb2eeb7e76a5d7533ead07a339.png', 'user', '2024-08-20 23:35:31'),
(25, 'asmith', '3937b9a1e649dd176aa8cf92a945b7fd.jpg', 'tree', '2024-09-08 22:36:47'),
(26, 'asmith', '7f40b9c1bdd7bc5d3b09763ba5c602e0.webp', 'frog', '2024-09-08 22:37:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'default.png',
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`name`, `username`, `email`, `password`, `created_at`, `profile_photo`) VALUES
('Alice Smith', 'asmith', 'alice.smith@example.com', 'dddd', '2024-08-11 22:54:28', 'default.png'),
('Bob Johnson', 'bjohnson', 'bob.johnson@example.com', 'dddd', '2024-08-11 22:54:28', 'default.png'),
('Carol Davis', 'cdavis', 'carol.davis@example.com', 'dddd', '2024-08-11 22:54:28', 'default.png'),
('David Brown', 'dbrown', 'david.brown@example.com', 'dddd', '2024-08-11 22:54:28', 'default.png'),
('Eve Williams', 'ewilliams', 'eve.williams@example.com', 'dddd', '2024-08-11 22:54:28', 'default.png'),
('Frank Miller', 'fmiller', 'frank.miller@example.com', 'dddd', '2024-08-11 22:54:28', 'default.png'),
('Grace Wilson', 'gwilson', 'grace.wilson@example.com', 'dddd', '2024-08-11 22:54:28', 'default.png'),
('Hank Moore', 'hmoore', 'hank.moore@example.com', 'dddd', '2024-08-11 22:54:28', 'default.png'),
('Ivy Taylor', 'itaylor', 'ivy.taylor@example.com', 'dddd', '2024-08-11 22:54:28', 'default.png'),
('Jack John', 'jack', 'jack@example.com', 'jjjj', '2024-08-11 22:47:34', 'jack.jpg'),
('Jain Roy', 'jain_panengadan', 'jain@example.com', 'jjjj', '2024-08-18 22:43:49', 'jain_panengadan.jpg'),
('Jack Anderson', 'janderson', 'jack.anderson@example.com', 'dddd', '2024-08-11 22:54:28', 'default.png');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `fk_follows_follower` FOREIGN KEY (`follower`) REFERENCES `users` (`username`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_follows_following` FOREIGN KEY (`following`) REFERENCES `users` (`username`) ON DELETE CASCADE;

--
-- Constraints for table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `fk_photos_username` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`username`) REFERENCES `users` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
