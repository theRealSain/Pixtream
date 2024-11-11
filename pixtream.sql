-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 11, 2024 at 10:11 AM
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
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `username` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'admin', 'admin@example.com', 'admin', '2024-10-31 07:37:06');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `category_name` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(7, 'art & Design'),
(5, 'articles'),
(10, 'Food & Cooking'),
(6, 'memes'),
(4, 'music'),
(11, 'Others'),
(8, 'Photography'),
(1, 'photos'),
(3, 'stories'),
(9, 'Travel & Adventure'),
(2, 'videos');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `post_id` bigint DEFAULT NULL,
  `comment` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

DROP TABLE IF EXISTS `complaints`;
CREATE TABLE IF NOT EXISTS `complaints` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `complaint_text` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `user_id`, `complaint_text`, `created_at`) VALUES
(1, 1, 'Spam or Scams', '2024-11-03 12:26:04'),
(2, 1, 'Data Protection Request', '2024-11-03 12:26:30');

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

DROP TABLE IF EXISTS `follows`;
CREATE TABLE IF NOT EXISTS `follows` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `follower_id` bigint DEFAULT NULL,
  `followed_id` bigint DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `follower_id` (`follower_id`),
  KEY `followed_id` (`followed_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`id`, `follower_id`, `followed_id`, `created_at`) VALUES
(1, 2, 1, '2024-11-03 12:31:37'),
(2, 1, 2, '2024-11-03 12:31:52');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `post_id` bigint DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `post_id`, `created_at`) VALUES
(95, 1, 41, '2024-11-11 09:38:04');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `sender_id` bigint DEFAULT NULL,
  `receiver_id` bigint DEFAULT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `created_at`) VALUES
(4, 1, 2, 'daa', '2024-11-09 04:53:00'),
(6, 2, 1, 'haa', '2024-11-09 05:10:49'),
(7, 1, 3, 'Hii man', '2024-11-09 05:12:03'),
(8, 3, 1, 'Hello da...', '2024-11-09 05:30:12'),
(9, 1, 2, 'Hii', '2024-11-09 05:30:40'),
(10, 1, 3, 'Enthokkeyund vishesham...😌', '2024-11-09 05:31:03'),
(11, 3, 1, 'Sughayitt irikkunu...😊', '2024-11-09 05:31:54'),
(28, 1, 4, 'Hello abi...😂', '2024-11-09 07:12:07'),
(29, 4, 3, 'Daa', '2024-11-09 07:12:37'),
(30, 4, 1, 'aada', '2024-11-09 07:12:44'),
(31, 4, 1, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat aspernatur vitae praesentium totam eveniet provident ullam at ratione esse accusantium. Sapiente repellendus asperiores nihil modi est repellat quia possimus incidunt.Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat aspernatur vitae praesentium totam eveniet provident ullam at ratione esse accusantium. Sapiente repellendus asperiores nihil modi est repellat quia possimus incidunt.Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat aspernatur vitae praesentium totam eveniet provident ullam at ratione esse accusantium. Sapiente repellendus asperiores nihil modi est repellat quia possimus incidunt.Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat aspernatur vitae praesentium totam eveniet provident ullam at ratione esse accusantium. Sapiente repellendus asperiores nihil modi est repellat quia possimus incidunt.Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat aspernatur vitae praesentium totam eveniet provident ullam at ratione esse accusantium. Sapiente repellendus asperiores nihil modi est repellat quia possimus incidunt.Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat aspernatur vitae praesentium totam eveniet provident ullam at ratione esse accusantium. Sapiente repellendus asperiores nihil modi est repellat quia possimus incidunt.', '2024-11-09 07:16:57'),
(32, 1, 4, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat aspernatur vitae praesentium totam eveniet provident ullam at ratione esse accusantium. Sapiente repellendus asperiores nihil modi est repellat quia possimus incidunt.Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat aspernatur vitae praesentium totam eveniet provident ullam at ratione esse accusantium. Sapiente repellendus asperiores nihil modi est repellat quia possimus incidunt.Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat aspernatur vitae praesentium totam eveniet provident ullam at ratione esse accusantium. Sapiente repellendus asperiores nihil modi est repellat quia possimus incidunt.Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat aspernatur vitae praesentium totam eveniet provident ullam at ratione esse accusantium. Sapiente repellendus asperiores nihil modi est repellat quia possimus incidunt.Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat aspernatur vitae praesentium totam eveniet provident ullam at ratione esse accusantium. Sapiente repellendus asperiores nihil modi est repellat quia possimus incidunt.Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat aspernatur vitae praesentium totam eveniet provident ullam at ratione esse accusantium. Sapiente repellendus asperiores nihil modi est repellat quia possimus incidunt.Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat aspernatur vitae praesentium totam eveniet provident ullam at ratione esse accusantium. Sapiente repellendus asperiores nihil modi est repellat quia possimus incidunt.Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat aspernatur vitae praesentium totam eveniet provident ullam at ratione esse accusantium. Sapiente repellendus asperiores nihil modi est repellat quia possimus incidunt.Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat aspernatur vitae praesentium totam eveniet provident ullam at ratione esse accusantium. Sapiente repellendus asperiores nihil modi est repellat quia possimus incidunt.Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat aspernatur vitae praesentium totam eveniet provident ullam at ratione esse accusantium. Sapiente repellendus asperiores nihil modi est repellat quia possimus incidunt.', '2024-11-09 07:17:16');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `category_id` bigint DEFAULT NULL,
  `option_name` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `category_id`, `option_name`) VALUES
(1, 1, 'Portraits'),
(2, 1, 'Landscapes'),
(3, 1, 'Wildlife'),
(4, 1, 'Street Photography'),
(5, 2, 'Documentaries'),
(6, 2, 'Short Films'),
(7, 2, 'Vlogs'),
(8, 2, 'Tutorials'),
(9, 3, 'Travel Stories'),
(10, 3, 'Personal Journeys'),
(11, 3, 'Historical Narratives'),
(12, 4, 'Instrumental'),
(13, 4, 'Cover Songs'),
(14, 4, 'Original Compositions'),
(15, 5, 'Technology'),
(16, 5, 'Lifestyle'),
(17, 5, 'Health & Wellness'),
(18, 6, 'Relatable Memes'),
(19, 6, 'Dark Humor'),
(20, 6, 'Wholesome Memes'),
(21, 7, 'Digital Art'),
(22, 7, 'Sketching'),
(23, 7, 'Painting'),
(24, 8, 'Nature'),
(25, 8, 'Urban Photography'),
(26, 8, 'Abstract'),
(27, 9, 'Backpacking'),
(28, 9, 'Road Trips'),
(29, 9, 'Nature Exploration'),
(30, 10, 'Recipes'),
(31, 10, 'Food Reviews'),
(32, 10, 'Culinary Tips'),
(33, 11, 'Miscellaneous'),
(34, 11, 'Random Thoughts');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `post_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `caption` longtext COLLATE utf8mb4_general_ci,
  `category` enum('photos','videos','stories','music','articles','memes','art & Design','Photography','Travel & Adventure','Food & Cooking','Others') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `post_path`, `caption`, `category`, `created_at`) VALUES
(41, 2, 'post_uploads/post_6731d04a159d7.jpg', 'Nature❤', 'Photography', '2024-11-11 09:37:14');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
CREATE TABLE IF NOT EXISTS `reports` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `reported_by` bigint DEFAULT NULL,
  `reported_user` bigint DEFAULT NULL,
  `approval` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reported_by` (`reported_by`),
  KEY `reported_user` (`reported_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saved_posts`
--

DROP TABLE IF EXISTS `saved_posts`;
CREATE TABLE IF NOT EXISTS `saved_posts` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `post_id` bigint DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saved_posts`
--

INSERT INTO `saved_posts` (`id`, `user_id`, `post_id`, `created_at`) VALUES
(17, 1, 41, '2024-11-11 09:38:05');

-- --------------------------------------------------------

--
-- Table structure for table `shares`
--

DROP TABLE IF EXISTS `shares`;
CREATE TABLE IF NOT EXISTS `shares` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `from_user_id` bigint DEFAULT NULL,
  `to_user_id` bigint DEFAULT NULL,
  `post_id` bigint DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `from_user_id` (`from_user_id`),
  KEY `to_user_id` (`to_user_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_general_ci,
  `profile_picture` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'default.png',
  `location` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `bio`, `profile_picture`, `location`, `created_at`) VALUES
(1, 'Sain Saburaj', 'sain', 'sain@example.com', 'ssss', 'Hello ALL😍', 'sain.jpg', 'Thrissur', '2024-10-31 07:38:11'),
(2, 'Abin Saburaj', 'abin', 'abin@example.com', 'aaaa', 'Hello', 'abin.jpg', 'Thrissur', '2024-11-03 12:30:45'),
(3, 'Jain Roy P', 'jain_panengadan', 'jain@example.com', 'jjjj', 'Hey😍', 'jain_panengadan.png', 'Thrissur', '2024-11-09 03:27:55'),
(4, 'Abijith P', 'abijith', 'abi@example.com', 'aaaa', 'Hey all💕', 'abijith.png', 'Palakkad', '2024-11-09 05:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `user_blocks`
--

DROP TABLE IF EXISTS `user_blocks`;
CREATE TABLE IF NOT EXISTS `user_blocks` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `blocked_by` bigint DEFAULT NULL,
  `blocked_user` bigint DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `blocked_by` (`blocked_by`),
  KEY `blocked_user` (`blocked_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_selections`
--

DROP TABLE IF EXISTS `user_selections`;
CREATE TABLE IF NOT EXISTS `user_selections` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `option_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `option_id` (`option_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_selections`
--

INSERT INTO `user_selections` (`id`, `user_id`, `option_id`) VALUES
(1, 1, 21),
(2, 1, 22),
(3, 1, 15),
(4, 1, 18),
(5, 1, 20),
(6, 1, 13),
(7, 1, 12),
(8, 1, 14),
(9, 1, 24),
(10, 1, 2),
(11, 1, 1),
(12, 1, 27),
(13, 2, 16),
(14, 2, 20),
(15, 2, 14),
(16, 2, 33),
(17, 2, 26),
(18, 2, 24),
(19, 2, 2),
(20, 2, 28),
(21, 3, 16),
(22, 3, 15),
(23, 3, 19),
(24, 3, 18),
(25, 3, 13),
(26, 3, 12),
(27, 3, 1),
(28, 3, 29),
(29, 3, 8),
(30, 4, 16),
(31, 4, 31),
(32, 4, 20),
(33, 4, 24),
(34, 4, 2),
(35, 4, 9),
(36, 4, 27),
(37, 4, 29),
(38, 4, 5),
(39, 4, 6);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_fk_post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_fk_followed_id` FOREIGN KEY (`followed_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `follows_fk_follower_id` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_fk_post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_fk_receiver_id` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_fk_sender_id` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_fk_reported_by` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_fk_reported_user` FOREIGN KEY (`reported_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `saved_posts`
--
ALTER TABLE `saved_posts`
  ADD CONSTRAINT `saved_posts_fk_post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `saved_posts_fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shares`
--
ALTER TABLE `shares`
  ADD CONSTRAINT `shares_fk_from_user_id` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shares_fk_post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shares_fk_to_user_id` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_blocks`
--
ALTER TABLE `user_blocks`
  ADD CONSTRAINT `user_blocks_fk_blocked_by` FOREIGN KEY (`blocked_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_blocks_fk_blocked_user` FOREIGN KEY (`blocked_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_selections`
--
ALTER TABLE `user_selections`
  ADD CONSTRAINT `user_selections_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_selections_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
