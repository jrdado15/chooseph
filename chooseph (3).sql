-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2022 at 11:29 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chooseph`
--

-- --------------------------------------------------------

--
-- Table structure for table `match_record`
--

CREATE TABLE `match_record` (
  `match_id` int(255) NOT NULL,
  `unique_id1` varchar(255) NOT NULL,
  `unique_id2` varchar(255) NOT NULL,
  `match_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `message_record`
--

CREATE TABLE `message_record` (
  `msg_id` int(255) NOT NULL,
  `incoming_msg_id` varchar(255) NOT NULL,
  `outgoing_msg_id` varchar(255) NOT NULL,
  `msg` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `public_record`
--

CREATE TABLE `public_record` (
  `pub_id` int(255) NOT NULL,
  `pub_name` varchar(500) NOT NULL,
  `pub_desc` text NOT NULL,
  `pub_sex` varchar(10) NOT NULL,
  `pub_age` int(150) NOT NULL,
  `pub_img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `public_record`
--

INSERT INTO `public_record` (`pub_id`, `pub_name`, `pub_desc`, `pub_sex`, `pub_age`, `pub_img`) VALUES
(1, 'Luke', 'Ikaw lang, sapat na!', 'Male', 21, 'placeholder.png,placeholder.png,placeholder.png,placeholder.png'),
(2, 'Joshua', 'Liver o lover, boy!', 'Male', 22, 'placeholder.png,,,'),
(3, 'Kevin', 'Black is beauty.', 'Male', 28, 'placeholder.png,,,'),
(4, 'Zandro', 'Man on the outside, woman on the inside.', 'Female', 23, 'placeholder.png,,,'),
(5, 'Mark', 'Health is wealth', 'Female', 28, 'placeholder.png,,,'),
(7, 'Carl Jayson', 'Living is suffering.', 'Male', 27, 'placeholder.png,,,'),
(10, 'Joseph', 'I love anime and manga', 'Male', 22, 'image_472428029,image_840293994,image_968909785,image_669553696');

-- --------------------------------------------------------

--
-- Table structure for table `users_profile`
--

CREATE TABLE `users_profile` (
  `id` int(255) NOT NULL,
  `pub_id` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `rotation` int(255) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_profile`
--

INSERT INTO `users_profile` (`id`, `pub_id`, `email`, `first_name`, `last_name`, `rotation`) VALUES
(2, 7, 'jayson.angeles@yahoo.com', 'Carl Jayson', 'Angeles', 0),
(3, 1, 'luketoledo@gmail.com', 'Luke', 'Toledo', 0),
(4, 2, 'joshuamananquil@gmail.com', 'Joshua Ray', 'Mananquil', 0),
(5, 3, 'kevinaban@gmail.com', 'Kevin Bryan', 'Aban', 0),
(6, 4, 'zandrooctavo@gmail.com', 'Zandro Miguel', 'Octavo', 0),
(7, 5, 'markalano@gmail.com', 'Mark Clarence', 'Alano', 0),
(8, 10, 'josepharias30@gmail.com', 'Joseph', 'Arias', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `match_record`
--
ALTER TABLE `match_record`
  ADD PRIMARY KEY (`match_id`);

--
-- Indexes for table `message_record`
--
ALTER TABLE `message_record`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `public_record`
--
ALTER TABLE `public_record`
  ADD PRIMARY KEY (`pub_id`);

--
-- Indexes for table `users_profile`
--
ALTER TABLE `users_profile`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `match_record`
--
ALTER TABLE `match_record`
  MODIFY `match_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message_record`
--
ALTER TABLE `message_record`
  MODIFY `msg_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `public_record`
--
ALTER TABLE `public_record`
  MODIFY `pub_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users_profile`
--
ALTER TABLE `users_profile`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
