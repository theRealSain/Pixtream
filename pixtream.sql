-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2024 at 03:15 PM
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
-- Database: `pixtream`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` bigint(20) NOT NULL,
  `username` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'admin', 'admin@example.com', 'admin', '2024-10-31 07:37:06');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) NOT NULL,
  `category_name` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `comments` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `post_id`, `comment`, `created_at`) VALUES
(50, 3, 39, '😍😍😍', '2024-11-09 11:15:57'),
(52, 4, 39, '❤️❤️', '2024-11-09 11:24:08'),
(55, 4, 40, '😍❤️', '2024-11-09 11:30:46'),
(56, 4, 40, 'oww', '2024-11-09 11:32:18'),
(57, 4, 22, 'machan😍', '2024-11-09 11:42:56'),
(58, 4, 34, '😍', '2024-11-09 11:43:18'),
(59, 4, 34, '❤️❤️❤️', '2024-11-09 11:47:15'),
(60, 4, 37, '😻', '2024-11-09 11:47:50');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `complaint_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `follows` (
  `id` bigint(20) NOT NULL,
  `follower_id` bigint(20) DEFAULT NULL,
  `followed_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `likes` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `post_id`, `created_at`) VALUES
(36, 4, 39, '2024-11-09 14:59:25'),
(89, 1, 39, '2024-11-10 13:20:20'),
(90, 1, 40, '2024-11-10 13:23:03'),
(91, 1, 37, '2024-11-10 13:28:33'),
(92, 1, 2, '2024-11-10 13:29:56'),
(93, 1, 38, '2024-11-10 13:33:05');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) NOT NULL,
  `sender_id` bigint(20) DEFAULT NULL,
  `receiver_id` bigint(20) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `options` (
  `id` bigint(20) NOT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  `option_name` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `posts` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `post_path` varchar(255) DEFAULT NULL,
  `caption` longtext DEFAULT NULL,
  `category` enum('photos','videos','stories','music','articles','memes','art & Design','Photography','Travel & Adventure','Food & Cooking','Others') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `post_path`, `caption`, `category`, `created_at`) VALUES
(1, 1, 'post_uploads/post_6723587f6800d.png', 'Friends😍', 'photos', '2024-10-31 10:14:23'),
(2, 1, 'post_uploads/post_67235b9856e10.jpg', 'ME❤️', 'photos', '2024-10-31 10:27:36'),
(20, 1, 'post_uploads/post_67236660015c8.jpg', '😂😂', 'memes', '2024-10-31 11:13:36'),
(22, 1, 'post_uploads/post_672366ce2921f.png', 'Hello all😍😍😍', 'memes', '2024-10-31 11:15:26'),
(34, 1, 'post_uploads/post_6726f56224b23.mp4', '', 'music', '2024-11-03 04:00:34'),
(35, 1, 'post_uploads/post_6726f5788fab3.mp4', '', 'music', '2024-11-03 04:00:56'),
(36, 1, 'post_uploads/post_6726f588be890.mp4', 'hai', 'Others', '2024-11-03 04:01:12'),
(37, 1, 'post_uploads/post_67275266906d8.jpg', 'Me😌', 'photos', '2024-11-03 10:37:26'),
(38, 1, 'post_uploads/post_6727529b1c44e.jpg', '', 'Others', '2024-11-03 10:38:19'),
(39, 2, 'post_uploads/post_672772d6dd667.jpg', 'Angamaly😍', 'Travel & Adventure', '2024-11-03 12:55:50'),
(40, 2, 'post_uploads/post_6727739989b44.mp4', '💕', 'Others', '2024-11-03 12:59:05');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) NOT NULL,
  `reported_by` bigint(20) DEFAULT NULL,
  `reported_user` bigint(20) DEFAULT NULL,
  `approval` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saved_posts`
--

CREATE TABLE `saved_posts` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saved_posts`
--

INSERT INTO `saved_posts` (`id`, `user_id`, `post_id`, `created_at`) VALUES
(14, 1, 39, '2024-11-10 14:07:46'),
(15, 1, 22, '2024-11-10 14:08:04');

-- --------------------------------------------------------

--
-- Table structure for table `shares`
--

CREATE TABLE `shares` (
  `id` bigint(20) NOT NULL,
  `from_user_id` bigint(20) DEFAULT NULL,
  `to_user_id` bigint(20) DEFAULT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT 'default.png',
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `user_blocks` (
  `id` bigint(20) NOT NULL,
  `blocked_by` bigint(20) DEFAULT NULL,
  `blocked_user` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_selections`
--

CREATE TABLE `user_selections` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `option_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `follower_id` (`follower_id`),
  ADD KEY `followed_id` (`followed_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reported_by` (`reported_by`),
  ADD KEY `reported_user` (`reported_user`);

--
-- Indexes for table `saved_posts`
--
ALTER TABLE `saved_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `shares`
--
ALTER TABLE `shares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from_user_id` (`from_user_id`),
  ADD KEY `to_user_id` (`to_user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_blocks`
--
ALTER TABLE `user_blocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blocked_by` (`blocked_by`),
  ADD KEY `blocked_user` (`blocked_user`);

--
-- Indexes for table `user_selections`
--
ALTER TABLE `user_selections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `option_id` (`option_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `saved_posts`
--
ALTER TABLE `saved_posts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `shares`
--
ALTER TABLE `shares`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_blocks`
--
ALTER TABLE `user_blocks`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_selections`
--
ALTER TABLE `user_selections`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `follows_ibfk_2` FOREIGN KEY (`followed_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`);

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
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`reported_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `saved_posts`
--
ALTER TABLE `saved_posts`
  ADD CONSTRAINT `saved_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `saved_posts_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `shares`
--
ALTER TABLE `shares`
  ADD CONSTRAINT `shares_ibfk_1` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `shares_ibfk_2` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `shares_ibfk_3` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `user_blocks`
--
ALTER TABLE `user_blocks`
  ADD CONSTRAINT `user_blocks_ibfk_1` FOREIGN KEY (`blocked_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_blocks_ibfk_2` FOREIGN KEY (`blocked_user`) REFERENCES `users` (`id`);

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
