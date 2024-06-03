-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 29, 2023 at 12:37 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `attendance_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_table`
--

CREATE TABLE `admin_table` (
  `admin_id` int(11) NOT NULL,
  `admin_user_name` varchar(150) NOT NULL,
  `admin_password` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_table`
--

INSERT INTO `admin_table` (`admin_id`, `admin_user_name`, `admin_password`) VALUES
(1, 'admin', '$2y$10$D74Zy1qMkATvmGRoVeq7hed4ajWof2aqDGnEaD3yPHABA.p.e7f4u');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_table`
--

CREATE TABLE `attendance_table` (
  `attendance_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `attendance_status` enum('Present','Absent') NOT NULL,
  `attendance_date` date DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_table`
--

CREATE TABLE `class_table` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_table`
--

INSERT INTO `class_table` (`class_id`, `class_name`) VALUES
(101, 'BCA'),
(102, 'Bsc.IT');

-- --------------------------------------------------------

--
-- Table structure for table `student_table`
--

CREATE TABLE `student_table` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(150) NOT NULL,
  `student_roll_number` int(11) NOT NULL,
  `student_dob` date NOT NULL,
  `student_class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_table`
--

INSERT INTO `student_table` (`student_id`, `student_name`, `student_roll_number`, `student_dob`, `student_class_id`) VALUES
(20231, 'Kashish Kaul', 1001, '2001-10-10', 101);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_table`
--

CREATE TABLE `teacher_table` (
  `teacher_id` int(11) NOT NULL,
  `teacher_name` varchar(150) NOT NULL,
  `teacher_address` varchar(250) NOT NULL,
  `teacher_emailid` varchar(150) NOT NULL,
  `teacher_password` varchar(200) NOT NULL,
  `teacher_class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_table`
--

INSERT INTO `teacher_table` (`teacher_id`, `teacher_name`, `teacher_address`, `teacher_emailid`, `teacher_password`, `teacher_class_id`) VALUES
(10, 'Mr. Anil Bhasin', 'Mahatma Hans Raj Marg, Grand Trunk Rd, Jalandhar, Punjab 144008', 'anil123@gmail.com', '$2y$10$D74Zy1qMkATvmGRoVeq7hed4ajWof2aqDGnEaD3yPHABA.p.e7f4u', 101);

-- --------------------------------------------------------

--
-- Table structure for table `userinfo`
--

CREATE TABLE `userinfo` (
  `Sr_no` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone_no` varchar(150) NOT NULL,
  `comment` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_table`
--
ALTER TABLE `admin_table`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `attendance_table`
--
ALTER TABLE `attendance_table`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `class_table`
--
ALTER TABLE `class_table`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `student_table`
--
ALTER TABLE `student_table`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `teacher_table`
--
ALTER TABLE `teacher_table`
  ADD PRIMARY KEY (`teacher_id`);

--
-- Indexes for table `userinfo`
--
ALTER TABLE `userinfo`
  ADD PRIMARY KEY (`Sr_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_table`
--
ALTER TABLE `admin_table`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `class_table`
--
ALTER TABLE `class_table`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `student_table`
--
ALTER TABLE `student_table`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20232;

--
-- AUTO_INCREMENT for table `teacher_table`
--
ALTER TABLE `teacher_table`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `userinfo`
--
ALTER TABLE `userinfo`
  MODIFY `Sr_no` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
