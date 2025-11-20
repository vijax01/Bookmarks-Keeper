-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2025 at 10:09 AM
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
-- Database: `bookmarks`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category`, `user_id`) VALUES
(1, 'Courses', NULL),
(4, 'Tools', NULL),
(5, 'Music ', NULL),
(6, 'Web Dev Links', NULL),
(10, 'Coding', NULL),
(11, 'Entertainment', NULL),
(12, 'Courses', 1);

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `link_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `link_name` varchar(255) NOT NULL,
  `link_url` text NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `links`
--

INSERT INTO `links` (`link_id`, `category_id`, `link_name`, `link_url`, `user_id`) VALUES
(1, 1, 'Knowledge Gate', 'https://learn.knowledgegate.in/learn', NULL),
(2, 1, 'Code With Harry', 'https://www.codewithharry.com/', NULL),
(3, 1, 'Data Flair', 'https://data-flair.training/', NULL),
(4, 1, 'Udemy', 'https://www.udemy.com/home/my-courses/learning/', NULL),
(5, 1, 'Gateway Classes', 'https://web.classplusapp.com/store/home?tabCategoryId=2', NULL),
(8, 6, 'Prebuilt Components', 'https://www.w3schools.com/bootstrap/bootstrap_templates.asp', NULL),
(9, 6, 'Starter Template', 'https://getbootstrap.com/docs/4.4/getting-started/introduction/', NULL),
(17, 4, 'Tinkercad', 'https://www.tinkercad.com/things/hPTLbPVPOuf-mighty-duup-blad/editel?tenant=circuits', NULL),
(18, 4, 'MIT App Inventor 2', 'https://ai2.appinventor.mit.edu/#6167069508829184', NULL),
(20, 4, 'PHP My Admin', 'http://localhost/phpmyadmin/', NULL),
(22, 5, 'Sound Cloud', 'https://soundcloud.com/prakansh/sets/favourite', NULL),
(26, 1, 'PW', 'https://www.pw.live/study/batches/study/my-batches', NULL),
(27, 6, 'JQuery', 'https://jquery.com/', NULL),
(28, 6, 'Bootstrap', 'https://getbootstrap.com/', NULL),
(31, 6, 'npm', 'https://www.npmjs.com/package/', NULL),
(33, 6, 'PNG Images', 'https://www.pngegg.com/', NULL),
(34, 6, 'CSS Loaders', 'https://cssloaders.github.io/', NULL),
(35, 4, 'Chat GPT', 'https://chatgpt.com/', NULL),
(41, 10, 'Leetcode', 'https://leetcode.com/problemset/', NULL),
(42, 10, 'GFG', 'https://www.geeksforgeeks.org/problem-of-the-day', NULL),
(43, 10, 'Github', 'https://github.com/vijax01', NULL),
(44, 10, 'Overleaf', 'https://www.overleaf.com/', NULL),
(45, 5, 'Pixaby Free Music', 'http://pixabay.com/music/', NULL),
(46, 5, 'Spotify', 'https://open.spotify.com/', NULL),
(47, 5, 'Youtube Music', 'https://music.youtube.com/', NULL),
(48, 11, 'Marvel Movies', 'https://www.marvel.com/movies', NULL),
(49, 11, 'Hotstar', 'https://www.hotstar.com/in/home', NULL),
(50, 11, 'Amazon Prime', 'https://www.primevideo.com/region/eu/offers/nonprimehomepage/ref=dv_web_force_root', NULL),
(51, 12, 'Udemy', 'https://www.udemy.com/home/my-courses/learning/', 1),
(52, 12, 'Code With Harry', 'https://www.codewithharry.com/courses', 1),
(54, 12, 'PW', 'https://www.pw.live/study-v2/batches', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `google_id` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `picture` varchar(512) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `google_id`, `email`, `name`, `picture`, `created_at`) VALUES
(1, '116300213817582255767', 'vijax01@gmail.com', 'Vijax', 'https://lh3.googleusercontent.com/a/ACg8ocL-5ddvvwRlzIex2sDvPBJKJAPg8uD2Gos7hQh5UsZRDI0N3T8=s96-c', '2025-11-19 18:36:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `fk_category_user` (`user_id`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`link_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `fk_links_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `google_id` (`google_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `link_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `fk_category_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `links`
--
ALTER TABLE `links`
  ADD CONSTRAINT `fk_links_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `links_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
