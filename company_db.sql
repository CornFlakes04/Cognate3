-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2025 at 06:46 PM
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `sku`, `price`, `date_added`, `categories`, `short_desc`, `short_description`, `specs`, `image_url`, `image_path`, `created_at`, `updated_at`) VALUES
(5, 'Predictive Maintenance Platform', 'PMP', 2490.00, '2025-10-16', 'Data,Sensors', NULL, 'Rack-mount edge platform that centralizes vibration and temperature sensing for rotating assets. Performs envelope/spectral feature extraction on-device and feeds RUL models so you can act before failure. Integrates with existing SCADA/historians via OPC-UA/Modbus/MQTT and buffers weeks of data to NVMe for automatic backfill after outages.', '24ch IEPE, OPC-UA, RUL Models, 1U Rack', NULL, 'uploads/20251016_144735_1b359942.png', '2025-10-16 12:47:35', '2025-10-16 12:47:35'),
(6, 'Smart City Traffic Flow Analytics', 'SCTFA', 1890.00, '2025-10-16', 'Data,Vision', NULL, 'Control-room application that fuses dozens of street cameras into a live picture of city traffic. Streams counts, speed, dwell and congestion heatmaps in real time with GPU overlays. Federates across districts, exports GeoJSON/CSV, and supports SSO for ops and planning teams.', '64 RTSP, Heatmaps, GPU Overlays, SSO', NULL, 'uploads/20251016_144900_bad46632.png', '2025-10-16 12:49:00', '2025-10-16 12:49:00'),
(7, 'IoT-Enabled Bionic Prosthetic Hand', 'IoTBPH', 1490.00, '2025-10-16', 'Data,Bionic', NULL, 'Research-grade prosthetic/robotic hand with multi-channel sEMG and closed-loop haptics. Prototype gestures and control strategies rapidly; access low-level streams for custom ML, with ROS 2 drivers and logging tools for reproducible experiments.', '12 DOF, 8-ch sEMG, Haptics, ROS2', NULL, 'uploads/20251016_145036_1ec0a93d.png', '2025-10-16 12:50:36', '2025-10-16 12:50:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `theme` varchar(10) NOT NULL DEFAULT 'system'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `password`, `last_login`, `theme`) VALUES
(2, 'testuser', '', '', '$2y$10$FHdmzQnNVyK5/IWLi1VKIePYrfLr2D45ejsDIPA9hp60jjNEqmWBy', '2025-10-05 22:27:23', 'light'),
(3, 'ace', '', 'ace@gmail.com', '$2y$10$E2/z99walPcfJWOrH7d7AO519.lNeCuVEFvYC.BEMhZaOb1JjwUAC', '2025-10-05 22:27:02', 'dark'),
(4, 'ace2', '', 'ace2@gmail.com', '$2y$10$u92XuffvRXt7jQNBoLm8Xu3yqFSGyeqMrqDMQ7bS6ixyapViB.3ye', NULL, 'system'),
(5, 'ace44', '', 'dasc@gmail.com', '$2y$10$Y5KhUaf5OTanM39hCZEKeO6rsQnq5ohjL5I2oTVGLT00jv7URUCOa', '2025-10-05 21:46:52', 'system'),
(6, 'ace432', '', 'ace38t3@gmauil.cpm', '$2y$10$Ak97.IWi8IeLoA7UsJuBvuBQcTsrZYxAobv6BNWtRTVqwOwk16JHy', NULL, 'system'),
(7, 'ace55', '', 'ace55@gmail.com', '$2y$10$s4kvxjvWnnU0wGNnUFD7xedcxYnNTOBcYcYq8fXKAeqieqz5fMIBa', '2025-10-05 22:55:00', 'dark'),
(8, 'third', '', 'third@eamil.com', '$2y$10$V9SZbndl0FjERwScMbr/guhrnUB9rj6yHgW7CPMpok7LscNkbQ1Be', '2025-10-16 19:45:56', 'light'),
(9, 'admin', '', 'admin@email.com', '$2y$10$2jyuqxNlp1PSwz.rvuRiIORxEO28alm08m6ajP.2WGHZUqb58AVAq', '2025-10-17 00:17:38', 'system'),
(10, 'qwe', '', 'qwe@qwe.com', '$2y$10$9Du4DWO3iUeg3GqtYi4TC.CJF75PTZi7WHP//6RgdhRT7nA06qCxu', '2025-10-16 21:39:02', 'system');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
