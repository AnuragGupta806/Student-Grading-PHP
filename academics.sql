-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2022 at 07:51 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `academics`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `curr_sem` int(20) NOT NULL,
  `course_entry` tinyint(1) NOT NULL,
  `grade_entry` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `curr_sem`, `course_entry`, `grade_entry`) VALUES
(1, 3, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `degree` varchar(255) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `credits` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `sub_code` varchar(255) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `distribution` varchar(255) NOT NULL,
  `course_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `employee_id`, `degree`, `branch`, `credits`, `semester`, `sub_code`, `subject_name`, `distribution`, `course_type`) VALUES
(1, 1, 'btech', 'ece', 3, 1, 'EC12412', 'Microprocessor', '60,20,20', 'Theory'),
(2, 1, 'btech', 'ece', 4, 1, 'EC13201', 'Electronics Circuit Design', '50,30,20', 'Theory'),
(3, 1, 'btech', 'ece', 2, 1, 'EC13106', 'Digital Communication Lab', '40,40,20', 'Practical'),
(4, 1, 'btech', 'ece', 4, 1, 'EC13206', 'Data Structures', '60,20,10,10', 'Theory'),
(5, 1, 'btech', 'ece', 4, 2, 'EC1111', 'Electromagnetic Theory', '60,20,20', 'Theory'),
(6, 1, 'btech', 'ece', 3, 2, 'EC12121', 'Mathematics', '60,20,10,10', 'Practical'),
(7, 1, 'btech', 'ece', 4, 3, 'EC2101', 'Algorithms', '60,20,5,5,5,5', 'Theory'),
(8, 1, 'btech', 'ece', 3, 3, 'EC30101', 'Chestry', '50,30,20', 'Practical');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `regno` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `degree` varchar(50) NOT NULL,
  `branch` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `regno`, `username`, `degree`, `branch`, `password`) VALUES
(1, '20195168', 'anurag', 'btech', 'ece', '$2y$10$6EaYvYcT4Cn3Y2F5cHAc/.Zipsy.zO/IGhrciVuJZKFY/0c03ZabK'),
(2, '20195151', 'ayush', 'btech', 'ece', '$2y$10$uGE49tWzCZ37eZ6LSVjn9.IMe4MfMT13HyW6q2fvX.TidRpUf/S0.'),
(3, '20194000', 'shubham', 'btech', 'cse', '$2y$10$ns2FfpqC2njryUE9Ht3xUOblr58W8KtdyySRLslZTeXV5VuyDgOCO');

-- --------------------------------------------------------

--
-- Table structure for table `students_marks`
--

CREATE TABLE `students_marks` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `marks` int(11) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `students_marks`
--

INSERT INTO `students_marks` (`id`, `student_id`, `marks`, `course_id`) VALUES
(1, 1, 83, 1),
(2, 1, 91, 4),
(3, 2, 60, 4),
(4, 1, 60, 2),
(5, 1, 60, 3),
(6, 2, 80, 1),
(7, 2, 90, 2),
(8, 2, 72, 3),
(9, 1, 75, 5),
(10, 1, 87, 6),
(11, 2, 55, 5),
(12, 2, 40, 6),
(13, 1, 78, 7),
(14, 2, 60, 7),
(15, 1, 83, 8),
(16, 2, 80, 8);

-- --------------------------------------------------------

--
-- Table structure for table `student_spi`
--

CREATE TABLE `student_spi` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `spi` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `student_spi`
--

INSERT INTO `student_spi` (`id`, `student_id`, `semester`, `spi`) VALUES
(2, 1, 1, 9.57),
(3, 2, 1, 8.08),
(4, 1, 2, 8.86),
(5, 1, 3, 8.43);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `employee_no` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`id`, `username`, `password`, `employee_no`) VALUES
(1, 'rajeev', '$2y$10$jR/.yVRDTxAE52DLkDlHSeAXjd4s85R2k823UNhcIupb06v8pOMTq', '123456');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `curr_sem` (`curr_sem`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `regno` (`regno`);

--
-- Indexes for table `students_marks`
--
ALTER TABLE `students_marks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student` (`student_id`),
  ADD KEY `courses` (`course_id`);

--
-- Indexes for table `student_spi`
--
ALTER TABLE `student_spi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student` (`student_id`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_no` (`employee_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students_marks`
--
ALTER TABLE `students_marks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `student_spi`
--
ALTER TABLE `student_spi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `teacher` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `students_marks`
--
ALTER TABLE `students_marks`
  ADD CONSTRAINT `students_marks_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `students_marks_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_spi`
--
ALTER TABLE `student_spi`
  ADD CONSTRAINT `student_spi_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
