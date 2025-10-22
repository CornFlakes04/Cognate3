-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2025 at 09:09 PM
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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `sku` varchar(64) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `date_added` date DEFAULT NULL,
  `categories` set('Data','Bionic','Listen','Vision','Control','Sensors') NOT NULL,
  `short_desc` text DEFAULT NULL,
  `short_description` text NOT NULL,
  `specs` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `sku`, `price`, `date_added`, `categories`, `short_desc`, `short_description`, `specs`, `image_url`, `image_path`, `archived`, `created_at`, `updated_at`) VALUES
(7, 'IoT-Enabled Bionic Prosthetic Hand', 'IoTBPH', 14900.00, '2025-10-16', 'Data,Bionic', NULL, 'Research-grade prosthetic/robotic hand with multi-channel sEMG and closed-loop haptics. Prototype gestures and control strategies rapidly; access low-level streams for custom ML, with ROS 2 drivers and logging tools for reproducible experiments.', '12 DOF, 8-ch sEMG, Haptics, ROS2', NULL, '/Cognate3/uploads/20251022_205029_6df56f7f.png', 0, '2025-10-16 12:50:36', '2025-10-22 19:08:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','editor','user') NOT NULL DEFAULT 'user',
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `password` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `role`, `status`, `password`, `last_login`, `archived`, `updated_at`, `created_at`) VALUES
(11, 'third', 'third Rodrigazoq', 'third@email.com', 'user', 'active', '$2y$10$PZ822OE5QCJwd4FCsR2eaOumIS6C.slAtZ16cXalrOdwD/vPAp.Ni', '2025-10-23 02:50:46', 0, '2025-10-22 18:56:11', '2025-10-22 18:33:35'),
(12, 'admin', 'System Administrator', 'admin@nexora.local', 'admin', 'active', '$2a$12$NhZtlfIV7BqRhJiOCr9vEu2inA.Gov5Y9YP.ZliSnoxBTfVHpuWfa', '2025-10-23 02:57:11', 0, '2025-10-22 18:57:11', '2025-10-22 18:33:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `idx_name` (`name`);

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
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
