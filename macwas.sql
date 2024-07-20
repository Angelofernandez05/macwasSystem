-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2024 at 10:21 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `macwas`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', 'admin123', '2024-07-19 03:52:58');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `consumer_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `is_resolved` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `consumers`
--
CREATE TABLE `consumers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `account_num` varchar(255) NOT NULL,
  `registration_num` varchar(255) NOT NULL,
  `meter_num` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `isUpdated` int(11) NOT NULL,
  `registration_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consumers`
--

INSERT INTO `consumers` (`id`, `name`, `barangay`, `account_num`, `registration_num`, `meter_num`, `type`, `status`, `email`, `phone`, `password`, `isUpdated`, `registration_date`) VALUES
(20, 'Manyaneth Carallas', 'Tugas', '09', '09', '1234569', 'Commercial', 1, '', '9104785733', '$2y$10$OXS9Ax/7xjaz8vi/gKfAEemeiEmu.cLXM1moM7QmU56KzaGB5NTh2', 0, NULL),
(21, 'Paul Dakz Zapa', 'Tugas', '10', '10', '12345610', 'Commercial', 1, '', '9104747438', '$2y$10$9pdvV2S54jjz5iGZX4ZKEe3N2zMdhytVz0nwv6/vJo6nfklihBV9K', 0, NULL),
(22, 'Lisa Manoban', 'Malbago', '11', '11', '12345611', 'Residential', 1, '', '9323409099', '$2y$10$Va41xSGcS.2blGttmK.9aeLrsU6rvaSHAfbjXUvKm2Tki5Y6nJE4a', 0, NULL),
(23, 'Darna De Leon', 'Tugas', '12', '12', '12345612', 'Residential', 1, '', '9226666099', '$2y$10$tsYHnyeDTQGO2mZrAmrty.SoZZ3Da8IQ4uNb/Ex6Rs4ROIopIbQ6i', 0, NULL),
(29, 'Angelo', 'Kaongkod', '123', '34', '1334567', 'Commercial', 1, 'fernandezanjerald@gmail.com', '9454351571', '$2y$10$DrsxW8xIn6TvL0x.TizxV.6Wtoc6Ex/GHPmBf9SZdMzYYTwVsICni', 0, NULL),
(30, 'Angelo', 'Kaongkod', '123', '34', '1334567', 'Commercial', 1, 'fernandezanjerald@gmail.com', '9454351571', '$2y$10$VUQ7SVnDWQkxLlGIk.5YLOeD6UbWRRq/r55f.Vfn3Slkp3mIfcdLa', 0, NULL),
(31, 'Angelo', 'Kaongkod', '123', '34', '1334567', 'Commercial', 1, 'fernandezanjerald@gmail.com', '9454351571', '$2y$10$QUQYoTSU65XR.LHFSKmske6wbwkVOvK/X195yCWKJdZsk7rXwNXzK', 0, NULL),
(32, 'eqeqe', 'Kaongkod', '343434', '754754654', '7434634', 'Residential', 1, 'jhonmarkcueva14@gmail.com', '9292437307', '$2y$10$DMrGvAdaEW2Q0DbeiIQ4FOPpgCUF8sPkwyBBqEpWfLoLy/lk53jga', 0, NULL),
(33, 'jhonjhon', 'Talangnan', '09292437307', '2582952', '495454', 'Residential', 1, 'jhonmarkcueva14@gmail.com', '09292437307', '$2y$10$LMZjkr3HuWiZoC8.af8nBOf.uCoYDA3FI9YPCASlwATzQKDdR5A6u', 0, NULL),
(34, 'oxfam', 'talangnan', '1212121', '2323232', '01010101', 'Commercial', 1, 'jhonmarkcueva14@gmail.com', '0925242526353', '$2y$10$F3GV7EizUuMjI7IELknEQOehDo.5oRwMRXMmkgHzCmya0VnN33gs2', 0, NULL),
(35, 'dsadas', 'Kangwayan', '2324143477', '343434', '353643', 'Residential', 1, 'fgdd@gmail.com', '9242524343', '$2y$10$W8GcjsivQOFTN/i3q55qxuXvU.s2bhD6UAazaeFBBU0j/vNsEC5f6', 0, NULL),
(36, 'dsadas', 'Kangwayan', '2324143477', '343434', '353643', 'Residential', 1, 'fgdd@gmail.com', '9242524343', '$2y$10$VyTwLfFHv1WCuPx.YQp2Be1OppuQRgbkivjk7D8FP9Fre8OC3Os1O', 0, NULL),
(37, 'Samuel', 'Poblacion', '09213', '414155', '202020202020', 'Commercial', 0, 'samuelmulle@gmail.com', '', '$2y$10$jQgF0duIGGtXJfzA7fxHkebRGi0REuwk7wdC/t9Z1b/pzlxqc0N/i', 0, NULL),
(38, 'Samuel Mulle', 'Poblacion', '3212', '1324', '123456789', 'Residential', 0, 'samuelmulle12@gmail.com', '09309614231', '$2y$10$lofzFU3OYKeBOw9Z1xdJEOLiU0TEGPAymAVcQa40v8uTHadsy2rSG', 0, NULL),
(39, 'nonoybering', 'kaongkod', '9249824', '384343', '09090909', 'Commercial', 0, 'kasmdas@gmail.com', '09292437307', '$2y$10$bt1z02q/bpzch1jzKSRv3eRg4C7fpCzzvQd6wuYbppYkH7ZATWt0a', 0, NULL),
(41, 'bababa', 'bababa', '24242', '24242', '11112', 'Residential', 0, 'sg@gmail.com', '09252524243', 'bobokatalaga', 1, NULL),
(42, 'kalokalokalo', 'kaongkod', '992424', '35363', '02020202', 'Commercial', 0, 'jhonmarks@gmail.com', '09024924252', '$2y$10$pVZQpJdRlcObHWshHrL5iujn2KJiIrPcTD26dsHL2rhyPcakPdWLi', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `readings`
--

CREATE TABLE `readings` (
  `id` int(11) NOT NULL,
  `consumer_id` int(11) NOT NULL,
  `reading_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_paid` date NOT NULL,
  `previous` float NOT NULL,
  `present` float NOT NULL,
  `status` int(11) NOT NULL,
  `due_date` date NOT NULL DEFAULT current_timestamp(),
  `ref` varchar(100) NOT NULL,
  `shot` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `readings`
--

INSERT INTO `readings` (`id`, `consumer_id`, `reading_date`, `date_paid`, `previous`, `present`, `status`, `due_date`, `ref`, `shot`) VALUES
(84, 11, '2022-12-13 06:19:03', '0000-00-00', 1751.17, 1777.13, 1, '2022-12-12', '', ''),
(86, 27, '2023-02-23 17:11:49', '0000-00-00', 0, 50, 0, '2023-02-24', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$6MGO5G.f4uKbCagzDXB5kOrjItTiXFrVmuIlDijFTXBkhmIA/UMxe', '2022-12-05 22:43:39'),
(2, 'test', '$2y$10$BfkfclU1rotU1aCFKL/lIu.vVHm9envWsug79dvis5tV1GRwQ0fT.', '2023-02-22 20:40:46'),
(3, 'jhonmark417', '$2y$10$VBBGBOz3cu00YROssg9gA.d4IcJ2WXQo7ysWW9FZv9CxdmrEy16WS', '2024-07-19 11:33:23'),
(4, 'adminadmin', '$2y$10$Et0IOHocOSrldjxd0ztHxuKBC1vjq0qmKdTydwsCw08NmWeO054Gy', '2024-07-19 12:08:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consumers`
--
ALTER TABLE `consumers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `readings`
--
ALTER TABLE `readings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `consumers`
--
ALTER TABLE `consumers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `readings`
--
ALTER TABLE `readings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
