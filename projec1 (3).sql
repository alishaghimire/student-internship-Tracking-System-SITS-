-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2026 at 06:44 AM
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
-- Database: `projec1`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super','admin','moderator') NOT NULL,
  `department` varchar(100) NOT NULL,
  `permissions` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_active` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`admin_id`, `name`, `email`, `username`, `password`, `role`, `department`, `permissions`, `created_at`, `last_active`, `status`) VALUES
(1, 'Alisha', 'alisha@gmail.com', 'alisha', '$2y$10$MbZEFEfOD1ZtNnwaTCQpgubre0fdob1TDM1W4Uu6HfZ1r/WaKu1UG', 'super', 'Engineering', 'all,settings,reports,users,approval', '2026-01-05 06:20:07', '2026-01-08 03:51:46', 'active'),
(2, 'Alisha', 'bbb@gmail.com', 'alishayojana', '$2y$10$Y7IWtoqxzlTJ6N9Csmt5pOKg/oubTaR4FikLjIOIxsAasCOEay4Xq', 'super', 'Engineering', 'all,settings,reports,users,approval', '2026-01-05 06:20:38', NULL, 'inactive'),
(3, 'Alina khatri', 'alina43@gmail.com', 'alina', '$2y$10$kXNmh1A28pvvlH44upxnKOx6o2EbwLfWUW.Lv/CFpckqj0K2QdRzy', 'admin', 'Engineering', 'all,users,settings,approval,reports', '2026-01-05 07:43:48', '2026-01-05 15:22:01', 'active'),
(4, 'Binisha Luitel', 'binisha@gmail.com', 'binisha', '$2y$10$cTWLDddwjfLDlEYau.By9.77BAhV7V.eHMWIU0jpEwGMPMClkr0oC', 'admin', 'Design', 'reports,approval', '2026-01-05 08:22:28', '2026-01-08 05:18:35', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `application_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `status` enum('pending','reviewed','shortlisted','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`application_id`, `post_id`, `company_id`, `student_id`, `status`, `created_at`) VALUES
(2, 6, 8, 13, '', '2026-01-04 12:57:43'),
(4, 10, 10, 14, 'shortlisted', '2026-01-06 09:37:45'),
(5, 10, 10, 13, 'shortlisted', '2026-01-07 08:37:38');

-- --------------------------------------------------------

--
-- Table structure for table `application_step1`
--

CREATE TABLE `application_step1` (
  `step1_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `dob` date NOT NULL,
  `linkedin` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_step1`
--

INSERT INTO `application_step1` (`step1_id`, `application_id`, `full_name`, `email`, `phone`, `dob`, `linkedin`) VALUES
(2, 2, 'Hari karki', 'hari@gmail.com', '9807654321', '2026-01-10', 'http://localhost/phpmyadmin/index.php?route=/table/structure&db=projec1&table=position_responsibilities'),
(4, 4, 'Alisha Khatri', 'alisha@gmail.com', '9807362342', '2006-03-09', 'https://www.linkedin.com/'),
(5, 5, 'Hari karki', 'hari45@gmail.com', '9876543456', '2010-05-04', 'https://www.linkedin.com/');

-- --------------------------------------------------------

--
-- Table structure for table `application_step2`
--

CREATE TABLE `application_step2` (
  `step2_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `education_level` varchar(50) DEFAULT NULL,
  `university` varchar(150) DEFAULT NULL,
  `graduation_year` int(11) DEFAULT NULL,
  `experience_level` varchar(50) DEFAULT NULL,
  `availability` varchar(50) DEFAULT NULL,
  `cover_letter` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_step2`
--

INSERT INTO `application_step2` (`step2_id`, `application_id`, `education_level`, `university`, `graduation_year`, `experience_level`, `availability`, `cover_letter`) VALUES
(2, 2, 'Bachelor', 'TU University', 2036, 'Beginner', 'Within 1 Week', 'jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj'),
(4, 4, 'High School', 'Trivhuvan University', 2025, 'Beginner', 'Within 1 Week', 'Alisha \r\nKapan, Kthamndu  \r\nKathamndu, Bagmati Province  \r\nalisha@gmail.com  \r\n9876543456  \r\n\r\n\r\nHiring Manager  \r\nNepal Doorsanchar Company Limited (Nepal Telecom)  \r\nKathmandu, Bagmati Province, Nepal  \r\n\r\nDear Hiring Manager,\r\n\r\nI am writing to express my interest in the Software Development Intern position at Nepal Doorsanchar Company Limited. As a current student pursuing [Your Degree, e.g., Bachelor of Information Technology/Computer Science] at [Your College Name], I am eager to apply my academic knowledge and technical skills in a practical, professional environment.\r\n\r\nThrough my coursework, I have gained a strong foundation in programming languages such as PHP, JavaScript, and SQL, along with experience in web development and database management. I have also worked on academic projects involving dynamic dashboards, multi-step forms, and secure data handling, which have strengthened my problem-solving abilities and attention to detail. These experiences have prepared me to contribute effectively to your software development team.\r\n\r\nI am particularly drawn to this internship because of Nepal Telecom’s reputation for innovation and its role in advancing technology infrastructure in Nepal. I am confident that my enthusiasm for learning, combined with my technical skills and collaborative mindset, will allow me to add value to your projects while gaining invaluable industry experience.\r\n\r\nI would welcome the opportunity to discuss how my background aligns with your needs. Thank you for considering my application. I look forward to the possibility of contributing to Nepal Telecom’s mission while further developing my skills as a software developer.\r\n\r\nSincerely,  \r\nAlisha khatri'),
(5, 5, 'Diploma', 'Bal Uddhar Secondary school', 2024, 'Intermediate', 'Within 1 Week', 'Dear Hiring Manager,\r\n\r\nI am writing to express my interest in the Software Development Internship at [Company Name]. As a Computer Science student with hands-on experience in PHP, JavaScript, and database design, I am eager to apply my technical skills to real-world projects while learning from your innovative team.\r\n\r\nIn my academic and personal projects, I have built modular dashboards and multi-step forms that emphasize user trust, data integrity, and seamless UX transitions. I thrive on solving architectural challenges and debugging complex code, and I am excited to bring this persistence and problem-solving mindset to [Company Name].\r\n\r\nI am particularly drawn to your commitment to [specific company value or project], and I believe my background in designing secure, user-centric systems aligns well with your mission. I am confident that this internship will allow me to contribute meaningfully while expanding my expertise in scalable software solutions.\r\n\r\nThank you for considering my application. I look forward to the opportunity to discuss how my skills and enthusiasm can support your team.\r\n\r\nSincerely,  \r\nHari');

-- --------------------------------------------------------

--
-- Table structure for table `application_step3`
--

CREATE TABLE `application_step3` (
  `step3_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `resume_path` varchar(255) DEFAULT NULL,
  `portfolio_path` varchar(255) DEFAULT NULL,
  `portfolio_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_step3`
--

INSERT INTO `application_step3` (`step3_id`, `application_id`, `resume_path`, `portfolio_path`, `portfolio_url`) VALUES
(2, 2, 'uploads/resume_1767531527.pdf', 'uploads/portfolio_1767531527.pdf', 'http://localhost/project/html/Student/applypage3.php'),
(4, 4, 'uploads/resume_1767692535.pdf', NULL, ''),
(5, 5, 'uploads/resume_1767775211.pdf', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `colleges`
--

CREATE TABLE `colleges` (
  `college_id` int(11) NOT NULL,
  `college_name` varchar(255) NOT NULL,
  `college_type` enum('Public','Private','Autonomous') NOT NULL,
  `affiliated_university` varchar(255) NOT NULL,
  `registration_number` varchar(100) NOT NULL,
  `accreditation_level` varchar(5) DEFAULT NULL,
  `established_year` year(4) NOT NULL,
  `province` varchar(100) NOT NULL,
  `district` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `full_address` text NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `authorized_name` varchar(150) NOT NULL,
  `designation` enum('Placement Officer','HOD','Principal') NOT NULL,
  `authorized_email` varchar(150) NOT NULL,
  `authorized_phone` varchar(20) NOT NULL,
  `alternate_phone` varchar(20) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `colleges`
--

INSERT INTO `colleges` (`college_id`, `college_name`, `college_type`, `affiliated_university`, `registration_number`, `accreditation_level`, `established_year`, `province`, `district`, `city`, `full_address`, `website`, `authorized_name`, `designation`, `authorized_email`, `authorized_phone`, `alternate_phone`, `username`, `password`, `status`, `created_at`) VALUES
(11, 'Bal Uddhar Seconday School', 'Public', 'NEB', 'RED789', 'A+', '2020', 'Kathmandu', '0', '10', 'ssssssssssssssssssssssssssssssssssss', 'https://www.youtube.com/', 'Alisha Khatri', 'Principal', 'alisha@gmail.com', '9876543290', '', 'alisha', '$2y$10$aaJ211d/2i5vXQtkCYfcfeVv7vvnn8lyJwCK7UyhNgRqzI40/78Xm', 'Approved', '2025-12-21 02:48:32'),
(14, 'Thames International College', 'Private', 'Tribhuvan Universit', 'REG678654', 'Accre', '2009', 'Bagmati Province', '0', 'Kathmandu Metropolitan City', 'Surya Bikram Gyawali Marg, Old Baneshwor, Kathmandu, Nepal', 'https://www.thamescollege.edu.np/', 'Rajesh Shrestha', 'Principal', 'info@thamescollege.edu.n', '9887654321', '+977‑1‑4426009', 'thamescollege', '$2y$10$LcOXl/o2PCgSeuXBZ9C8wOi1exLCf4gTbqsu/nKAc80lyajI4pPAq', 'Approved', '2026-01-06 09:13:05'),
(16, 'St Xavier College', 'Private', 'Tribhuvan Universit', 'REG6785', 'Accre', '1988', 'Bagmati Province', '0', 'Kathmandu Metropolitan City', 'Maitighar, Kathmandu', 'https://sxc.edu.np/', 'George Pattery', 'Principal', 'info@sxc.edu.np', '9876543212', '', 'xavier', '$2y$10$8vCUrRhN1RHDHWLkD0UNQObejYWXqMN4VYJ0VY9uaLPFu4zaUQeHC', 'Approved', '2026-01-06 09:24:06'),
(18, 'Himalayan College of Computer Science', 'Private', 'TU', 'HCOOCS12342', 'A-', '2012', 'Kathmandu', '0', 'kathmandu', 'kapan kathmandu', 'https://www.hcoe.edu.np/', 'alisha khatri', 'HOD', 'himalayn@gmail.com', '9807362342', '', 'himalayan', '$2y$10$JEuoZ05GR1gCeGpA7knaMO3KXgqL1xHtXRtBBF4SqafMw6QWtr5u.', 'Approved', '2026-02-08 15:08:48');

-- --------------------------------------------------------

--
-- Table structure for table `company_registration`
--

CREATE TABLE `company_registration` (
  `company_id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `industry` varchar(100) NOT NULL,
  `registration_number` varchar(100) NOT NULL,
  `tax_id` varchar(100) NOT NULL,
  `established_year` int(11) NOT NULL,
  `num_employees` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `city_state` varchar(255) NOT NULL,
  `full_address` text DEFAULT NULL,
  `company_description` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('pending','verified','approved','rejected') DEFAULT 'pending',
  `submitted_at` datetime DEFAULT current_timestamp(),
  `approved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company_registration`
--

INSERT INTO `company_registration` (`company_id`, `company_name`, `industry`, `registration_number`, `tax_id`, `established_year`, `num_employees`, `email`, `phone`, `website`, `city_state`, `full_address`, `company_description`, `password`, `status`, `submitted_at`, `approved_at`) VALUES
(8, 'Opp', 'tech', 'REG567', 'TAX45', 2012, '51-200', 'opp@gmail.com', '9876543212', NULL, 'Kathmandu', NULL, NULL, '$2y$10$f3Gl6LE3DrdxhlCuipZp/e1k.H9oosibP5cy1Vfa7M/vTXOFOsZSS', 'approved', '2026-01-04 18:12:21', NULL),
(10, 'Nepal Doorsanchar Company Limited', 'consumer', 'REG31806061', 'TAX500010000', 2004, '51-200', 'nepaltelecom@gmail.com', '9812343245', NULL, 'Kathmandu, Bagmati Province', NULL, NULL, '$2y$10$8WX.EQqltX0XXmXkSxivK.o/9DDjwXsxn0LlY7L18cTs5IKaxWLbK', 'approved', '2026-01-06 14:12:54', NULL),
(11, 'Tech Solutions Pvt. Ltd.', 'tech', 'REG67890', 'TAX5678', 2012, '11-50', 'yojana@gmail.com', '9807362342', NULL, 'Kathmandu', NULL, NULL, '$2y$10$kvocxR.Im1eI9FiHnVKt1uCzGziK5avpGBZjqjzXicoceCa5xHsZO', 'approved', '2026-01-06 18:03:17', NULL),
(12, 'NexaSoft Solutions', 'tech', 'NEX12345', 'TAX56785', 2021, '11-50', 'nexasoftsoln@gmail.com', '9812342134', NULL, 'Biratnagar, Nepal', NULL, NULL, '$2y$10$wIgaTE0G/DtR4Ssgdoe3IutxmA51WkoHX5jp/o0x8EvYf6my5Nium', 'approved', '2026-02-08 20:33:05', NULL),
(13, 'CodeWave Systems', 'tech', 'COD123432', 'TAX45324', 2013, '51-200', 'codewave@gmail.com', '9876543456', NULL, 'Bhaktapur , Nepal', NULL, NULL, '$2y$10$aiQ97dnktbcwyvboRX0sK.8.oCP0QPGXxiCXTS59IsgakG08LbnT6', 'approved', '2026-02-08 20:38:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `company_settings`
--

CREATE TABLE `company_settings` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `theme_bg` varchar(20) DEFAULT '#f4f6f8',
  `theme_font` varchar(50) DEFAULT 'Arial',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_details`
--

CREATE TABLE `contact_details` (
  `contact_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `altPhone` varchar(20) DEFAULT NULL,
  `country` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `fullAddress` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_details`
--

INSERT INTO `contact_details` (`contact_id`, `student_id`, `email`, `phone`, `altPhone`, `country`, `state`, `city`, `fullAddress`, `created_at`, `updated_at`) VALUES
(3, 14, 'alisha@gmail.com', '9807362342', '', 'Nepal', 'Kathmandu', 'kathmandu', 'kapan kathmandu', '2026-01-06 09:34:08', '2026-01-06 09:34:08');

-- --------------------------------------------------------

--
-- Table structure for table `education_details`
--

CREATE TABLE `education_details` (
  `edu_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `level` varchar(50) NOT NULL,
  `major` varchar(150) NOT NULL,
  `institution` varchar(150) NOT NULL,
  `university` varchar(150) NOT NULL,
  `startYear` int(4) NOT NULL,
  `endYear` int(4) NOT NULL,
  `grade` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `education_details`
--

INSERT INTO `education_details` (`edu_id`, `student_id`, `level`, `major`, `institution`, `university`, `startYear`, `endYear`, `grade`, `created_at`, `updated_at`) VALUES
(3, 14, 'High School', 'Computer Engineering', ' Thames International College', 'Trivhuvan University', 2022, 2024, '3.45', '2026-01-06 09:35:16', '2026-01-06 09:35:16');

-- --------------------------------------------------------

--
-- Table structure for table `interviews`
--

CREATE TABLE `interviews` (
  `id` int(11) NOT NULL,
  `candidate_name` varchar(100) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `interview_date` date DEFAULT NULL,
  `interview_time` time DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `interview_type` enum('Video','Phone','In-Person') DEFAULT NULL,
  `interviewer` varchar(100) DEFAULT NULL,
  `meeting_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `application_id` int(11) NOT NULL,
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interviews`
--

INSERT INTO `interviews` (`id`, `candidate_name`, `role`, `interview_date`, `interview_time`, `duration`, `interview_type`, `interviewer`, `meeting_link`, `created_at`, `application_id`, `location`) VALUES
(7, 'Hari karki', 'Teacher', '2026-01-10', '16:20:00', 20, 'In-Person', 'job interview ', '', '2026-01-04 14:39:39', 2, 'kapan, Kathmandu'),
(8, 'Alisha Khatri', 'Software Development Intern', '2026-01-30', '10:00:00', 20, 'In-Person', 'Hari Thapa', '', '2026-01-06 09:53:36', 4, 'chabil,kathmandu'),
(9, 'Alisha Khatri', 'Software Development Intern', '2026-02-01', '11:20:00', 30, 'In-Person', 'Ram Thapa', '', '2026-01-07 08:41:33', 4, 'Kapan,Kathmandu');

-- --------------------------------------------------------

--
-- Table structure for table `logbook_entries`
--

CREATE TABLE `logbook_entries` (
  `entry_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `title` varchar(255) NOT NULL,
  `entry_date` date NOT NULL,
  `hours` decimal(5,2) NOT NULL,
  `location` varchar(150) NOT NULL,
  `supervisor` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approval_status` enum('pending','approved') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logbook_entries`
--

INSERT INTO `logbook_entries` (`entry_id`, `student_id`, `full_name`, `title`, `entry_date`, `hours`, `location`, `supervisor`, `created_at`, `approval_status`) VALUES
(3, 13, 'Hari karki', 'Js implementation', '0000-00-00', 8.00, 'Kritipur', 'Shidartha', '2026-01-06 02:20:15', 'approved'),
(4, 13, 'Hari karki', 'Hi', '2026-01-09', 43.00, 'Kapan', 'Shidartha', '2026-01-06 02:24:21', 'approved'),
(5, 14, 'Alisha Khatri', 'API Integration and Bug Fixing', '2026-01-31', 8.00, 'Chabil kathmandu', 'Uday Raj Karki', '2026-01-06 12:01:37', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `logbook_learnings`
--

CREATE TABLE `logbook_learnings` (
  `learning_id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `learning_text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logbook_learnings`
--

INSERT INTO `logbook_learnings` (`learning_id`, `entry_id`, `learning_text`) VALUES
(3, 3, 'learn'),
(4, 4, 'learn'),
(5, 5, 'API Development & Integration'),
(6, 5, 'Debugging & Validation'),
(7, 5, 'Frontend–Backend Collaboration');

-- --------------------------------------------------------

--
-- Table structure for table `logbook_tasks`
--

CREATE TABLE `logbook_tasks` (
  `task_id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `task_text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logbook_tasks`
--

INSERT INTO `logbook_tasks` (`task_id`, `entry_id`, `task_text`) VALUES
(3, 3, 'DataBase Connection'),
(4, 4, 'takes note'),
(5, 5, 'Implemented REST API endpoints for user authentication and data retrieval.'),
(6, 5, 'Debugged issues in the internship portal’s login form, ensuring proper validation for email and password fields'),
(7, 5, 'Collaborated with the frontend team to integrate AJAX calls for dynamic dashboard updates.'),
(8, 5, 'Wrote unit tests to validate database queries and ensure data integrity across multiple tables.');

-- --------------------------------------------------------

--
-- Table structure for table `position_responsibilities`
--

CREATE TABLE `position_responsibilities` (
  `id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `responsibility` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `position_responsibilities`
--

INSERT INTO `position_responsibilities` (`id`, `position_id`, `responsibility`) VALUES
(6, 6, 'teach'),
(9, 9, 'Support daily network monitoring and reporting.'),
(10, 9, 'Assist in troubleshooting connectivity issues.'),
(11, 9, 'Assist in troubleshooting connectivity issues.'),
(12, 9, 'Assist in troubleshooting connectivity issues.'),
(13, 10, 'Assist in developing and testing web applications.'),
(14, 10, 'Write clean, maintainable code under supervision.'),
(15, 10, 'Support integration of backend APIs with frontend dashboards.'),
(16, 10, 'Document workflows and contribute to team meetings'),
(17, 11, 'Assist in developing and maintaining web applications'),
(18, 11, 'Work with HTML, CSS, JavaScript, and PHP'),
(19, 11, 'Debug and test web applications'),
(20, 11, 'Collaborate with the development team on real projects'),
(21, 12, 'Design responsive web pages'),
(22, 12, 'Work with HTML, CSS, JavaScript'),
(23, 12, 'Assist in UI/UX improvements'),
(24, 12, 'Test and optimize frontend performance'),
(25, 13, 'Enter and verify data in the system'),
(26, 13, 'Assist in documentation'),
(27, 13, 'Support internal teams');

-- --------------------------------------------------------

--
-- Table structure for table `position_skills`
--

CREATE TABLE `position_skills` (
  `id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `skill` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `position_skills`
--

INSERT INTO `position_skills` (`id`, `position_id`, `skill`) VALUES
(6, 6, 'study'),
(9, 9, 'Basic knowledge of networking (TCP/IP, DNS, routing).'),
(10, 9, 'Familiarity with Cisco/Juniper devices.'),
(11, 9, 'Strong analytical and problem‑solving skills.'),
(12, 9, 'Good communication and teamwork abilities.'),
(13, 10, 'Basic knowledge of PHP, JavaScript, and SQL.'),
(14, 10, 'Familiarity with version control (Git).'),
(15, 10, 'Strong problem‑solving and debugging skills.'),
(16, 10, 'Ability to work collaboratively in a team environment.'),
(17, 11, 'Basic knowledge of HTML, CSS, and JavaScript'),
(18, 11, 'Familiarity with PHP and MySQL'),
(19, 11, 'Understanding of web development concepts'),
(20, 11, 'Willingness to learn and adapt'),
(21, 12, 'Basic knowledge of HTML, CSS, and JavaScript'),
(22, 12, 'Test and optimize frontend performance'),
(23, 12, 'Eye for design'),
(24, 13, 'Basic computer skills'),
(25, 13, 'MS Word / Excel'),
(26, 13, 'Attention to detail');

-- --------------------------------------------------------

--
-- Table structure for table `postposition`
--

CREATE TABLE `postposition` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `department` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `type` enum('full-time','part-time','remote') NOT NULL,
  `stipend` decimal(10,2) NOT NULL,
  `duration` varchar(255) NOT NULL,
  `deadline` date NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `company_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `postposition`
--

INSERT INTO `postposition` (`id`, `title`, `company_name`, `department`, `location`, `type`, `stipend`, `duration`, `deadline`, `description`, `created_at`, `company_id`) VALUES
(6, 'Teacher', 'Opp', 'Computer', 'kapan', 'full-time', 30000.00, '7 month', '2026-01-16', 'sjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj', '2026-01-04 12:56:30', 8),
(9, 'Network Engineering Intern', 'Nepal Doorsanchar Company Limited', 'Information Technology / Network Operations', 'Kathmandu, Bagmati Province, Nepal', 'part-time', 12000.00, '3 months', '2026-01-23', 'Assist the network operations team in monitoring, troubleshooting, and maintaining telecom infrastructure. Gain hands‑on experience with routers, switches, and fiber optic systems. Exposure to enterprise‑level IT systems and telecom protocols.', '2026-01-06 08:33:06', 10),
(10, 'Software Development Intern', 'Nepal Doorsanchar Company Limited', 'Information Technology / Software Solutions', 'Kathmandu, Bagmati Province, Nepal', 'remote', 15000.00, '4 months', '2026-01-30', 'Work with the software development team to design, test, and deploy internal applications that support telecom operations. Gain hands‑on experience with PHP, JavaScript, and database systems while contributing to real projects.', '2026-01-06 09:03:40', 10),
(11, 'Web Development Intern', 'CodeWave Systems', 'Technology', 'Bhaktapur, Nepal', 'part-time', 300000.00, '3 months', '2026-02-17', 'CodeWave Systems is looking for enthusiastic and motivated students to join our team as Web Development Interns. Interns will work closely with experienced developers and gain hands-on experience in building modern web applications.', '2026-02-08 14:57:48', 13),
(12, 'Frontend Developer Intern', 'CodeWave Systems', 'Technology', 'Bhaktapur, Nepal', 'full-time', 300000.00, '3 months', '2026-02-17', 'CodeWave Systems is seeking creative Frontend Developer Interns who are passionate about UI and user experience.', '2026-02-08 15:00:57', 13),
(13, 'Data Entry & Support Intern', 'CodeWave Systems', 'Technology', 'Bhaktapur, Nepal', 'part-time', 20000.00, '3 months', '2026-02-17', 'Ideal for beginners, this role helps students understand real-world data handling and system support.', '2026-02-08 15:02:59', 13);

-- --------------------------------------------------------

--
-- Table structure for table `review_notes`
--

CREATE TABLE `review_notes` (
  `note_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `note_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review_notes`
--

INSERT INTO `review_notes` (`note_id`, `application_id`, `company_id`, `note_text`, `created_at`) VALUES
(10, 4, 10, 'hi thi is', '2026-01-07 02:22:43');

-- --------------------------------------------------------

--
-- Table structure for table `students_registrtaion`
--

CREATE TABLE `students_registrtaion` (
  `student_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `student_code` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `major` varchar(100) NOT NULL,
  `college_id` int(11) NOT NULL,
  `college_name` varchar(150) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students_registrtaion`
--

INSERT INTO `students_registrtaion` (`student_id`, `full_name`, `student_code`, `email`, `phone`, `major`, `college_id`, `college_name`, `password`, `status`, `created_at`, `updated_at`) VALUES
(13, 'Hari karki', 'STU7890', 'hari@gmail.com', '9834563245', 'BBA', 11, 'Bal Uddhar Seconday School', '$2y$10$AtquUMQDH1vj6vSDxfKK3OgMa0s6EToSVgc17WWgCpZFWwm4.bmsS', 'Approved', '2026-01-04 12:54:54', '2026-01-06 12:36:17'),
(14, 'Alisha Khatri', 'STU7865', 'alisha@gmail.com', '9876543278', 'Computer', 14, 'Thames International College', '$2y$10$OvwjhFrTgx1Kw/GpfqkudelHl4KaXgJh0jt0tj.qwwCKOmnOwUE/W', 'Approved', '2026-01-06 09:27:29', '2026-01-08 04:07:04'),
(15, 'Yojana Rana', 'STU6754', 'yojana@gmail.com', '9876543456', 'BCA', 18, 'Himalayan College of Computer Science', '$2y$10$C4uThJY4sMF0OL9bNwpanOarIGERSdMFrq81W2CGiOmsKb75.WVr.', 'Approved', '2026-02-09 12:35:01', '2026-02-09 12:35:42'),
(16, 'Binisha Luitel', 'STU6785', 'binisha@gmail.com', '9876543456', 'BIT', 16, 'St Xavier College', '$2y$10$BuPd24vb/RFHawbyl/PJE.gZxCBwz4NQhbgq7FwetFumncxXIYNsi', 'Approved', '2026-02-09 12:37:09', '2026-02-09 12:37:44');

-- --------------------------------------------------------

--
-- Table structure for table `student_profile`
--

CREATE TABLE `student_profile` (
  `profile_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `profilePic` varchar(255) DEFAULT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `nationality` varchar(100) NOT NULL,
  `marital` enum('Single','Married') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_profile`
--

INSERT INTO `student_profile` (`profile_id`, `student_id`, `profilePic`, `fname`, `lname`, `dob`, `gender`, `nationality`, `marital`, `created_at`, `updated_at`) VALUES
(3, 14, '1767692009_istockphoto-1398385367-612x612 (1).jpg', 'Alisha', 'Khatri', '2006-03-09', 'Female', 'Nepali', 'Single', '2026-01-06 09:33:29', '2026-01-06 09:33:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `fk_applications_student` (`student_id`);

--
-- Indexes for table `application_step1`
--
ALTER TABLE `application_step1`
  ADD PRIMARY KEY (`step1_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `application_step2`
--
ALTER TABLE `application_step2`
  ADD PRIMARY KEY (`step2_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `application_step3`
--
ALTER TABLE `application_step3`
  ADD PRIMARY KEY (`step3_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `colleges`
--
ALTER TABLE `colleges`
  ADD PRIMARY KEY (`college_id`),
  ADD UNIQUE KEY `registration_number` (`registration_number`),
  ADD UNIQUE KEY `authorized_email` (`authorized_email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `company_registration`
--
ALTER TABLE `company_registration`
  ADD PRIMARY KEY (`company_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `company_settings`
--
ALTER TABLE `company_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `contact_details`
--
ALTER TABLE `contact_details`
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `education_details`
--
ALTER TABLE `education_details`
  ADD PRIMARY KEY (`edu_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `interviews`
--
ALTER TABLE `interviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `logbook_entries`
--
ALTER TABLE `logbook_entries`
  ADD PRIMARY KEY (`entry_id`),
  ADD KEY `fk_logbook_student` (`student_id`);

--
-- Indexes for table `logbook_learnings`
--
ALTER TABLE `logbook_learnings`
  ADD PRIMARY KEY (`learning_id`),
  ADD KEY `entry_id` (`entry_id`);

--
-- Indexes for table `logbook_tasks`
--
ALTER TABLE `logbook_tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `entry_id` (`entry_id`);

--
-- Indexes for table `position_responsibilities`
--
ALTER TABLE `position_responsibilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `position_id` (`position_id`);

--
-- Indexes for table `position_skills`
--
ALTER TABLE `position_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `position_id` (`position_id`);

--
-- Indexes for table `postposition`
--
ALTER TABLE `postposition`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_postposition_company` (`company_id`);

--
-- Indexes for table `review_notes`
--
ALTER TABLE `review_notes`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `fk_review_application` (`application_id`),
  ADD KEY `fk_review_company` (`company_id`);

--
-- Indexes for table `students_registrtaion`
--
ALTER TABLE `students_registrtaion`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `student_code` (`student_code`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_college` (`college_id`);

--
-- Indexes for table `student_profile`
--
ALTER TABLE `student_profile`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `application_step1`
--
ALTER TABLE `application_step1`
  MODIFY `step1_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `application_step2`
--
ALTER TABLE `application_step2`
  MODIFY `step2_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `application_step3`
--
ALTER TABLE `application_step3`
  MODIFY `step3_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `colleges`
--
ALTER TABLE `colleges`
  MODIFY `college_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `company_registration`
--
ALTER TABLE `company_registration`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `company_settings`
--
ALTER TABLE `company_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_details`
--
ALTER TABLE `contact_details`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `education_details`
--
ALTER TABLE `education_details`
  MODIFY `edu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `interviews`
--
ALTER TABLE `interviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `logbook_entries`
--
ALTER TABLE `logbook_entries`
  MODIFY `entry_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `logbook_learnings`
--
ALTER TABLE `logbook_learnings`
  MODIFY `learning_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `logbook_tasks`
--
ALTER TABLE `logbook_tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `position_responsibilities`
--
ALTER TABLE `position_responsibilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `position_skills`
--
ALTER TABLE `position_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `postposition`
--
ALTER TABLE `postposition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `review_notes`
--
ALTER TABLE `review_notes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `students_registrtaion`
--
ALTER TABLE `students_registrtaion`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `student_profile`
--
ALTER TABLE `student_profile`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `postposition` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company_registration` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_applications_student` FOREIGN KEY (`student_id`) REFERENCES `students_registrtaion` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_step1`
--
ALTER TABLE `application_step1`
  ADD CONSTRAINT `application_step1_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_step2`
--
ALTER TABLE `application_step2`
  ADD CONSTRAINT `application_step2_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application_step3`
--
ALTER TABLE `application_step3`
  ADD CONSTRAINT `application_step3_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `company_settings`
--
ALTER TABLE `company_settings`
  ADD CONSTRAINT `company_settings_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company_registration` (`company_id`) ON DELETE CASCADE;

--
-- Constraints for table `contact_details`
--
ALTER TABLE `contact_details`
  ADD CONSTRAINT `contact_details_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students_registrtaion` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `education_details`
--
ALTER TABLE `education_details`
  ADD CONSTRAINT `education_details_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students_registrtaion` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `interviews`
--
ALTER TABLE `interviews`
  ADD CONSTRAINT `interviews_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`application_id`);

--
-- Constraints for table `logbook_entries`
--
ALTER TABLE `logbook_entries`
  ADD CONSTRAINT `fk_logbook_student` FOREIGN KEY (`student_id`) REFERENCES `students_registrtaion` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `logbook_entries_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students_registrtaion` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `logbook_learnings`
--
ALTER TABLE `logbook_learnings`
  ADD CONSTRAINT `logbook_learnings_ibfk_1` FOREIGN KEY (`entry_id`) REFERENCES `logbook_entries` (`entry_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `logbook_tasks`
--
ALTER TABLE `logbook_tasks`
  ADD CONSTRAINT `logbook_tasks_ibfk_1` FOREIGN KEY (`entry_id`) REFERENCES `logbook_entries` (`entry_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `position_responsibilities`
--
ALTER TABLE `position_responsibilities`
  ADD CONSTRAINT `position_responsibilities_ibfk_1` FOREIGN KEY (`position_id`) REFERENCES `postposition` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `position_skills`
--
ALTER TABLE `position_skills`
  ADD CONSTRAINT `position_skills_ibfk_1` FOREIGN KEY (`position_id`) REFERENCES `postposition` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `postposition`
--
ALTER TABLE `postposition`
  ADD CONSTRAINT `fk_postposition_company` FOREIGN KEY (`company_id`) REFERENCES `company_registration` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review_notes`
--
ALTER TABLE `review_notes`
  ADD CONSTRAINT `fk_review_application` FOREIGN KEY (`application_id`) REFERENCES `applications` (`application_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_review_company` FOREIGN KEY (`company_id`) REFERENCES `company_registration` (`company_id`) ON DELETE CASCADE;

--
-- Constraints for table `students_registrtaion`
--
ALTER TABLE `students_registrtaion`
  ADD CONSTRAINT `fk_college` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`college_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_profile`
--
ALTER TABLE `student_profile`
  ADD CONSTRAINT `student_profile_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students_registrtaion` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
