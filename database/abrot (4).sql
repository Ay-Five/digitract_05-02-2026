-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 16, 2026 at 12:59 PM
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
-- Database: `abrot`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `fullname` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `modified_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `fullname`, `email`, `password`, `modified_date`, `created_at`) VALUES
(1, 'Admin Eds', 'abrotcarl@gmail.com', '$2y$10$Ut9KPGy2IHBiyOC5mctdZeq5F09r4G92bjVVYLnj/qc1jbuWzMz6e', NULL, '2026-03-16 06:39:38');

-- --------------------------------------------------------

--
-- Table structure for table `admin_login_history`
--

CREATE TABLE `admin_login_history` (
  `admin_login_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `login_time` datetime NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `login_status` enum('success','failed') NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `maps_link` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_login_history`
--

INSERT INTO `admin_login_history` (`admin_login_id`, `admin_id`, `login_time`, `ip_address`, `user_agent`, `latitude`, `longitude`, `login_status`, `city`, `region`, `country`, `maps_link`, `remarks`) VALUES
(1, 1, '2026-03-16 14:40:23', '::1', 'Mozilla/5.0', '', '', 'success', NULL, NULL, NULL, NULL, 'Password verified, OTP sent'),
(2, 1, '2026-03-21 09:16:45', '::1', 'Mozilla/5.0', NULL, NULL, 'success', 'Iloilo City', 'Western Visayas', 'Philippines', NULL, 'Password verified, OTP sent'),
(3, 1, '2026-03-25 13:32:59', '::1', 'Mozilla/5.0', NULL, NULL, 'success', 'Iloilo City', 'Western Visayas', 'Philippines', NULL, 'Password verified, OTP sent'),
(4, 1, '2026-03-26 15:43:13', '::1', 'Mozilla/5.0', NULL, NULL, 'success', 'Iloilo City', 'Western Visayas', 'Philippines', NULL, 'Password verified, OTP sent'),
(5, 1, '2026-04-05 20:54:22', '::1', 'Mozilla/5.0', NULL, NULL, 'success', 'Iloilo City', 'Western Visayas', 'Philippines', NULL, 'Password verified, OTP sent'),
(6, 1, '2026-04-12 08:30:00', '::1', 'Mozilla/5.0', NULL, NULL, 'success', 'Iloilo City', 'Western Visayas', 'Philippines', NULL, 'Password verified, OTP sent'),
(7, 1, '2026-04-15 10:15:00', '::1', 'Mozilla/5.0', NULL, NULL, 'failed', NULL, NULL, NULL, NULL, 'Wrong password'),
(8, 1, '2026-04-20 14:00:00', '::1', 'Mozilla/5.0', NULL, NULL, 'success', 'Iloilo City', 'Western Visayas', 'Philippines', NULL, 'Password verified, OTP sent'),
(9, 1, '2026-04-26 09:00:00', '::1', 'Mozilla/5.0', NULL, NULL, 'success', 'Iloilo City', 'Western Visayas', 'Philippines', NULL, 'Password verified, OTP sent');

-- --------------------------------------------------------

--
-- Table structure for table `admin_otp_history`
--

CREATE TABLE `admin_otp_history` (
  `admin_otp_history_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `otp_code` varchar(10) NOT NULL,
  `purpose` enum('login_verification','forgot_password') NOT NULL,
  `sent_at` datetime NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL,
  `verified_at` datetime DEFAULT NULL,
  `status` enum('sent','used','expired','failed') NOT NULL DEFAULT 'sent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_otp_history`
--

INSERT INTO `admin_otp_history` (`admin_otp_history_id`, `admin_id`, `otp_code`, `purpose`, `sent_at`, `expires_at`, `verified_at`, `status`) VALUES
(1, 1, '947510', 'login_verification', '2026-03-16 14:40:17', '2026-03-16 14:50:17', '2026-03-16 14:40:53', 'used'),
(2, 1, '947851', 'login_verification', '2026-03-16 15:47:39', '2026-03-16 15:57:39', '2026-03-16 15:48:11', 'used'),
(3, 1, '332519', 'login_verification', '2026-03-16 16:04:05', '2026-03-16 16:14:05', '2026-03-16 16:04:28', 'used'),
(4, 1, '338652', 'login_verification', '2026-03-20 20:10:53', '2026-03-20 20:20:53', '2026-03-20 20:11:31', 'used'),
(5, 1, '210037', 'login_verification', '2026-03-21 09:16:51', '2026-03-21 09:26:51', '2026-03-21 09:17:37', 'used'),
(6, 1, '775704', 'login_verification', '2026-03-25 13:38:22', '2026-03-25 13:48:22', '2026-03-25 13:38:45', 'used'),
(7, 1, '513499', 'login_verification', '2026-04-05 20:54:17', '2026-04-05 21:04:17', '2026-04-05 20:54:38', 'used');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `start_date` date NOT NULL COMMENT 'Must be Sat for TDC',
  `total_hours` tinyint(3) NOT NULL DEFAULT 15,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','approved','in_progress','completed','rejected','cancelled') NOT NULL DEFAULT 'pending',
  `student_note` text DEFAULT NULL,
  `instructor_note` text DEFAULT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reviewed_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `tdc_overall_grade` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Final TDC overall grade 1-100',
  `pdc_overall_rating` tinyint(1) DEFAULT NULL COMMENT 'PDC overall rating 1-5',
  `pdc_overall_grade` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `student_id`, `instructor_id`, `course_id`, `start_date`, `total_hours`, `total_price`, `status`, `student_note`, `instructor_note`, `requested_at`, `reviewed_at`, `completed_at`, `tdc_overall_grade`, `pdc_overall_rating`, `pdc_overall_grade`) VALUES
(1, 27, 1, 1, '2026-03-28', 15, 2010.00, 'completed', '', 'Good student, completed all sessions.', '2026-03-27 03:28:43', '2026-03-27 11:28:57', '2026-04-06 20:43:24', 90, NULL, NULL),
(2, 28, 1, 1, '2026-04-19', 15, 2010.00, 'completed', '', '', '2026-04-03 10:53:28', '2026-04-03 18:54:03', '2026-05-07 22:24:41', 65, NULL, NULL),
(3, 27, 1, 2, '2026-05-02', 15, 1665.00, 'completed', '', '', '2026-04-28 06:27:23', '2026-04-29 10:34:54', '2026-05-01 22:46:45', 88, NULL, NULL),
(4, 27, 1, 5, '2026-05-15', 8, 1352.00, 'completed', '[Vehicle: Car] ', '', '2026-05-07 14:19:08', '2026-05-07 22:19:19', '2026-05-07 22:21:46', NULL, NULL, NULL),
(5, 27, 1, 5, '2026-05-16', 8, 1352.00, 'cancelled', '[Vehicle: Car] ', NULL, '2026-05-07 14:34:39', NULL, NULL, NULL, NULL, NULL),
(8, 27, 1, 5, '2026-06-10', 8, 1352.00, 'cancelled', '', '', '2026-06-09 11:46:36', '2026-06-09 19:46:51', NULL, NULL, NULL, NULL),
(9, 27, 1, 1, '2026-06-16', 15, 2010.00, 'cancelled', 'Assigned by admin.', NULL, '2026-06-15 05:07:22', '2026-06-15 13:07:22', NULL, NULL, NULL, NULL),
(10, 27, 1, 5, '2026-06-17', 8, 1352.00, 'approved', '', '', '2026-06-16 10:50:19', '2026-06-16 18:50:39', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `course_schedules`
--

CREATE TABLE `course_schedules` (
  `schedule_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `schedule_date` date NOT NULL COMMENT 'The start_date of the appointment',
  `booked_count` tinyint(3) NOT NULL DEFAULT 0,
  `student_limit` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Snapshot of limit at booking time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_schedules`
--

INSERT INTO `course_schedules` (`schedule_id`, `course_id`, `instructor_id`, `schedule_date`, `booked_count`, `student_limit`) VALUES
(1, 5, 1, '2026-06-10', 0, 1),
(2, 1, 1, '2026-06-16', 0, 12),
(3, 5, 1, '2026-06-17', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `instructor_id` int(11) NOT NULL,
  `fullname` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `certificate_image` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `modified_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`instructor_id`, `fullname`, `email`, `mobile`, `bio`, `mobile_number`, `certificate_image`, `password`, `modified_date`, `created_at`) VALUES
(1, 'Instructor Carl', 'abrotcarl@gmail.com', '099539999', 'yjgtug', '09171234567', 'cert_1_1774057601.png', '$2y$10$Xjz.KySt83TQ8U26ZRgHTun0LXafyW4cfzio8rSx8NtecklPHVx3.', '2026-06-15 19:19:25', '2026-03-16 11:08:26');

-- --------------------------------------------------------

--
-- Table structure for table `instructor_courses`
--

CREATE TABLE `instructor_courses` (
  `course_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `course_type` enum('TDC Face-to-Face','TDC Online','PDC Manual','PDC Automatic') NOT NULL,
  `description` text DEFAULT NULL,
  `price_per_hr` decimal(10,2) DEFAULT NULL,
  `student_limit` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Max students per start_date. NULL = unlimited.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructor_courses`
--

INSERT INTO `instructor_courses` (`course_id`, `instructor_id`, `course_type`, `description`, `price_per_hr`, `student_limit`, `is_active`, `created_at`) VALUES
(1, 1, 'TDC Face-to-Face', '', 134.00, 12, 1, '2026-03-22 10:16:48'),
(2, 1, 'TDC Online', '', 111.00, 1, 1, '2026-03-22 10:53:54'),
(5, 1, 'PDC Manual', '', 169.00, 3, 1, '2026-05-07 14:14:42'),
(6, 1, 'PDC Automatic', '', 100.00, 1, 1, '2026-05-07 14:14:58');

-- --------------------------------------------------------

--
-- Table structure for table `instructor_login_history`
--

CREATE TABLE `instructor_login_history` (
  `instructor_login_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `login_time` datetime NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `login_status` enum('success','failed') NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `maps_link` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructor_login_history`
--

INSERT INTO `instructor_login_history` (`instructor_login_id`, `instructor_id`, `login_time`, `ip_address`, `user_agent`, `latitude`, `longitude`, `login_status`, `city`, `region`, `country`, `maps_link`, `remarks`) VALUES
(1, 1, '2026-03-16 19:13:25', '::1', 'Mozilla/5.0', '10.698909', '122.541855', 'success', 'Altavas', 'Western Visayas', 'Philippines', 'https://www.google.com/maps?q=10.698909,122.541855', 'Password verified, OTP sent'),
(2, 1, '2026-03-21 09:40:29', '::1', 'Mozilla/5.0', NULL, NULL, 'success', 'Iloilo City', 'Western Visayas', 'Philippines', NULL, 'Password verified, OTP sent'),
(3, 1, '2026-03-25 13:52:04', '::1', 'Mozilla/5.0', NULL, NULL, 'success', 'Iloilo City', 'Western Visayas', 'Philippines', NULL, 'Password verified, OTP sent'),
(4, 1, '2026-04-05 20:39:35', '::1', 'Mozilla/5.0', NULL, NULL, 'success', 'Iloilo City', 'Western Visayas', 'Philippines', NULL, 'Password verified, OTP sent');

-- --------------------------------------------------------

--
-- Table structure for table `instructor_otp_history`
--

CREATE TABLE `instructor_otp_history` (
  `instructor_otp_history_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `otp_code` varchar(10) NOT NULL,
  `purpose` enum('login_verification','forgot_password','password_reset') NOT NULL,
  `sent_at` datetime NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL,
  `verified_at` datetime DEFAULT NULL,
  `status` enum('sent','used','expired','failed') NOT NULL DEFAULT 'sent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructor_otp_history`
--

INSERT INTO `instructor_otp_history` (`instructor_otp_history_id`, `instructor_id`, `otp_code`, `purpose`, `sent_at`, `expires_at`, `verified_at`, `status`) VALUES
(1, 1, '794560', 'password_reset', '2026-03-18 16:40:07', '2026-03-18 16:50:07', '2026-03-18 16:40:29', 'used'),
(2, 1, '634334', 'login_verification', '2026-03-22 16:15:37', '2026-03-22 16:25:37', '2026-03-22 16:15:58', 'used'),
(3, 1, '740867', 'login_verification', '2026-03-25 13:52:04', '2026-03-25 14:02:04', '2026-03-25 13:53:46', 'used'),
(4, 1, '497802', 'login_verification', '2026-04-05 20:39:27', '2026-04-05 20:49:27', '2026-04-05 20:40:39', 'used');

-- --------------------------------------------------------

--
-- Table structure for table `instructor_password_history`
--

CREATE TABLE `instructor_password_history` (
  `password_history_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `change_type` enum('reset') NOT NULL DEFAULT 'reset',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `maps_link` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructor_password_history`
--

INSERT INTO `instructor_password_history` (`password_history_id`, `instructor_id`, `change_type`, `ip_address`, `user_agent`, `latitude`, `longitude`, `city`, `region`, `country`, `maps_link`, `changed_at`) VALUES
(1, 1, 'reset', '::1', 'Mozilla/5.0', '10.698800', '122.541712', 'Altavas', 'Western Visayas', 'Philippines', 'https://www.google.com/maps?q=10.698800,122.541712', '2026-03-18 08:35:43'),
(2, 1, 'reset', '::1', 'Mozilla/5.0', NULL, NULL, 'Iloilo City', 'Western Visayas', 'Philippines', NULL, '2026-03-22 08:15:15');

-- --------------------------------------------------------

--
-- Table structure for table `instructor_ratings`
--

CREATE TABLE `instructor_ratings` (
  `rating_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `comment` text DEFAULT NULL,
  `rated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructor_ratings`
--

INSERT INTO `instructor_ratings` (`rating_id`, `appointment_id`, `student_id`, `instructor_id`, `rating`, `comment`, `rated_at`) VALUES
(1, 1, 27, 1, 5, 'Very patient and knowledgeable instructor. Highly recommended!', '2026-04-06 21:31:59');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `sender_type` enum('student','instructor') NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `receiver_type` enum('student','instructor') NOT NULL,
  `appointment_id` int(11) DEFAULT NULL COMMENT 'Optional: link to enrollment context',
  `body` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `sender_type`, `receiver_id`, `receiver_type`, `appointment_id`, `body`, `is_read`, `created_at`) VALUES
(1, 27, 'student', 1, 'instructor', 3, 'hi', 1, '2026-05-15 13:37:45'),
(2, 1, 'instructor', 27, 'student', NULL, 'op', 1, '2026-05-15 14:04:35'),
(3, 27, 'student', 1, 'instructor', 4, 'fff', 1, '2026-05-26 12:30:19'),
(4, 1, 'instructor', 27, 'student', NULL, 'gjgg', 1, '2026-05-26 12:38:51'),
(5, 27, 'student', 1, 'instructor', 4, 'ff', 1, '2026-05-26 12:39:03'),
(6, 1, 'instructor', 27, 'student', NULL, 'fkv', 1, '2026-05-26 13:19:05');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notif_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('student','instructor','admin') NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `recipient_target` enum('all_students','all_instructors','everyone','specific_student','specific_instructor') DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notif_id`, `user_id`, `user_type`, `message`, `type`, `recipient_target`, `is_read`, `created_at`) VALUES
(1, 1, 'instructor', 'New appointment request for TDC Face-to-Face starting 2026-03-28 from Carl Edciel.', 'booking_request', NULL, 1, '2026-03-27 03:28:43'),
(2, 27, 'student', 'Your appointment for TDC Face-to-Face has been APPROVED! TDC sessions are on Saturdays and Sundays.', 'appointment_approved', NULL, 1, '2026-03-27 11:28:57'),
(3, 1, 'instructor', 'New appointment request for TDC Face-to-Face starting 2026-04-19 from Kent John Canas.', 'booking_request', NULL, 1, '2026-04-03 10:53:28'),
(4, 28, 'student', 'Your appointment for TDC Face-to-Face has been APPROVED! TDC sessions are on Saturdays and Sundays.', 'appointment_approved', NULL, 1, '2026-04-03 18:54:03'),
(5, 27, 'student', 'Your TDC course is complete! Please rate your instructor.', 'course_completed', NULL, 1, '2026-04-06 20:43:24'),
(6, 27, 'student', 'Your TDC Certificate (TDC-2026-001) has been issued — Face-to-Face release. PDC courses are now unlocked.', 'certificate_issued', NULL, 1, '2026-04-06 20:45:00'),
(7, 1, 'instructor', 'New appointment request for TDC Online starting 2026-05-02 from A student.', 'booking_request', NULL, 0, '2026-04-28 06:27:23'),
(8, 27, 'student', 'Your appointment for TDC Online has been APPROVED!', 'appointment_approved', NULL, 1, '2026-04-29 02:34:54'),
(9, 27, 'student', 'Your course is complete! Please rate your instructor.', 'course_completed', NULL, 1, '2026-05-01 14:46:45'),
(10, 27, 'student', 'Your TDC Certificate has been issued online! PDC is now unlocked.', 'certificate_issued', NULL, 1, '2026-05-01 14:47:39'),
(11, 1, 'instructor', 'New appointment request for PDC Manual (Vehicle: Car) on 2026-05-15 from A student.', 'booking_request', NULL, 0, '2026-05-07 14:19:08'),
(12, 27, 'student', 'Your appointment for PDC Manual has been APPROVED!', 'appointment_approved', NULL, 1, '2026-05-07 14:19:19'),
(13, 27, 'student', 'Your course is complete! Please rate your instructor.', 'course_completed', NULL, 1, '2026-05-07 14:21:46'),
(14, 28, 'student', 'Your course is complete! Please rate your instructor.', 'course_completed', NULL, 0, '2026-05-07 14:24:41'),
(15, 1, 'instructor', 'New appointment request for PDC Manual (Vehicle: Car) on 2026-05-16 from A student.', 'booking_request', NULL, 0, '2026-05-07 14:34:39'),
(16, 27, 'student', 'Your PDC Certificate has been issued online! Congratulations!', 'certificate_issued', NULL, 1, '2026-05-07 14:40:53'),
(17, 1, 'instructor', '💬 Message from your student.', 'message', NULL, 0, '2026-05-15 13:37:45'),
(18, 27, 'student', '📢 Admin: fail kamo tanan', 'announcement', 'all_students', 1, '2026-05-15 14:04:03'),
(19, 28, 'student', '📢 Admin: fail kamo tanan', 'announcement', 'all_students', 0, '2026-05-15 14:04:03'),
(20, 27, 'student', '💬 Message from your instructor.', 'message', NULL, 1, '2026-05-15 14:04:35'),
(21, 1, 'instructor', 'New appointment request for PDC Manual on 2026-05-28 from Carl Edciel.', 'booking_request', NULL, 0, '2026-05-26 08:47:15'),
(22, 1, 'instructor', 'New appointment request for PDC Manual on 2026-05-28 from Carl Edciel.', 'booking_request', NULL, 0, '2026-05-26 12:29:06'),
(23, 27, 'student', '📢 Admin: cdcv', 'announcement', 'all_students', 1, '2026-05-26 12:29:58'),
(24, 28, 'student', '📢 Admin: cdcv', 'announcement', 'all_students', 0, '2026-05-26 12:29:58'),
(25, 1, 'instructor', '💬 Message from your student.', 'message', NULL, 0, '2026-05-26 12:30:19'),
(26, 27, 'student', '💬 Message from your instructor.', 'message', NULL, 0, '2026-05-26 12:38:51'),
(27, 1, 'instructor', '💬 Message from your student.', 'message', NULL, 0, '2026-05-26 12:39:03'),
(28, 27, 'student', '💬 Message from your instructor.', 'message', NULL, 0, '2026-05-26 13:19:05'),
(29, 1, 'instructor', 'New appointment request for PDC Manual on 2026-06-10 from Carl Edciel.', 'booking_request', NULL, 0, '2026-06-09 11:46:36'),
(30, 27, 'student', 'Your appointment for PDC Manual has been APPROVED!', 'appointment_approved', NULL, 0, '2026-06-09 11:46:51'),
(31, 27, 'student', 'Admin has assigned you to Instructor Carl for TDC Face-to-Face starting 2026-06-16. The appointment is already approved.', 'admin_assign', NULL, 0, '2026-06-15 05:07:22'),
(32, 1, 'instructor', 'Admin has assigned student Carl Edciel to your TDC Face-to-Face course starting 2026-06-16.', 'admin_assign', NULL, 0, '2026-06-15 05:07:22'),
(33, 1, 'instructor', 'Carl Edciel cancelled their TDC Face-to-Face appointment that was scheduled for 2026-06-16.', 'student_cancelled', NULL, 0, '2026-06-15 11:09:11'),
(34, 1, 'instructor', 'Carl Edciel cancelled their PDC Manual appointment that was scheduled for 2026-06-10.', 'student_cancelled', NULL, 0, '2026-06-16 10:49:59'),
(35, 1, 'instructor', 'New appointment request for PDC Manual on 2026-06-17 from Carl Edciel.', 'booking_request', NULL, 0, '2026-06-16 10:50:19'),
(36, 27, 'student', 'Your appointment for PDC Manual has been APPROVED!', 'appointment_approved', NULL, 0, '2026-06-16 10:50:39');

-- --------------------------------------------------------

--
-- Table structure for table `pdc_certificates`
--

CREATE TABLE `pdc_certificates` (
  `cert_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `issued_by` int(11) NOT NULL,
  `issued_at` datetime DEFAULT current_timestamp(),
  `cert_number` varchar(50) DEFAULT NULL,
  `delivery_method` enum('online','face_to_face') DEFAULT 'face_to_face',
  `cert_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pdc_certificates`
--

INSERT INTO `pdc_certificates` (`cert_id`, `student_id`, `appointment_id`, `issued_by`, `issued_at`, `cert_number`, `delivery_method`, `cert_image`) VALUES
(1, 27, 4, 1, '2026-05-07 22:40:53', '', 'online', 'pcert_27_1778164853.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `session_type` enum('TDC','PDC') NOT NULL DEFAULT 'TDC',
  `session_number` tinyint(3) NOT NULL DEFAULT 1,
  `student_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `session_date` date NOT NULL COMMENT 'For TDC must be Sat or Sun',
  `hours_covered` decimal(4,1) NOT NULL DEFAULT 1.0,
  `status` enum('scheduled','completed','cancelled','no_show') NOT NULL DEFAULT 'completed',
  `performance_rating` tinyint(1) DEFAULT NULL COMMENT '1-5 stars per session by instructor',
  `grade` tinyint(3) UNSIGNED DEFAULT NULL COMMENT '1-100 numeric grade for TDC sessions',
  `improvements` text DEFAULT NULL COMMENT 'Suggested improvements by instructor',
  `performance_notes` text DEFAULT NULL COMMENT 'Feedback and suggestions per session',
  `completed_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`session_id`, `appointment_id`, `session_type`, `session_number`, `student_id`, `instructor_id`, `session_date`, `hours_covered`, `status`, `performance_rating`, `grade`, `improvements`, `performance_notes`, `completed_at`, `created_at`) VALUES
(1, 1, 'TDC', 1, 27, 1, '2026-03-28', 3.0, 'completed', 4, NULL, NULL, 'Good focus. Needs more practice on road signs.', '2026-03-28 17:00:00', '2026-03-28 08:00:00'),
(2, 1, 'TDC', 2, 27, 1, '2026-03-29', 3.0, 'completed', 5, NULL, NULL, 'Excellent improvement. Very attentive.', '2026-03-29 17:00:00', '2026-03-29 08:00:00'),
(3, 1, 'TDC', 3, 27, 1, '2026-04-04', 3.0, 'completed', 4, NULL, NULL, 'Good performance. Minor errors on lane discipline.', '2026-04-04 17:00:00', '2026-04-04 08:00:00'),
(4, 1, 'TDC', 4, 27, 1, '2026-04-05', 3.0, 'completed', 5, NULL, NULL, 'Excellent. Ready for practical exam.', '2026-04-05 17:00:00', '2026-04-05 08:00:00'),
(5, 1, 'TDC', 5, 27, 1, '2026-04-06', 3.0, 'completed', 4, 90, 'review more in traffic lights', '', '2026-04-06 17:00:00', '2026-04-06 08:00:00'),
(6, 2, 'TDC', 1, 28, 1, '2026-04-19', 3.0, 'completed', 3, NULL, NULL, 'Decent start. Needs to review road signs chapter.', '2026-04-19 17:00:00', '2026-04-19 08:00:00'),
(7, 2, 'TDC', 2, 28, 1, '2026-04-20', 3.0, 'completed', 4, NULL, NULL, 'Improved. Better understanding of traffic rules.', '2026-04-20 17:00:00', '2026-04-20 08:00:00'),
(8, 3, 'TDC', 1, 27, 1, '2026-04-29', 8.0, 'completed', NULL, 90, '', '', NULL, '2026-04-29 02:36:06'),
(9, 3, 'TDC', 2, 27, 1, '2026-04-29', 7.0, 'completed', NULL, 86, 'jhshscbikyeg', 'ajbciew', NULL, '2026-05-01 14:46:45'),
(10, 4, 'PDC', 1, 27, 1, '2026-05-07', 8.0, 'completed', NULL, NULL, '', '', NULL, '2026-05-07 14:21:46'),
(11, 2, 'TDC', 3, 28, 1, '2026-05-09', 8.0, 'completed', NULL, 50, '', '', NULL, '2026-05-07 14:24:25'),
(12, 2, 'TDC', 4, 28, 1, '2026-05-07', 1.0, 'completed', NULL, 80, '', '', NULL, '2026-05-07 14:24:41');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `fullname` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `fb_link` varchar(255) DEFAULT NULL,
  `id_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `fullname`, `email`, `mobile`, `address`, `password_hash`, `is_verified`, `created_at`, `gender`, `fb_link`, `id_photo`) VALUES
(27, 'Carl Edciel', 'cara.abrot.ui@phinmaed.com', '09957320690', '', '$2y$10$837MiGQAS1yef8dMf1eEwe7DC2gxO.JBLDHn67.iaFhrOFYIddGni', 1, '2026-03-22 06:46:56', 'Male', '', 'sid_27_1778206183.jpg'),
(28, 'Kent John Canas', 'canaskentjohn@gmail.com', NULL, NULL, '$2y$10$vXjVMSjdtietwHh1CBM3JePsBtopD1X0NRof4bHRW8miMYAfLh6KG', 1, '2026-03-27 02:07:36', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_lockout_history`
--

CREATE TABLE `student_lockout_history` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `device_info` text NOT NULL,
  `lockout_type` enum('1min','1hr') NOT NULL,
  `lockout_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_login_history`
--

CREATE TABLE `student_login_history` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `device_info` text NOT NULL,
  `status` enum('success','failed') NOT NULL,
  `login_datetime` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_login_history`
--

INSERT INTO `student_login_history` (`id`, `student_id`, `ip_address`, `device_info`, `status`, `login_datetime`) VALUES
(1, 27, '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 145.0.0.0', 'success', '2026-03-22 14:50:04'),
(2, 27, '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 146.0.0.0', 'success', '2026-03-22 16:18:45'),
(3, 27, '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 146.0.0.0', 'success', '2026-03-25 14:09:38'),
(4, 27, '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 146.0.0.0', 'success', '2026-03-26 15:45:06'),
(5, 28, '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 146.0.0.0', 'success', '2026-03-27 10:13:25'),
(6, 27, '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 146.0.0.0', 'success', '2026-04-05 20:42:26');

-- --------------------------------------------------------

--
-- Table structure for table `student_otp_history`
--

CREATE TABLE `student_otp_history` (
  `otp_history_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `otp_type` enum('signup','login','password_reset') NOT NULL,
  `otp_expiry` datetime NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_otp_history`
--

INSERT INTO `student_otp_history` (`otp_history_id`, `student_id`, `otp`, `otp_type`, `otp_expiry`, `is_used`, `created_at`) VALUES
(1, 27, '684753', 'signup', '2026-03-22 14:56:56', 1, '2026-03-22 06:46:56'),
(2, 27, '345846', 'login', '2026-03-22 14:59:34', 1, '2026-03-22 06:49:34'),
(3, 27, '064477', 'login', '2026-03-25 14:17:25', 1, '2026-03-25 06:07:25'),
(4, 27, '848765', 'login', '2026-03-26 21:55:10', 1, '2026-03-26 13:45:10'),
(5, 28, '786312', 'signup', '2026-03-27 10:17:36', 1, '2026-03-27 02:07:36'),
(6, 28, '209342', 'login', '2026-03-27 10:22:54', 1, '2026-03-27 02:12:54'),
(7, 27, '365249', 'login', '2026-04-05 20:51:52', 1, '2026-04-05 12:41:52');

-- --------------------------------------------------------

--
-- Table structure for table `student_password_history`
--

CREATE TABLE `student_password_history` (
  `history_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `change_type` enum('reset','change') NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `device_info` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tdc_certificates`
--

CREATE TABLE `tdc_certificates` (
  `cert_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `issued_by` int(11) NOT NULL,
  `issued_at` datetime NOT NULL DEFAULT current_timestamp(),
  `cert_number` varchar(50) DEFAULT NULL,
  `delivery_method` enum('online','face_to_face') NOT NULL DEFAULT 'face_to_face',
  `cert_image` varchar(255) DEFAULT NULL COMMENT 'Uploaded certificate image for online delivery',
  `cert_type` enum('online','face_to_face') NOT NULL DEFAULT 'face_to_face'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tdc_certificates`
--

INSERT INTO `tdc_certificates` (`cert_id`, `student_id`, `appointment_id`, `issued_by`, `issued_at`, `cert_number`, `delivery_method`, `cert_image`, `cert_type`) VALUES
(1, 27, 1, 1, '2026-04-06 18:00:00', 'TDC-2026-001', 'face_to_face', NULL, 'face_to_face'),
(2, 27, 3, 1, '2026-05-01 22:47:39', '', 'online', 'tcert_27_1777646859.jpg', 'face_to_face');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admin_login_history`
--
ALTER TABLE `admin_login_history`
  ADD PRIMARY KEY (`admin_login_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `admin_otp_history`
--
ALTER TABLE `admin_otp_history`
  ADD PRIMARY KEY (`admin_otp_history_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `fk_appt_student` (`student_id`),
  ADD KEY `fk_appt_instructor` (`instructor_id`),
  ADD KEY `fk_appt_course` (`course_id`);

--
-- Indexes for table `course_schedules`
--
ALTER TABLE `course_schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD UNIQUE KEY `unique_course_date` (`course_id`,`schedule_date`),
  ADD KEY `fk_cs_course` (`course_id`),
  ADD KEY `fk_cs_instructor` (`instructor_id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`instructor_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `instructor_courses`
--
ALTER TABLE `instructor_courses`
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `fk_ic_instructor` (`instructor_id`);

--
-- Indexes for table `instructor_login_history`
--
ALTER TABLE `instructor_login_history`
  ADD PRIMARY KEY (`instructor_login_id`),
  ADD KEY `fk_instructor_login_history_instructor` (`instructor_id`);

--
-- Indexes for table `instructor_otp_history`
--
ALTER TABLE `instructor_otp_history`
  ADD PRIMARY KEY (`instructor_otp_history_id`),
  ADD KEY `fk_instructor_otp_history_instructor` (`instructor_id`);

--
-- Indexes for table `instructor_password_history`
--
ALTER TABLE `instructor_password_history`
  ADD PRIMARY KEY (`password_history_id`),
  ADD KEY `fk_instructor_password_history` (`instructor_id`);

--
-- Indexes for table `instructor_ratings`
--
ALTER TABLE `instructor_ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD UNIQUE KEY `unique_instr_rating` (`appointment_id`,`student_id`),
  ADD KEY `fk_ir_student` (`student_id`),
  ADD KEY `fk_ir_instructor` (`instructor_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `idx_msg_receiver` (`receiver_id`,`receiver_type`),
  ADD KEY `idx_msg_sender` (`sender_id`,`sender_type`),
  ADD KEY `fk_msg_appt` (`appointment_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notif_id`),
  ADD KEY `idx_notif_user` (`user_id`,`user_type`);

--
-- Indexes for table `pdc_certificates`
--
ALTER TABLE `pdc_certificates`
  ADD PRIMARY KEY (`cert_id`),
  ADD UNIQUE KEY `appointment_id` (`appointment_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `issued_by` (`issued_by`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `fk_sess_appointment` (`appointment_id`),
  ADD KEY `fk_sess_student` (`student_id`),
  ADD KEY `fk_sess_instructor` (`instructor_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `student_lockout_history`
--
ALTER TABLE `student_lockout_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `student_login_history`
--
ALTER TABLE `student_login_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `student_otp_history`
--
ALTER TABLE `student_otp_history`
  ADD PRIMARY KEY (`otp_history_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `student_password_history`
--
ALTER TABLE `student_password_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `fk_student_password_history_student` (`student_id`);

--
-- Indexes for table `tdc_certificates`
--
ALTER TABLE `tdc_certificates`
  ADD PRIMARY KEY (`cert_id`),
  ADD UNIQUE KEY `unique_student_cert` (`student_id`,`appointment_id`),
  ADD KEY `fk_cert_appointment` (`appointment_id`),
  ADD KEY `fk_cert_instructor` (`issued_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_login_history`
--
ALTER TABLE `admin_login_history`
  MODIFY `admin_login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `admin_otp_history`
--
ALTER TABLE `admin_otp_history`
  MODIFY `admin_otp_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `course_schedules`
--
ALTER TABLE `course_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `instructor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `instructor_courses`
--
ALTER TABLE `instructor_courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `instructor_login_history`
--
ALTER TABLE `instructor_login_history`
  MODIFY `instructor_login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `instructor_otp_history`
--
ALTER TABLE `instructor_otp_history`
  MODIFY `instructor_otp_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `instructor_password_history`
--
ALTER TABLE `instructor_password_history`
  MODIFY `password_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `instructor_ratings`
--
ALTER TABLE `instructor_ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notif_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `pdc_certificates`
--
ALTER TABLE `pdc_certificates`
  MODIFY `cert_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `student_lockout_history`
--
ALTER TABLE `student_lockout_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_login_history`
--
ALTER TABLE `student_login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `student_otp_history`
--
ALTER TABLE `student_otp_history`
  MODIFY `otp_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `student_password_history`
--
ALTER TABLE `student_password_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tdc_certificates`
--
ALTER TABLE `tdc_certificates`
  MODIFY `cert_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_login_history`
--
ALTER TABLE `admin_login_history`
  ADD CONSTRAINT `admin_login_history_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_otp_history`
--
ALTER TABLE `admin_otp_history`
  ADD CONSTRAINT `admin_otp_history_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`) ON DELETE CASCADE;

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `fk_appt_course` FOREIGN KEY (`course_id`) REFERENCES `instructor_courses` (`course_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_appt_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`instructor_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_appt_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `course_schedules`
--
ALTER TABLE `course_schedules`
  ADD CONSTRAINT `fk_cs_course` FOREIGN KEY (`course_id`) REFERENCES `instructor_courses` (`course_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cs_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`instructor_id`) ON DELETE CASCADE;

--
-- Constraints for table `instructor_courses`
--
ALTER TABLE `instructor_courses`
  ADD CONSTRAINT `fk_ic_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`instructor_id`) ON DELETE CASCADE;

--
-- Constraints for table `instructor_login_history`
--
ALTER TABLE `instructor_login_history`
  ADD CONSTRAINT `fk_instructor_login_history_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`instructor_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `instructor_otp_history`
--
ALTER TABLE `instructor_otp_history`
  ADD CONSTRAINT `fk_instructor_otp_history_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`instructor_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `instructor_password_history`
--
ALTER TABLE `instructor_password_history`
  ADD CONSTRAINT `fk_instructor_password_history` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`instructor_id`) ON DELETE CASCADE;

--
-- Constraints for table `instructor_ratings`
--
ALTER TABLE `instructor_ratings`
  ADD CONSTRAINT `fk_ir_appointment` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ir_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`instructor_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ir_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_msg_appt` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`) ON DELETE SET NULL;

--
-- Constraints for table `pdc_certificates`
--
ALTER TABLE `pdc_certificates`
  ADD CONSTRAINT `pdc_certificates_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pdc_certificates_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pdc_certificates_ibfk_3` FOREIGN KEY (`issued_by`) REFERENCES `instructors` (`instructor_id`) ON DELETE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `fk_sess_appointment` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sess_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`instructor_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sess_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_lockout_history`
--
ALTER TABLE `student_lockout_history`
  ADD CONSTRAINT `student_lockout_history_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_login_history`
--
ALTER TABLE `student_login_history`
  ADD CONSTRAINT `student_login_history_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_otp_history`
--
ALTER TABLE `student_otp_history`
  ADD CONSTRAINT `fk_student_otp_history_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_password_history`
--
ALTER TABLE `student_password_history`
  ADD CONSTRAINT `fk_student_password_history_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tdc_certificates`
--
ALTER TABLE `tdc_certificates`
  ADD CONSTRAINT `fk_cert_appointment` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cert_instructor` FOREIGN KEY (`issued_by`) REFERENCES `instructors` (`instructor_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cert_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
