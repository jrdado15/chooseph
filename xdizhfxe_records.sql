-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2022 at 12:41 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `xdizhfxe_records`
--

-- --------------------------------------------------------

--
-- Table structure for table `ods_match_record`
--

CREATE TABLE `ods_match_record` (
  `match_id` int(255) NOT NULL,
  `unique_id1` varchar(255) NOT NULL,
  `unique_id2` varchar(255) NOT NULL,
  `match_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ods_message_record`
--

CREATE TABLE `ods_message_record` (
  `msg_id` int(255) NOT NULL,
  `incoming_msg_id` varchar(255) NOT NULL,
  `outgoing_msg_id` varchar(255) NOT NULL,
  `msg` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ods_public_record`
--

CREATE TABLE `ods_public_record` (
  `pub_id` int(255) NOT NULL,
  `pub_name` varchar(500) NOT NULL,
  `pub_desc` text NOT NULL,
  `pub_sex` varchar(10) NOT NULL,
  `pub_age` int(150) NOT NULL,
  `pub_img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ods_users_profile`
--

CREATE TABLE `ods_users_profile` (
  `id` int(255) NOT NULL,
  `pub_id` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `rotation` int(255) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ods_match_record`
--
ALTER TABLE `ods_match_record`
  ADD PRIMARY KEY (`match_id`);

--
-- Indexes for table `ods_message_record`
--
ALTER TABLE `ods_message_record`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `ods_public_record`
--
ALTER TABLE `ods_public_record`
  ADD PRIMARY KEY (`pub_id`);

--
-- Indexes for table `ods_users_profile`
--
ALTER TABLE `ods_users_profile`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ods_match_record`
--
ALTER TABLE `ods_match_record`
  MODIFY `match_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ods_message_record`
--
ALTER TABLE `ods_message_record`
  MODIFY `msg_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ods_public_record`
--
ALTER TABLE `ods_public_record`
  MODIFY `pub_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ods_users_profile`
--
ALTER TABLE `ods_users_profile`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
