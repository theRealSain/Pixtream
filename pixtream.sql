-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 12, 2024 at 12:05 AM
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
  `follower` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `following` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `follower` (`follower`),
  KEY `following` (`following`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`id`, `follower`, `following`, `created_at`) VALUES
(34, 'jack', 'fmiller', '2024-08-11 22:55:00'),
(35, 'jack', 'hmoore', '2024-08-11 22:55:01'),
(44, 'jack', 'asmith', '2024-08-11 23:29:29'),
(45, 'jack', 'bjohnson', '2024-08-11 23:29:30'),
(46, 'jack', 'cdavis', '2024-08-11 23:29:30'),
(47, 'jack', 'dbrown', '2024-08-11 23:29:30'),
(48, 'asmith', 'jack', '2024-08-11 23:30:05');

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

DROP TABLE IF EXISTS `photos`;
CREATE TABLE IF NOT EXISTS `photos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `caption` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_photos_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'default.png',
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
