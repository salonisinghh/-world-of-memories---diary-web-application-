-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 02:53 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `memories`
--

-- --------------------------------------------------------

--
-- Table structure for table `diary_entries`
--

CREATE TABLE `diary_entries` (
  `id` int(50) NOT NULL,
  `user_id` int(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `edited_entries`
--

CREATE TABLE `edited_entries` (
  `id` int(11) NOT NULL,
  `original_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `content` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `edited_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `edited_entries`
--

INSERT INTO `edited_entries` (`id`, `original_id`, `email`, `title`, `date`, `content`, `image`, `edited_at`) VALUES
(7, 1, 'alpha@gmail.com', 'True Beauty🌺', '2025-05-08', '<span style=\"color: rgb(210, 20, 20);\"><span style=\"font-weight: bold;\">Dear Diary</span>,</span>\r\ntrue beauty🌸 is not a<span style=\"background-color: rgb(36, 235, 142); color: rgb(22, 19, 19);\">bout the outer beauty of the person but it is</span> about the inner beauty💖 of the person ..the good heart of the person and the pure soul❤️', 'uploads/1746793985_holi.jpeg', '2025-05-09 12:33:05'),
(10, 3, 'sng123@gmail.com', 'True Beauty💖', '2025-05-14', '\r\nDear Diary,\r\nIn the the world full of people everyone is talking about the beauty but no one understand what beauty is actually 🙂.the real beauty is the good heart❤️person .the soul which is pure🌍.\r\n', 'uploads/1747228225_tree.jpg', '2025-05-14 13:10:25'),
(11, 4, 'sng123@gmail.com', 'The Day of Taj mahal 🤩', '2025-05-14', '<span style=\"font-weight: bold; color: rgb(212, 22, 22); font-size: large;\">Dear Diary,😊</span>&nbsp;<div><span style=\"font-weight: bold;\">Today was one of those rare days that<span style=\"background-color: rgb(189, 47, 208);\"> <span style=\"color: rgb(244, 235, 235);\">feel like a dream woven into reality</span></span>😍. I finally got the <span style=\"background-color: rgb(237, 111, 80);\">chance to visit the Taj Mahal✌️</span>—a monument I’ve only seen in pictures and heard about in stories of eternal love.\r\n\r\nAs I entered through the gran</span></div>', 'uploads/taj.jpeg', '2025-05-14 13:16:13'),
(12, 4, 'sng123@gmail.com', 'The Day of Taj mahal 🤩', '2025-05-14', 'Dear Diary,😊\r\nToday was one of those rare days that feel like a dream woven into reality😍. I finally got the chance to visit the Taj Mahal✌️—a monument I’ve only seen in pictures and heard about in stories of eternal love.\r\n\r\nAs I entered through the gran', 'uploads/taj.jpeg', '2025-05-15 08:20:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(50) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`) VALUES
(1, 'Alpha', 'alpha@gmail.com', '$2y$10$Z9qmyvZdpWgaGwMIFbAFN.N7rr77BqTi4tOi/u35B7aVhgOcobqiW');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `diary_entries`
--
ALTER TABLE `diary_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `edited_entries`
--
ALTER TABLE `edited_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `diary_entries`
--
ALTER TABLE `diary_entries`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `edited_entries`
--
ALTER TABLE `edited_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `diary_entries`
--
ALTER TABLE `diary_entries`
  ADD CONSTRAINT `diary_entries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
