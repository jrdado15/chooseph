-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2021 at 06:29 AM
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
-- Table structure for table `public_record`
--

CREATE TABLE `public_record` (
  `pub_id` int(255) NOT NULL,
  `pub_name` varchar(500) NOT NULL,
  `pub_desc` text NOT NULL,
  `pub_sex` varchar(10) NOT NULL,
  `pub_age` int(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `public_record`
--

INSERT INTO `public_record` (`pub_id`, `pub_name`, `pub_desc`, `pub_sex`, `pub_age`) VALUES
(1, 'Luke Toledo', 'Ikaw lang, sapat na!', 'Male', 21),
(2, 'Joshua Mananquil', 'Liver o lover, boy!', 'Male', 22),
(3, 'Kevin Aban', 'Black is beauty.', 'Male', 28),
(4, 'Zandro Octavo', 'Man on the outside, woman on the inside.', 'Female', 23),
(5, 'Mark Alano', 'Health is wealth', 'Female', 28);

-- --------------------------------------------------------

--
-- Table structure for table `users_profile`
--

CREATE TABLE `users_profile` (
  `id` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_profile`
--

INSERT INTO `users_profile` (`id`, `email`, `first_name`, `last_name`) VALUES
(1, 'josepharias30@gmail.com', 'Joseph', 'Arias');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `public_record`
--
ALTER TABLE `public_record`
  MODIFY `pub_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users_profile`
--
ALTER TABLE `users_profile`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
