-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2024 at 05:51 AM
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
(258, 'Ella King', 'ella_king', 'ella.king@example.com', 'dddd', 'Travel enthusiast and writer', 'default.png', 'San Francisco, USA', '2024-12-07 06:51:38'),
(259, 'Lily Roberts', 'lily_roberts', 'lily.roberts@example.com', 'dddd', 'Book lover and aspiring author', 'default.png', 'New York, USA', '2024-12-07 06:51:38'),
(260, 'Ethan Walker', 'ethan_walker', 'ethan.walker@example.com', 'dddd', 'Guitarist and music lover', 'default.png', 'Toronto, Canada', '2024-12-07 06:51:38'),
(261, 'Chloe Adams', 'chloe_adams', 'chloe.adams@example.com', 'dddd', 'Art student and painter', 'default.png', 'London, UK', '2024-12-07 06:51:38'),
(262, 'Lucas Harris', 'lucas_harris', 'lucas.harris@example.com', 'dddd', 'Fitness enthusiast and gym lover', 'default.png', 'Los Angeles, USA', '2024-12-07 06:51:38'),
(263, 'Zoe Lee', 'zoe_lee', 'zoe.lee@example.com', 'dddd', 'Blogger and food critic', 'default.png', 'Paris, France', '2024-12-07 06:51:38'),
(264, 'Benjamin Clark', 'benjamin_clark', 'benjamin.clark@example.com', 'dddd', 'Music lover and aspiring DJ', 'default.png', 'Berlin, Germany', '2024-12-07 06:51:38'),
(265, 'Maya Wilson', 'maya_wilson', 'maya.wilson@example.com', 'dddd', 'Tech lover and software developer', 'default.png', 'San Francisco, USA', '2024-12-07 06:51:38'),
(266, 'Ryan Harris', 'ryan_harris', 'ryan.harris@example.com', 'dddd', 'Cyclist and adventure seeker', 'default.png', 'Toronto, Canada', '2024-12-07 06:51:38'),
(267, 'Olivia Miller', 'olivia_miller', 'olivia.miller@example.com', 'dddd', 'Photographer and travel enthusiast', 'default.png', 'Vancouver, Canada', '2024-12-07 06:51:38'),
(268, 'David Adams', 'david_adams', 'david.adams@example.com', 'dddd', 'Football player and coach', 'default.png', 'Miami, USA', '2024-12-07 06:51:38'),
(269, 'Harper Scott', 'harper_scott', 'harper.scott@example.com', 'dddd', 'Video game streamer and esports lover', 'default.png', 'Los Angeles, USA', '2024-12-07 06:51:38'),
(270, 'Sophia Evans', 'sophia_evans', 'sophia.evans@example.com', 'dddd', 'Dance lover and choreographer', 'default.png', 'Berlin, Germany', '2024-12-07 06:51:38'),
(271, 'Luke Harris', 'luke_harris', 'luke.harris@example.com', 'dddd', 'Environmental activist and hiker', 'default.png', 'Toronto, Canada', '2024-12-07 06:51:38'),
(272, 'Jack Brown', 'jack_brown', 'jack.brown@example.com', 'dddd', 'Photographer and tech enthusiast', 'default.png', 'Sydney, Australia', '2024-12-07 06:51:38'),
(273, 'Victoria Harris', 'victoria_harris', 'victoria.harris@example.com', 'dddd', 'Culinary artist and food lover', 'default.png', 'New York, USA', '2024-12-07 06:51:38'),
(274, 'Aiden Miller', 'aiden_miller', 'aiden.miller@example.com', 'dddd', 'Fitness coach and nutritionist', 'default.png', 'Miami, USA', '2024-12-07 06:51:38'),
(275, 'Emma Scott', 'emma_scott', 'emma.scott@example.com', 'dddd', 'Art curator and fashion lover', 'default.png', 'Paris, France', '2024-12-07 06:51:38'),
(276, 'David Lee', 'david_lee', 'david.lee@example.com', 'dddd', 'Digital marketing expert and entrepreneur', 'default.png', 'London, UK', '2024-12-07 06:51:38'),
(277, 'Benjamin Moore', 'benjamin_moore', 'benjamin.moore@example.com', 'dddd', 'Science enthusiast and tech lover', 'default.png', 'Toronto, Canada', '2024-12-07 06:51:38'),
(278, 'Samantha Scott', 'samantha_scott', 'samantha.scott@example.com', 'dddd', 'Yoga instructor and health coach', 'default.png', 'San Francisco, USA', '2024-12-07 06:51:38'),
(279, 'Oliver King', 'oliver_king', 'oliver.king@example.com', 'dddd', 'Social media expert and digital marketer', 'default.png', 'New York, USA', '2024-12-07 06:51:38'),
(280, 'Lily Miller', 'lily_miller', 'lily.miller@example.com', 'dddd', 'Blogger and photography enthusiast', 'default.png', 'Los Angeles, USA', '2024-12-07 06:51:38'),
(281, 'Zoe Garcia', 'zoe_garcia', 'zoe.garcia@example.com', 'dddd', 'Fashion designer and artist', 'default.png', 'Chicago, USA', '2024-12-07 06:51:38'),
(282, 'Mason Clark', 'mason_clark', 'mason.clark@example.com', 'dddd', 'Tech entrepreneur and innovator', 'default.png', 'Berlin, Germany', '2024-12-07 06:51:38'),
(283, 'Grace Moore', 'grace_moore', 'grace.moore@example.com', 'dddd', 'Chef and culinary enthusiast', 'default.png', 'Sydney, Australia', '2024-12-07 06:51:38'),
(284, 'Ethan Wilson', 'ethan_wilson', 'ethan.wilson@example.com', 'dddd', 'Outdoor enthusiast and photographer', 'default.png', 'Seattle, USA', '2024-12-07 06:51:38'),
(285, 'Isaac Scott', 'isaac_scott', 'isaac.scott@example.com', 'dddd', 'Digital creator and gamer', 'default.png', 'Toronto, Canada', '2024-12-07 06:51:38'),
(286, 'Emma King', 'emma_king', 'emma.king@example.com', 'dddd', 'Traveler and history buff', 'default.png', 'Paris, France', '2024-12-07 06:51:38'),
(287, 'Jackson Brown', 'jackson_brown', 'jackson.brown@example.com', 'dddd', 'Football coach and player', 'default.png', 'Vancouver, Canada', '2024-12-07 06:51:38'),
(288, 'Chloe Wilson', 'chloe_wilson', 'chloe.wilson@example.com', 'dddd', 'Yoga teacher and fitness enthusiast', 'default.png', 'London, UK', '2024-12-07 06:51:38'),
(289, 'Olivia Brown', 'olivia_brown', 'olivia.brown@example.com', 'dddd', 'Photographer and writer', 'default.png', 'Los Angeles, USA', '2024-12-07 06:51:38'),
(290, 'Luke Lee', 'luke_lee', 'luke.lee@example.com', 'dddd', 'Cyclist and environmental advocate', 'default.png', 'Berlin, Germany', '2024-12-07 06:51:38'),
(291, 'Megan Brown', 'megan_brown', 'megan.brown@example.com', 'dddd', 'Blogger and travel expert', 'default.png', 'San Francisco, USA', '2024-12-07 06:51:38'),
(292, 'Nathaniel Clark', 'nathaniel_clark', 'nathaniel.clark@example.com', 'dddd', 'Food enthusiast and chef', 'default.png', 'Rome, Italy', '2024-12-07 06:51:38'),
(293, 'Zara Harris', 'zara_harris', 'zara.harris@example.com', 'dddd', 'Writer and photographer', 'default.png', 'Chicago, USA', '2024-12-07 06:51:38'),
(294, 'William Lee', 'william_lee', 'william.lee@example.com', 'dddd', 'Entrepreneur and tech innovator', 'default.png', 'London, UK', '2024-12-07 06:51:38'),
(295, 'Ryan King', 'ryan_king', 'ryan.king@example.com', 'dddd', 'Music lover and guitarist', 'default.png', 'Toronto, Canada', '2024-12-07 06:51:38'),
(296, 'Sophie Harris', 'sophie_harris', 'sophie.harris@example.com', 'dddd', 'Fitness trainer and health coach', 'default.png', 'San Francisco, USA', '2024-12-07 06:51:38'),
(297, 'Aiden Walker', 'aiden_walker', 'aiden.walker@example.com', 'dddd', 'Photographer and digital artist', 'default.png', 'New York, USA', '2024-12-07 06:51:38'),
(298, 'Victoria King', 'victoria_king', 'victoria.king@example.com', 'dddd', 'Painter and art enthusiast', 'default.png', 'Los Angeles, USA', '2024-12-07 06:51:38'),
(299, 'Harper Miller', 'harper_miller', 'harper.miller@example.com', 'dddd', 'Gamer and video game developer', 'default.png', 'Toronto, Canada', '2024-12-07 06:51:38'),
(300, 'Sophia Brown', 'sophia_brown', 'sophia.brown@example.com', 'dddd', 'Writer and poet', 'default.png', 'Paris, France', '2024-12-07 06:51:38'),
(301, 'Jackson Davis', 'jackson_davis', 'jackson.davis@example.com', 'dddd', 'Tech lover and engineer', 'default.png', 'Chicago, USA', '2024-12-07 06:51:38'),
(302, 'Benjamin Walker', 'benjamin_walker', 'benjamin.walker@example.com', 'dddd', 'Chef and food stylist', 'default.png', 'San Francisco, USA', '2024-12-07 06:51:38'),
(303, 'Olivia Adams', 'olivia_adams', 'olivia.adams@example.com', 'dddd', 'Traveler and culture enthusiast', 'default.png', 'Berlin, Germany', '2024-12-07 06:51:38'),
(304, 'Mason Lee', 'mason_lee', 'mason.lee@example.com', 'dddd', 'Football player and coach', 'default.png', 'London, UK', '2024-12-07 06:51:38'),
(305, 'Noah Carter', 'noah_carter', 'noah.carter@example.com', 'dddd', 'Entrepreneur and tech innovator', 'default.png', 'New York, USA', '2024-12-07 06:51:38'),
(306, 'Liam Martinez', 'liam_martinez', 'liam.martinez@example.com', 'dddd', 'Travel photographer and food lover', 'default.png', 'Los Angeles, USA', '2024-12-07 06:51:38'),
(307, 'Sophia Johnson', 'sophia_johnson', 'sophia.johnson@example.com', 'dddd', 'Fitness trainer and wellness coach', 'default.png', 'Chicago, USA', '2024-12-07 06:51:38'),
(308, 'Olivia Lewis', 'olivia_lewis', 'olivia.lewis@example.com', 'dddd', 'Fashion designer and stylist', 'default.png', 'San Francisco, USA', '2024-12-07 06:51:38'),
(309, 'William Scott', 'william_scott', 'william.scott@example.com', 'dddd', 'Musician and songwriter', 'default.png', 'Austin, USA', '2024-12-07 06:51:38'),
(310, 'James Walker', 'james_walker', 'james.walker@example.com', 'dddd', 'Digital marketer and entrepreneur', 'default.png', 'London, UK', '2024-12-07 06:51:38'),
(311, 'Emma Davis', 'emma_davis', 'emma.davis@example.com', 'dddd', 'Artist and gallery curator', 'default.png', 'Paris, France', '2024-12-07 06:51:38'),
(312, 'Alexander Hall', 'alexander_hall', 'alexander.hall@example.com', 'dddd', 'Adventure enthusiast and nature lover', 'default.png', 'Sydney, Australia', '2024-12-07 06:51:38'),
(313, 'Isabella Moore', 'isabella_moore', 'isabella.moore@example.com', 'dddd', 'Writer and bookworm', 'default.png', 'Vancouver, Canada', '2024-12-07 06:51:38'),
(314, 'Benjamin Thomas', 'benjamin_thomas', 'benjamin.thomas@example.com', 'dddd', 'Tech geek and software developer', 'default.png', 'Berlin, Germany', '2024-12-07 06:51:38'),
(315, 'Charlotte White', 'charlotte_white', 'charlotte.white@example.com', 'dddd', 'Travel blogger and photographer', 'default.png', 'Rome, Italy', '2024-12-07 06:51:38'),
(316, 'Mia Young', 'mia_young', 'mia.young@example.com', 'dddd', 'Yoga instructor and fitness enthusiast', 'default.png', 'Los Angeles, USA', '2024-12-07 06:51:38'),
(317, 'Lucas Taylor', 'lucas_taylor', 'lucas.taylor@example.com', 'dddd', 'Sports coach and athlete', 'default.png', 'Chicago, USA', '2024-12-07 06:51:38'),
(318, 'Ella Adams', 'ella_adams', 'ella.adams@example.com', 'dddd', 'Food critic and recipe creator', 'default.png', 'Austin, USA', '2024-12-07 06:51:38'),
(319, 'Mason Wilson', 'mason_wilson', 'mason.wilson@example.com', 'dddd', 'Musician and guitar enthusiast', 'default.png', 'San Francisco, USA', '2024-12-07 06:51:38'),
(320, 'Charlotte King', 'charlotte_king', 'charlotte.king@example.com', 'dddd', 'Digital artist and designer', 'default.png', 'London, UK', '2024-12-07 06:51:38'),
(321, 'Harper Allen', 'harper_allen', 'harper.allen@example.com', 'dddd', 'Nature photographer and adventurer', 'default.png', 'Berlin, Germany', '2024-12-07 06:51:38'),
(322, 'Zoe Hill', 'zoe_hill', 'zoe.hill@example.com', 'dddd', 'Fashion influencer and blogger', 'default.png', 'Paris, France', '2024-12-07 06:51:38'),
(323, 'Alexander Wright', 'alexander_wright', 'alexander.wright@example.com', 'dddd', 'Tech entrepreneur and innovator', 'default.png', 'New York, USA', '2024-12-07 06:51:38'),
(324, 'Ella Rodriguez', 'ella_rodriguez', 'ella.rodriguez@example.com', 'dddd', 'Creative writer and storyteller', 'default.png', 'Vancouver, Canada', '2024-12-07 06:51:38'),
(325, 'Amelia Carter', 'amelia_carter', 'amelia.carter@example.com', 'dddd', 'Fitness coach and health enthusiast', 'default.png', 'Los Angeles, USA', '2024-12-07 06:51:38'),
(326, 'Jack Martinez', 'jack_martinez', 'jack.martinez@example.com', 'dddd', 'Photographer and travel explorer', 'default.png', 'San Francisco, USA', '2024-12-07 06:51:38'),
(327, 'Lucas Brown', 'lucas_brown', 'lucas.brown@example.com', 'dddd', 'Adventure lover and cyclist', 'default.png', 'Chicago, USA', '2024-12-07 06:51:38'),
(328, 'Ava Perez', 'ava_perez', 'ava.perez@example.com', 'dddd', 'Food blogger and chef', 'default.png', 'Austin, USA', '2024-12-07 06:51:38'),
(329, 'Elijah Thompson', 'elijah_thompson', 'elijah.thompson@example.com', 'dddd', 'Musician and singer-songwriter', 'default.png', 'London, UK', '2024-12-07 06:51:38'),
(330, 'Mia Scott', 'mia_scott', 'mia.scott@example.com', 'dddd', 'Yoga enthusiast and wellness coach', 'default.png', 'Berlin, Germany', '2024-12-07 06:51:38'),
(331, 'Charlotte Torres', 'charlotte_torres', 'charlotte.torres@example.com', 'dddd', 'Digital artist and creator', 'default.png', 'Rome, Italy', '2024-12-07 06:51:38'),
(332, 'Henry White', 'henry_white', 'henry.white@example.com', 'dddd', 'Tech enthusiast and developer', 'default.png', 'Sydney, Australia', '2024-12-07 06:51:38'),
(333, 'Olivia Harris', 'olivia_harris', 'olivia.harris@example.com', 'dddd', 'Creative designer and artist', 'default.png', 'New York, USA', '2024-12-07 06:51:38'),
(334, 'Ethan Baker', 'ethan_baker', 'ethan.baker@example.com', 'dddd', 'Sports lover and coach', 'default.png', 'Vancouver, Canada', '2024-12-07 06:51:38'),
(335, 'Ava Taylor', 'ava_taylor', 'ava.taylor@example.com', 'dddd', 'Fitness enthusiast and yoga coach', 'default.png', 'Los Angeles, USA', '2024-12-07 06:51:38'),
(336, 'Harper Wilson', 'harper_wilson', 'harper.wilson@example.com', 'dddd', 'Travel photographer and explorer', 'default.png', 'San Francisco, USA', '2024-12-07 06:51:38'),
(337, 'Lucas Young', 'lucas_young', 'lucas.young@example.com', 'dddd', 'Cyclist and outdoor enthusiast', 'default.png', 'Chicago, USA', '2024-12-07 06:51:38'),
(338, 'Emma Clark', 'emma_clark', 'emma.clark@example.com', 'dddd', 'Writer and passionate reader', 'default.png', 'Austin, USA', '2024-12-07 06:51:38'),
(339, 'Liam Torres', 'liam_torres', 'liam.torres@example.com', 'dddd', 'Tech entrepreneur and software innovator', 'default.png', 'London, UK', '2024-12-07 06:51:38'),
(340, 'Sophia Harris', 'sophia_harris', 'sophia.harris@example.com', 'dddd', 'Yoga coach and fitness trainer', 'default.png', 'Berlin, Germany', '2024-12-07 06:51:38'),
(341, 'Isabella Martinez', 'isabella_martinez', 'isabella.martinez@example.com', 'dddd', 'Fashion blogger and influencer', 'default.png', 'Paris, France', '2024-12-07 06:51:38'),
(342, 'James White', 'james_white', 'james.white@example.com', 'dddd', 'Nature photographer and artist', 'default.png', 'Sydney, Australia', '2024-12-07 06:51:38'),
(343, 'Amelia Young', 'amelia_young', 'amelia.young@example.com', 'dddd', 'Creative storyteller and writer', 'default.png', 'New York, USA', '2024-12-07 06:51:38'),
(344, 'Charlotte Wright', 'charlotte_wright', 'charlotte.wright@example.com', 'dddd', 'Fitness trainer and adventure seeker', 'default.png', 'Vancouver, Canada', '2024-12-07 06:51:38'),
(345, 'Lucas Rodriguez', 'lucas_rodriguez', 'lucas.rodriguez@example.com', 'dddd', 'Digital marketer and entrepreneur', 'default.png', 'Los Angeles, USA', '2024-12-07 06:51:38'),
(346, 'Ava Johnson', 'ava_johnson', 'ava.johnson@example.com', 'dddd', 'Travel explorer and food critic', 'default.png', 'San Francisco, USA', '2024-12-07 06:51:38'),
(347, 'Elijah Allen', 'elijah_allen', 'elijah.allen@example.com', 'dddd', 'Musician and creative artist', 'default.png', 'Chicago, USA', '2024-12-07 06:51:38'),
(348, 'Isabella Torres', 'isabella_torres', 'isabella.torres@example.com', 'dddd', 'Fashion designer and stylist', 'default.png', 'Austin, USA', '2024-12-07 06:51:38'),
(349, 'James Baker', 'james_baker', 'james.baker@example.com', 'dddd', 'Tech innovator and software developer', 'default.png', 'London, UK', '2024-12-07 06:51:38'),
(350, 'Sophia Clark', 'sophia_clark', 'sophia.clark@example.com', 'dddd', 'Wellness coach and yoga enthusiast', 'default.png', 'Berlin, Germany', '2024-12-07 06:51:38');

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
(89, 258, 27),
(90, 258, 28),
(91, 258, 9),
(92, 258, 10),
(93, 258, 15),
(94, 259, 15),
(95, 259, 17),
(96, 259, 16),
(97, 259, 10),
(98, 259, 33),
(99, 260, 12),
(100, 260, 13),
(101, 260, 14),
(102, 260, 6),
(103, 260, 18),
(104, 261, 21),
(105, 261, 23),
(106, 261, 22),
(107, 261, 24),
(108, 261, 7),
(109, 262, 17),
(110, 262, 27),
(111, 262, 29),
(112, 262, 19),
(113, 262, 30),
(114, 263, 30),
(115, 263, 31),
(116, 263, 32),
(117, 263, 15),
(118, 263, 33),
(119, 264, 12),
(120, 264, 13),
(121, 264, 14),
(122, 264, 18),
(123, 264, 9),
(124, 265, 15),
(125, 265, 16),
(126, 265, 6),
(127, 265, 18),
(128, 265, 33),
(129, 266, 27),
(130, 266, 28),
(131, 266, 29),
(132, 266, 12),
(133, 266, 19),
(134, 267, 24),
(135, 267, 25),
(136, 267, 28),
(137, 267, 9),
(138, 267, 7),
(139, 268, 15),
(140, 268, 9),
(141, 268, 27),
(142, 268, 16),
(143, 268, 12),
(144, 269, 18),
(145, 269, 19),
(146, 269, 6),
(147, 269, 13),
(148, 269, 14),
(149, 270, 19),
(150, 270, 16),
(151, 270, 15),
(152, 270, 24),
(153, 270, 9),
(154, 271, 27),
(155, 271, 29),
(156, 271, 9),
(157, 271, 15),
(158, 271, 12),
(159, 272, 24),
(160, 272, 25),
(161, 272, 15),
(162, 272, 6),
(163, 272, 9),
(164, 273, 30),
(165, 273, 31),
(166, 273, 32),
(167, 273, 15),
(168, 273, 17),
(169, 274, 17),
(170, 274, 19),
(171, 274, 27),
(172, 274, 30),
(173, 274, 31),
(174, 275, 21),
(175, 275, 23),
(176, 275, 22),
(177, 275, 7),
(178, 275, 6),
(179, 276, 15),
(180, 276, 16),
(181, 276, 33),
(182, 276, 12),
(183, 276, 7),
(184, 277, 15),
(185, 277, 16),
(186, 277, 19),
(187, 277, 12),
(188, 277, 13),
(189, 278, 17),
(190, 278, 19),
(191, 278, 30),
(192, 278, 32),
(193, 278, 27),
(194, 279, 15),
(195, 279, 16),
(196, 279, 6),
(197, 279, 18),
(198, 279, 19),
(199, 280, 24),
(200, 280, 25),
(201, 280, 30),
(202, 280, 32),
(203, 280, 15),
(204, 281, 21),
(205, 281, 23),
(206, 281, 22),
(207, 281, 7),
(208, 281, 16),
(209, 282, 15),
(210, 282, 16),
(211, 282, 19),
(212, 282, 12),
(213, 282, 18),
(214, 283, 30),
(215, 283, 31),
(216, 283, 32),
(217, 283, 15),
(218, 283, 17),
(219, 284, 24),
(220, 284, 25),
(221, 284, 27),
(222, 284, 29),
(223, 284, 15),
(224, 285, 18),
(225, 285, 19),
(226, 285, 12),
(227, 285, 14),
(228, 285, 6),
(229, 286, 9),
(230, 286, 10),
(231, 286, 15),
(232, 286, 33),
(233, 286, 12),
(234, 287, 15),
(235, 287, 27),
(236, 287, 28),
(237, 287, 29),
(238, 287, 9),
(239, 288, 17),
(240, 288, 19),
(241, 288, 30),
(242, 288, 32),
(243, 288, 27),
(244, 289, 24),
(245, 289, 25),
(246, 289, 9),
(247, 289, 15),
(248, 289, 16),
(249, 290, 27),
(250, 290, 29),
(251, 290, 9),
(252, 290, 12),
(253, 290, 15),
(254, 291, 9),
(255, 291, 15),
(256, 291, 10),
(257, 291, 33),
(258, 291, 18),
(259, 292, 15),
(260, 292, 16),
(261, 292, 6),
(262, 292, 12),
(263, 292, 33),
(264, 293, 16),
(265, 293, 24),
(266, 293, 1),
(267, 293, 4),
(268, 293, 11),
(269, 294, 16),
(270, 294, 15),
(271, 294, 34),
(272, 294, 11),
(273, 294, 5),
(274, 295, 13),
(275, 295, 12),
(276, 295, 14),
(277, 295, 10),
(278, 295, 6),
(279, 296, 17),
(280, 296, 16),
(281, 296, 32),
(282, 296, 31),
(283, 296, 14),
(284, 297, 21),
(285, 297, 22),
(286, 297, 2),
(287, 297, 1),
(288, 297, 4),
(289, 298, 21),
(290, 298, 23),
(291, 298, 22),
(292, 298, 16),
(293, 298, 1),
(294, 299, 15),
(295, 299, 20),
(296, 299, 33),
(297, 299, 8),
(298, 299, 7),
(299, 300, 16),
(300, 300, 34),
(301, 300, 11),
(302, 300, 10),
(303, 300, 5),
(304, 301, 16),
(305, 301, 15),
(306, 301, 33),
(307, 301, 34),
(308, 301, 8),
(309, 302, 17),
(310, 302, 32),
(311, 302, 31),
(312, 302, 30),
(313, 302, 7),
(314, 303, 2),
(315, 303, 9),
(316, 303, 27),
(317, 303, 29),
(318, 303, 28),
(319, 304, 17),
(320, 304, 16),
(321, 304, 15),
(322, 304, 32),
(323, 304, 8),
(324, 305, 16),
(325, 305, 15),
(326, 305, 33),
(327, 305, 11),
(328, 305, 10),
(329, 306, 31),
(330, 306, 30),
(331, 306, 3),
(332, 306, 9),
(333, 306, 28),
(334, 307, 17),
(335, 307, 16),
(336, 307, 32),
(337, 307, 31),
(338, 307, 14),
(339, 308, 17),
(340, 308, 16),
(341, 308, 32),
(342, 308, 2),
(343, 308, 1),
(344, 309, 13),
(345, 309, 12),
(346, 309, 14),
(347, 309, 33),
(348, 309, 6),
(349, 310, 16),
(350, 310, 15),
(351, 310, 33),
(352, 310, 11),
(353, 310, 5),
(354, 311, 21),
(355, 311, 23),
(356, 311, 22),
(357, 311, 2),
(358, 311, 1),
(359, 312, 30),
(360, 312, 14),
(361, 312, 27),
(362, 312, 29),
(363, 312, 28),
(364, 313, 16),
(365, 313, 34),
(366, 313, 11),
(367, 313, 10),
(368, 313, 5),
(369, 314, 15),
(370, 314, 14),
(371, 314, 33),
(372, 314, 25),
(373, 314, 5),
(374, 315, 24),
(375, 315, 2),
(376, 315, 9),
(377, 315, 27),
(378, 315, 28),
(379, 316, 17),
(380, 316, 16),
(381, 316, 32),
(382, 316, 31),
(383, 316, 14),
(384, 317, 17),
(385, 317, 16),
(386, 317, 32),
(387, 317, 31),
(388, 317, 8),
(389, 318, 17),
(390, 318, 16),
(391, 318, 32),
(392, 318, 31),
(393, 318, 30),
(394, 319, 13),
(395, 319, 12),
(396, 319, 14),
(397, 319, 6),
(398, 319, 8),
(399, 320, 21),
(400, 320, 23),
(401, 320, 22),
(402, 320, 26),
(403, 320, 2),
(404, 321, 24),
(405, 321, 2),
(406, 321, 1),
(407, 321, 27),
(408, 321, 29),
(409, 322, 17),
(410, 322, 16),
(411, 322, 32),
(412, 322, 1),
(413, 322, 7),
(414, 323, 16),
(415, 323, 15),
(416, 323, 34),
(417, 323, 11),
(418, 323, 5),
(419, 324, 34),
(420, 324, 11),
(421, 324, 10),
(422, 324, 5),
(423, 324, 6),
(424, 325, 17),
(425, 325, 16),
(426, 325, 32),
(427, 325, 31),
(428, 325, 7),
(429, 326, 24),
(430, 326, 2),
(431, 326, 3),
(432, 326, 27),
(433, 326, 29),
(434, 327, 25),
(435, 327, 4),
(436, 327, 10),
(437, 327, 27),
(438, 327, 28),
(439, 328, 17),
(440, 328, 32),
(441, 328, 31),
(442, 328, 30),
(443, 328, 7),
(444, 329, 13),
(445, 329, 12),
(446, 329, 14),
(447, 329, 11),
(448, 329, 6),
(449, 330, 17),
(450, 330, 16),
(451, 330, 32),
(452, 330, 34),
(453, 330, 10),
(454, 331, 21),
(455, 331, 22),
(456, 331, 15),
(457, 331, 18),
(458, 331, 33),
(459, 332, 16),
(460, 332, 15),
(461, 332, 33),
(462, 332, 25),
(463, 332, 8),
(464, 333, 21),
(465, 333, 23),
(466, 333, 22),
(467, 333, 16),
(468, 333, 2),
(469, 334, 17),
(470, 334, 16),
(471, 334, 32),
(472, 334, 31),
(473, 334, 8),
(474, 335, 17),
(475, 335, 16),
(476, 335, 32),
(477, 335, 34),
(478, 335, 8),
(479, 336, 25),
(480, 336, 2),
(481, 336, 4),
(482, 336, 29),
(483, 336, 28),
(484, 337, 25),
(485, 337, 4),
(486, 337, 3),
(487, 337, 10),
(488, 337, 28),
(489, 338, 16),
(490, 338, 34),
(491, 338, 11),
(492, 338, 10),
(493, 338, 5),
(494, 339, 16),
(495, 339, 15),
(496, 339, 33),
(497, 339, 25),
(498, 339, 10),
(499, 340, 17),
(500, 340, 16),
(501, 340, 32),
(502, 340, 31),
(503, 340, 8),
(504, 341, 17),
(505, 341, 16),
(506, 341, 32),
(507, 341, 1),
(508, 341, 7),
(509, 342, 23),
(510, 342, 22),
(511, 342, 24),
(512, 342, 2),
(513, 342, 29),
(514, 343, 34),
(515, 343, 11),
(516, 343, 10),
(517, 343, 5),
(518, 343, 6),
(519, 344, 17),
(520, 344, 16),
(521, 344, 32),
(522, 344, 27),
(523, 344, 29),
(524, 345, 16),
(525, 345, 15),
(526, 345, 33),
(527, 345, 11),
(528, 345, 5),
(529, 346, 31),
(530, 346, 30),
(531, 346, 9),
(532, 346, 27),
(533, 346, 29),
(534, 347, 21),
(535, 347, 23),
(536, 347, 22),
(537, 347, 12),
(538, 347, 14),
(539, 348, 16),
(540, 348, 15),
(541, 348, 32),
(542, 348, 1),
(543, 348, 7),
(544, 349, 16),
(545, 349, 15),
(546, 349, 33),
(547, 349, 10),
(548, 349, 8),
(549, 350, 17),
(550, 350, 16),
(551, 350, 32),
(552, 350, 31),
(553, 350, 8);

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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `saved_posts`
--
ALTER TABLE `saved_posts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `shares`
--
ALTER TABLE `shares`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=351;

--
-- AUTO_INCREMENT for table `user_blocks`
--
ALTER TABLE `user_blocks`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_selections`
--
ALTER TABLE `user_selections`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=554;

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
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `user_selections_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_selections_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
