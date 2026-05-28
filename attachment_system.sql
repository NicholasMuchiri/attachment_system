-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2026 at 12:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+01:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `attachment_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachees`
--

CREATE TABLE `attachees` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `institution` varchar(100) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attachees`
--

INSERT INTO `attachees` (`id`, `name`, `email`, `password`, `created_at`, `institution`, `course`) VALUES
(36, 'omondi timon', 'omonditimon2@gmail.com', '$2y$10$942uHO3S7YBeuEJ0e44ieOzVGWw/tRdtHuNdEQs1XCYq7deUITltC', '2026-05-22 09:48:53', 'kyu', 'IT'),
(37, 'omondi timon', 'omonditimon@gmail.com', '$2y$10$yA3h.RGQ0myB9lZAHuPiUOQXTPNz5zkkxuKxUwxFLxJjkmflGsnay', '2026-05-22 10:07:56', 'kyu', 'cs'),
(38, 'nicholasmuchiri', 'nicholasmuchiri45@gmail.com', '$2y$10$DNNcgwkDmNK5q5dsRzC.sOqywuo9rZp4IMnQFQCrUVsgFDw4MQeaq', '2026-05-22 10:30:13', 'UON', 'CPA');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `attachee_id` int(11) DEFAULT NULL,
  `sign_in` datetime DEFAULT NULL,
  `sign_out` datetime DEFAULT NULL,
  `marked_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `attachee_id`, `sign_in`, `sign_out`, `marked_at`) VALUES
(13, 37, '2026-05-22 12:29:13', '2026-05-22 12:29:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `attachee_id` int(11) DEFAULT NULL,
  `report_text` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `attachee_id`, `report_text`, `file_path`, `uploaded_at`) VALUES
(15, 38, 'CPA Guy Here!', 'uploads/php_cookbook_2.pdf', '2026-05-22 10:31:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachees`
--
ALTER TABLE `attachees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_attendance_attachee` (`attachee_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reports_attachee` (`attachee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachees`
--
ALTER TABLE `attachees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `fk_attendance_attachee` FOREIGN KEY (`attachee_id`) REFERENCES `attachees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `fk_reports_attachee` FOREIGN KEY (`attachee_id`) REFERENCES `attachees` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
