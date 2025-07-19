-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2024 at 07:07 PM
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
-- Database: `studentassigmenttracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignment`
--

CREATE TABLE `assignment` (
  `assignment_id` int(11) NOT NULL AUTO_INCREMENT,
  `assignment_title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `priority` enum('Low','Medium','High') DEFAULT NULL,
  `status_of_assignment` enum('Pending','Completed','Overdue') DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`assignment_id`),
  KEY `user_id` (`user_id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `assignment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `assignment_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(255) DEFAULT NULL,
  `semester` varchar(255) DEFAULT NULL,
  `grade` int(11) DEFAULT NULL,
  `ects` int(11) DEFAULT NULL,
  `course_type` enum('Core','Elective','OutOfStudentsProgram') DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,  -- Added this line to reference user_id
  PRIMARY KEY (`course_id`),
  KEY `user_id` (`user_id`),  -- Index for user_id
  CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE  -- Foreign key constraint
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `discussionforum`
--

CREATE TABLE `discussionforum` (
  `discussionforum_id` int(11) NOT NULL AUTO_INCREMENT,
  `topic` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `user_creator_id` int(11) DEFAULT NULL,
  `is_private` boolean NOT NULL,
  PRIMARY KEY (`discussionforum_id`),
  KEY `user_creator_id` (`user_creator_id`),
  CONSTRAINT `discussionforum_ibfk_1` FOREIGN KEY (`user_creator_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forumpost`
--

CREATE TABLE `forumpost` (
  `forumpost_id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) DEFAULT NULL,
  `post_date` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `discussionforum_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`forumpost_id`),
  KEY `user_id` (`user_id`),
  KEY `discussionforum_id` (`discussionforum_id`),
  CONSTRAINT `forumpost_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `forumpost_ibfk_2` FOREIGN KEY (`discussionforum_id`) REFERENCES `discussionforum` (`discussionforum_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `join_forum`
--

CREATE TABLE `join_forum` (
  `join_forum_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_join_id` int(11) DEFAULT NULL,
  `forum_join_id` int(11) DEFAULT NULL,
  `forum_code` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`join_forum_id`),
  KEY `forum_join_id` (`forum_join_id`),
  KEY `user_join_id` (`user_join_id`),
  CONSTRAINT `join_forum_ibfk_1` FOREIGN KEY (`forum_join_id`) REFERENCES `discussionforum` (`discussionforum_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `join_forum_ibfk_2` FOREIGN KEY (`user_join_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

-- CREATE TABLE `notification` (
--   `notification_id` int(11) NOT NULL AUTO_INCREMENT,
--   `notification_type` enum('Assignment','Task','Forum') DEFAULT NULL,
--   `notification_date` date DEFAULT NULL,
--   `is_read` tinyint(1) DEFAULT NULL,
--   `message` varchar(255) DEFAULT NULL,
--   `user_id` int(11) DEFAULT NULL,
--   PRIMARY KEY (`notification_id`),
--   KEY `user_id` (`user_id`),
--   CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profilecustomization`
--

-- CREATE TABLE `profilecustomization` (
--   `profile_id` int(11) NOT NULL AUTO_INCREMENT,
--   `user_id` int(11) DEFAULT NULL,
--   `colore_shame` varchar(255) DEFAULT NULL,
--   `profile_picture_url` varchar(255) DEFAULT NULL,
--   `is_public` tinyint(1) DEFAULT NULL,
--   PRIMARY KEY (`profile_id`),
--   KEY `user_id` (`user_id`),
--   CONSTRAINT `profilecustomization_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  -- `due_date` date DEFAULT NULL,
  -- `priority` enum('Low','Medium','High') DEFAULT NULL,
  -- `streak_count` int(11) DEFAULT NULL,
  -- `longest_streak` int(11) DEFAULT NULL,
  -- `status` enum('Pending','Completed') DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`task_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `task_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `user_index` int(12) DEFAULT NULL,
  `bio` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Materials Table (Junction Table linking students and courses)
-- CREATE TABLE materials (
--     `material_id` int(11) NOT NULL AUTO_INCREMENT,
--     `student_id` int(11) DEFAULT NULL,
--     `course_id` int(11) DEFAULT NULL,
--     `filename` VARCHAR(255) DEFAULT NULL,
--     `filepath` VARCHAR(255) DEFAULT NULL,
--     `file_type` VARCHAR(50) DEFAULT NULL,
--     `file_size` INT DEFAULT NULL,
--     `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     PRIMARY KEY (`material_id`),
--     CONSTRAINT `materials_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
--     CONSTRAINT `materials_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--
-- (Indexes and foreign key constraints are already added as part of the fixes above)
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
