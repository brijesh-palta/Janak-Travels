-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2024 at 09:54 AM
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
-- Database: `travels`
--

-- --------------------------------------------------------

--
-- Table structure for table `beach_bookings`
--

CREATE TABLE `beach_bookings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `location` varchar(255) NOT NULL,
  `num_people` int(11) NOT NULL,
  `people_names` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beach_bookings`
--

INSERT INTO `beach_bookings` (`id`, `name`, `email`, `contact`, `location`, `num_people`, `people_names`, `timestamp`) VALUES
(1, 'Brijesh', 'Viralkamani9@gmail.com', '9265825526', 'jamnagar', 2, 'Brijesh, jaimin', '2024-02-18 06:30:25'),
(2, 'Brijesh', 'Viralkamani9@gmail.com', '9265825526', 'jamnagar', 2, '', '2024-02-18 06:42:43'),
(3, 'Brijesh', 'brijeshpalta99@gmail.com', '9265825526', 'jamnagar', 2, 'Brijesh, jaimin', '2024-02-18 06:43:36'),
(4, 'brijesh', 'brijeshpalta99@gmail.com', '9265825526', 'jamnagar', 1, 'Brijesh', '2024-02-19 14:36:31'),
(5, 'Manu', 'Manu99@gmail.com', '9265825526', 'jamnagar', 2, 'brij, Manu', '2024-02-19 14:47:15'),
(6, 'brijesh', 'brijeshpalta99@gmail.com', '9265825526', 'jamnagar', 1, 'Brijesh', '2024-02-21 07:30:48'),
(7, 'brijesh', 'brijeshpalta99@gmail.com', '9265825526', 'jamnagar', 2, 'Brijesh, jaimin', '2024-03-20 03:41:23'),
(8, 'brijesh', 'brijeshpalta99@gmail.com', '9265825526', 'jamnagar', 1, 'Brijesh', '2024-03-20 03:43:46');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chardhamyatra`
--

CREATE TABLE `chardhamyatra` (
  `booking_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `location` varchar(100) NOT NULL,
  `num_people` int(11) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cruisebooking`
--

CREATE TABLE `cruisebooking` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `location` varchar(100) NOT NULL,
  `num_people` int(11) NOT NULL,
  `people_names` text NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cruisebooking`
--

INSERT INTO `cruisebooking` (`id`, `name`, `email`, `contact`, `location`, `num_people`, `people_names`, `booking_date`) VALUES
(1, 'Brijesh', 'brijeshpalta99@gmail.com', '9265825526', 'jamnagar', 2, 'Brijesh, jaimin', '2024-02-18 07:20:39');

-- --------------------------------------------------------

--
-- Table structure for table `desert_bookings`
--

CREATE TABLE `desert_bookings` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `location` varchar(100) NOT NULL,
  `num_people` int(11) NOT NULL,
  `people_names` text NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `desert_bookings`
--

INSERT INTO `desert_bookings` (`id`, `name`, `email`, `contact`, `location`, `num_people`, `people_names`, `booking_date`) VALUES
(1, 'brijesh', 'brijeshpalta99@gmail.com', '9265825526', 'jamnagar', 2, 'Brijesh, jaimin', '2024-02-18 11:53:47'),
(2, 'demo', 'brijeshpalta99@gmail.com', '9265825526', 'punjab', 2, 'Brijesh, jaimin', '2024-02-18 11:54:29'),
(3, 'brijesh', 'brijeshpalta99@gmail.com', '9265825526', 'jamnagar', 1, 'Brijesh', '2024-03-20 03:57:32');

-- --------------------------------------------------------

--
-- Table structure for table `historical`
--

CREATE TABLE `historical` (
  `booking_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `location` varchar(100) NOT NULL,
  `num_people` int(11) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mountain_bookings`
--

CREATE TABLE `mountain_bookings` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `location` varchar(100) NOT NULL,
  `num_people` int(11) NOT NULL,
  `people_names` text NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mountain_bookings`
--

INSERT INTO `mountain_bookings` (`id`, `name`, `email`, `contact`, `location`, `num_people`, `people_names`, `booking_date`) VALUES
(1, 'Brijesh', 'brijeshpalta99@gmail.com', '9265825526', 'jamnagar', 3, 'Brijesh, jaimin, efr345f', '2024-02-18 06:54:38'),
(2, 'ARSHDEEP ', 'ANJDFHNASU@GMAIL.COM', '123465789', 'RAJKIT', 3, 'Brijesh, jaimin, efr345f', '2024-02-18 10:07:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `status` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `mobile_number`, `token`, `status`) VALUES
(1, 'brijeshpalta', 'brijeshpalta99@gmail.com', '$2y$10$wJoEeO09aolud86LyXJP4OMBOQ4UUVq1sO/u/KfIjsrXzyrSnKbZK', '9265825526', '0', 1),
(23, 'brijeshpalta', 'brijeshpalta999@gmail.com', '$2y$10$6xx0.63bQXkoqZatH7pKeetsUEgcFC3DhCp70CYJjL4iChCCKGcey', '9265825526', '0', 1),
(24, 'manu', 'brijeshpalta666@gmail.com', '$2y$10$JOUJnbSeUPCeF3.m2qb10ujElLxaGzLu8NX4HEGJwRvzkSbmPkdVC', '9265825526', '0', 0),
(66, 'mansi', 'brijeshpalta9999@gmail.com', '$2y$10$smMrzzHcLCSgPbrVKDQXk.LSQhlbROUkUMp3CVKuGGbNJdp.EDa9G', '9265825526', '0', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `beach_bookings`
--
ALTER TABLE `beach_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chardhamyatra`
--
ALTER TABLE `chardhamyatra`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `cruisebooking`
--
ALTER TABLE `cruisebooking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `desert_bookings`
--
ALTER TABLE `desert_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `historical`
--
ALTER TABLE `historical`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `mountain_bookings`
--
ALTER TABLE `mountain_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `beach_bookings`
--
ALTER TABLE `beach_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chardhamyatra`
--
ALTER TABLE `chardhamyatra`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cruisebooking`
--
ALTER TABLE `cruisebooking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `desert_bookings`
--
ALTER TABLE `desert_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `historical`
--
ALTER TABLE `historical`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mountain_bookings`
--
ALTER TABLE `mountain_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
