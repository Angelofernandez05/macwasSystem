-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 29, 2023 at 01:15 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

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
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `consumer_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `is_resolved` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `consumers`
--

CREATE TABLE `consumers` (
  `id` int(11) NOT NULL,
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
  `isUpdated` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `consumers`
--

INSERT INTO `consumers` (`id`, `name`, `barangay`, `account_num`, `registration_num`, `meter_num`, `type`, `status`, `email`, `phone`, `password`, `isUpdated`) VALUES
(12, 'John Laurel Carallas', 'Bunakan', '01', '01', '1234561', 'Residential', 1, '', '9983833838', '$2y$10$6zCFTr6PQdOth9uqp2Ngs.nsLUTXAcObLPcwue2/tUihIG8aa6ZXq', 1),
(13, 'Jade Ducay', 'Bunakan', '02', '02', '1234562', 'Residential', 1, '', '9283373727', '$2y$10$vGSRitiWVXCwKVYVifDzdu9mPvMpTc6nMCLf8vnWvFxZAg0/l1/l2', 1),
(14, 'Mark Stephen Alolor', 'Mancilang', '03', '03', '1234563', 'Residential', 1, '', '9238383840', '$2y$10$/DO40H.TPmygnXcm3hpKwe8zHMCoGW1fzWnhbWM3V7AH7wUrdeelq', 0),
(15, 'John Ryan Medallo', 'Tarong', '04', '04', '1234564', 'Residential', 1, '', '9314567809', '$2y$10$PdPdq6Ai.2TBUTUnmeOivuYLhvyUy8aKxU8S3AJOZVHrtKEvOrSfK', 0),
(16, 'Alvin Moradas', 'San Agustin', '05', '05', '1234565', 'Residential', 1, '', '9224547678', '$2y$10$rQl7PnoX35U4vHhrLND1y.mpxWb8wue9xQF4qPbB1NGV27p17UVby', 0),
(17, 'Miguel Fernandez', 'Kodia', '06', '06', '1234566', 'Commercial', 1, '', '9103422987', '$2y$10$SKjxQ7dkXtiLEXSS2wrJ/eCzc80FrPym5kZ6AYX2KyijV2K1BosRm', 0),
(18, 'Lourenz Fulmenar', 'Pili', '07', '07', '1234567', 'Commercial', 1, '', '9123636789', '$2y$10$Hj3SWIPd0joUKObPLoyyiudjpbvcxeclmzh8U.S2c9pjJgLeovDlq', 0),
(19, 'Mareth Maru', 'Talangnan', '08', '08', '1234568', 'Commercial', 1, '', '9318478423', '$2y$10$gseRX652mwCCzfiGdTQKSutkSJWY7ahm8fOPouOvj448sSxq5E4ty', 0),
(20, 'Manyaneth Carallas', 'Tugas', '09', '09', '1234569', 'Commercial', 1, '', '9104785733', '$2y$10$OXS9Ax/7xjaz8vi/gKfAEemeiEmu.cLXM1moM7QmU56KzaGB5NTh2', 0),
(21, 'Paul Dakz Zapa', 'Tugas', '10', '10', '12345610', 'Commercial', 1, '', '9104747438', '$2y$10$9pdvV2S54jjz5iGZX4ZKEe3N2zMdhytVz0nwv6/vJo6nfklihBV9K', 0),
(22, 'Lisa Manoban', 'Malbago', '11', '11', '12345611', 'Residential', 1, '', '9323409099', '$2y$10$Va41xSGcS.2blGttmK.9aeLrsU6rvaSHAfbjXUvKm2Tki5Y6nJE4a', 0),
(23, 'Darna De Leon', 'Tugas', '12', '12', '12345612', 'Residential', 1, '', '9226666099', '$2y$10$tsYHnyeDTQGO2mZrAmrty.SoZZ3Da8IQ4uNb/Ex6Rs4ROIopIbQ6i', 0),
(24, 'Valentina Salvador', 'Maalat', '13', '13', '12345613', 'Residential', 1, '', '9330001101', '$2y$10$j0uuOxtU6h3WVrfySGFM3OZ6B42BsfnXqSnLWT2iUQC35xBWi.Q3O', 0),
(25, 'Joshua Garcia', 'Pili', '14', '14', '12345614', 'Residential', 1, '', '9107123456', '$2y$10$Tzh7yLwrfLklhqLlt0xSu.IO/N9XbMdF1kMyTMW69viqT5RnIzujS', 0),
(26, 'Aries Ilustrisimo', 'San Agustin', '15', '15', '12345615', 'Commercial', 1, 'ariesilustrisimo39@gmail.com', '9812020735', '$2y$10$v4QLicueLJPzPiMi3EH4p.lpAGa5oWnxLoUWcF3/epWRXL9x2XQe2', 1),
(27, 'Charlie Valdez', 'San Agustin', '16', '16', '12345616', 'Residential', 1, 'clongxxi@gmail.com', '9812020735', '$2y$10$9wtzoxDvLCh2fXGdGQGvm.bAukDgL3tfcTrJ..eY88/mxbGZc7dVa', 1),
(28, 'Chloe Valdez', 'San Agustin', '01', '01', '1234561', 'Commercial', 1, 'clongxxi@gmail.com', '9437837537', '$2y$10$/txi2xqSYrPltq5m3Nj30.ALi87Cl.buAkWq9hwQdmlp09ncMB9kO', 0);

-- --------------------------------------------------------

--
-- Table structure for table `readings`
--

CREATE TABLE `readings` (
  `id` int(11) NOT NULL,
  `consumer_id` int(11) NOT NULL,
  `reading_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `previous` float NOT NULL,
  `present` float NOT NULL,
  `status` int(11) NOT NULL,
  `due_date` date NOT NULL DEFAULT current_timestamp(),
  `ref` varchar(100) NOT NULL,
  `shot` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `readings`
--

INSERT INTO `readings` (`id`, `consumer_id`, `reading_date`, `previous`, `present`, `status`, `due_date`, `ref`, `shot`) VALUES
(84, 11, '2022-12-13 14:19:03', 1751.17, 1777.13, 1, '2022-12-12', '', ''),
(86, 27, '2023-02-24 01:11:49', 0, 50, 0, '2023-02-24', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$3NfLw8RDIQ6LaS.gcnZnGOqbFF1RWEmCFe68j/fw2Hhgob2grCF7q', '2022-12-05 22:43:39'),
(2, 'test', '$2y$10$BfkfclU1rotU1aCFKL/lIu.vVHm9envWsug79dvis5tV1GRwQ0fT.', '2023-02-22 20:40:46');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `consumers`
--
ALTER TABLE `consumers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `readings`
--
ALTER TABLE `readings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
