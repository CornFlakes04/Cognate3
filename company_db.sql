-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 05, 2025 at 04:56 PM
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
-- Database: `company_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `theme` varchar(10) NOT NULL DEFAULT 'system'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `last_login`, `theme`) VALUES
(2, 'testuser', '', '$2y$10$FHdmzQnNVyK5/IWLi1VKIePYrfLr2D45ejsDIPA9hp60jjNEqmWBy', '2025-10-05 22:27:23', 'light'),
(3, 'ace', 'ace@gmail.com', '$2y$10$E2/z99walPcfJWOrH7d7AO519.lNeCuVEFvYC.BEMhZaOb1JjwUAC', '2025-10-05 22:27:02', 'dark'),
(4, 'ace2', 'ace2@gmail.com', '$2y$10$u92XuffvRXt7jQNBoLm8Xu3yqFSGyeqMrqDMQ7bS6ixyapViB.3ye', NULL, 'system'),
(5, 'ace44', 'dasc@gmail.com', '$2y$10$Y5KhUaf5OTanM39hCZEKeO6rsQnq5ohjL5I2oTVGLT00jv7URUCOa', '2025-10-05 21:46:52', 'system'),
(6, 'ace432', 'ace38t3@gmauil.cpm', '$2y$10$Ak97.IWi8IeLoA7UsJuBvuBQcTsrZYxAobv6BNWtRTVqwOwk16JHy', NULL, 'system'),
(7, 'ace55', 'ace55@gmail.com', '$2y$10$s4kvxjvWnnU0wGNnUFD7xedcxYnNTOBcYcYq8fXKAeqieqz5fMIBa', '2025-10-05 22:55:00', 'dark');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_username` (`username`),
  ADD UNIQUE KEY `uniq_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
