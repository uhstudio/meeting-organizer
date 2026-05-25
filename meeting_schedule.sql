-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2026 at 10:15 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `meeting_schedule`
--

-- --------------------------------------------------------

--
-- Table structure for table `meetings`
--

CREATE TABLE `meetings` (
  `id` int(11) NOT NULL,
  `meeting_code` varchar(20) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `meeting_type` enum('online','offline','hybrid') NOT NULL,
  `meeting_date` date NOT NULL,
  `start_time` time NOT NULL,
  `duration` int(11) NOT NULL COMMENT 'in minutes',
  `end_time` time NOT NULL,
  `is_recurring` tinyint(1) DEFAULT 0,
  `recurring_pattern` varchar(50) DEFAULT NULL COMMENT 'daily, weekly, monthly',
  `room_id` int(11) DEFAULT NULL,
  `platform_id` int(11) DEFAULT NULL,
  `meeting_link` text DEFAULT NULL,
  `passcode` varchar(50) DEFAULT NULL,
  `requested_by` int(11) NOT NULL,
  `status` enum('scheduled','ongoing','completed','cancelled') DEFAULT 'scheduled',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meeting_attendance`
--

CREATE TABLE `meeting_attendance` (
  `id` int(11) NOT NULL,
  `meeting_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `status` enum('pending','present','absent') DEFAULT 'present',
  `marked_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meeting_participants`
--

CREATE TABLE `meeting_participants` (
  `id` int(11) NOT NULL,
  `meeting_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `attendance_status` enum('invited','accepted','declined','attended') DEFAULT 'invited',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meeting_platforms`
--

CREATE TABLE `meeting_platforms` (
  `id` int(11) NOT NULL,
  `platform_name` varchar(50) NOT NULL,
  `platform_type` varchar(50) NOT NULL DEFAULT 'Other',
  `account_email` varchar(100) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meeting_platforms`
--

INSERT INTO `meeting_platforms` (`id`, `platform_name`, `platform_type`, `account_email`, `status`, `created_at`) VALUES
(1, 'Zoom helpdesk@tjbservices.com', 'Zoom', 'helpdesk@tjbservices.com', 'active', '2026-01-07 08:08:46'),
(2, 'Zoom admin@tjbservices.com', 'Zoom', 'admin@tjbservices.com', 'active', '2026-01-07 08:08:46'),
(4, 'Zoom itsupport@tjbservices.com', 'Zoom', 'itsupport@tjbservices.com', 'active', '2026-01-18 15:48:47'),
(5, 'Teams helpdesk@tjbservices.com', 'Microsoft Teams', 'helpdesk@tjbservices.com', 'active', '2026-01-18 15:48:47');

-- --------------------------------------------------------

--
-- Table structure for table `meeting_rooms`
--

CREATE TABLE `meeting_rooms` (
  `id` int(11) NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `room_code` varchar(50) NOT NULL,
  `capacity` int(11) DEFAULT 0,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meeting_rooms`
--

INSERT INTO `meeting_rooms` (`id`, `room_name`, `room_code`, `capacity`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Meeting Room Admin I', 'MR-001', 50, 'gedung utama', 'active', '2026-01-07 08:08:46', '2026-01-18 15:33:19'),
(2, 'Meeting Room Admin II', 'MR-002', 100, 'Gedung utama', 'active', '2026-01-07 08:08:46', '2026-01-18 15:33:38'),
(3, 'Conference Room', 'CR-001', 200, 'Large conference room', 'active', '2026-01-07 08:08:46', '2026-01-08 06:43:07'),
(4, 'Training Room Admin', 'TR-001', 20, 'gedung gedung', 'active', '2026-01-07 08:08:46', '2026-01-18 15:34:22'),
(5, 'Training Room Aminity', 'TR-002', 50, 'Training room', 'active', '2026-01-18 15:35:42', '2026-01-18 15:35:42'),
(6, 'Meeting Room Aminity', 'MR-005', 100, 'Aminity', 'active', '2026-01-18 15:35:42', '2026-01-18 15:36:13');

-- --------------------------------------------------------

--
-- Table structure for table `room_activities`
--

CREATE TABLE `room_activities` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `activity_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_activities`
--

INSERT INTO `room_activities` (`id`, `room_id`, `activity_name`, `description`, `display_order`, `created_at`) VALUES
(1, 1, 'weekly meeting', NULL, 1, '2026-01-07 08:08:46'),
(2, 1, 'finance meeting', NULL, 2, '2026-01-07 08:08:46'),
(3, 2, 'development meeting', NULL, 1, '2026-01-07 08:08:46'),
(4, 3, 'no activity', NULL, 1, '2026-01-07 08:08:46'),
(5, 4, 'no activity', NULL, 1, '2026-01-07 08:08:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(50) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_id`, `username`, `email`, `password`, `full_name`, `position`, `role`, `created_at`, `updated_at`) VALUES
(4, NULL, 'admin1', 'admin1@tjbps.com', '$2y$10$eRvwCAMb037bTGNbGSIUA.PW98L/eJnnlt6DqCGFgRbvqNraIjjyO', 'admin tjb satu', NULL, 'admin', '2026-01-07 12:52:13', '2026-01-08 02:11:46'),
(5, NULL, 'user1', 'user1@tjbps.com', '$2y$10$ILkUjE4cqcblxOHLvbC9MOtXnU0Ybhv7GYebxVX7MGtpzuTr7CRCG', 'user tjb satu', NULL, 'user', '2026-01-07 12:52:13', '2026-01-08 02:11:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `meeting_code` (`meeting_code`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `platform_id` (`platform_id`),
  ADD KEY `requested_by` (`requested_by`);

--
-- Indexes for table `meeting_attendance`
--
ALTER TABLE `meeting_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meeting_id` (`meeting_id`),
  ADD KEY `status` (`status`),
  ADD KEY `email` (`email`),
  ADD KEY `marked_at` (`marked_at`),
  ADD KEY `idx_meeting_lookup` (`meeting_id`,`status`),
  ADD KEY `idx_email_search` (`email`,`meeting_id`);

--
-- Indexes for table `meeting_participants`
--
ALTER TABLE `meeting_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meeting_id` (`meeting_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `meeting_platforms`
--
ALTER TABLE `meeting_platforms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meeting_rooms`
--
ALTER TABLE `meeting_rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_code` (`room_code`);

--
-- Indexes for table `room_activities`
--
ALTER TABLE `room_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username_2` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `meeting_attendance`
--
ALTER TABLE `meeting_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `meeting_participants`
--
ALTER TABLE `meeting_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=306;

--
-- AUTO_INCREMENT for table `meeting_platforms`
--
ALTER TABLE `meeting_platforms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `meeting_rooms`
--
ALTER TABLE `meeting_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `room_activities`
--
ALTER TABLE `room_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `meetings`
--
ALTER TABLE `meetings`
  ADD CONSTRAINT `meetings_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `meeting_rooms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `meetings_ibfk_2` FOREIGN KEY (`platform_id`) REFERENCES `meeting_platforms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `meetings_ibfk_3` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `meeting_attendance`
--
ALTER TABLE `meeting_attendance`
  ADD CONSTRAINT `fk_attendance_meeting` FOREIGN KEY (`meeting_id`) REFERENCES `meetings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `meeting_participants`
--
ALTER TABLE `meeting_participants`
  ADD CONSTRAINT `meeting_participants_ibfk_1` FOREIGN KEY (`meeting_id`) REFERENCES `meetings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `meeting_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_activities`
--
ALTER TABLE `room_activities`
  ADD CONSTRAINT `room_activities_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `meeting_rooms` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
