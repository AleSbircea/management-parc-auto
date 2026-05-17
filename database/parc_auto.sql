-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2026 at 09:47 PM
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
-- Database: `parc_auto`
--

-- --------------------------------------------------------

--
-- Table structure for table `masini`
--

CREATE TABLE `masini` (
  `id_masina` int(11) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `numar_inmatriculare` varchar(15) NOT NULL CHECK (`numar_inmatriculare` regexp '^[A-Z]{1,2} [0-9]{2,3} [A-Z]{3}$'),
  `vin` char(17) NOT NULL CHECK (char_length(`vin`) = 17),
  `an_fabricatie` int(11) NOT NULL CHECK (`an_fabricatie` > 1900 and `an_fabricatie` < 2100),
  `sezon_anvelope` enum('Vara','Iarna','All-Season') NOT NULL,
  `km_actuali` int(11) DEFAULT 0 CHECK (`km_actuali` >= 0),
  `status` enum('activa','service','indisponibila') DEFAULT 'activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `utilizatori`
--

CREATE TABLE `utilizatori` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(70) NOT NULL,
  `parola` varchar(255) NOT NULL,
  `data_creare` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

--
-- Dumping data for table `utilizatori`
--

INSERT INTO `utilizatori` (`id`, `username`, `email`, `parola`, `data_creare`) VALUES
(1, 'Ale', 'sbirceaalexandra7@gmail.com', '$2y$10$txriYCHc4mgkVwVSjVJz4ui4YVZhQx.VLBLVx7TAfXZIe/qmGf5xO', '2026-05-17 19:33:02'),
(4, 'Alexandra', 'elenaalexandra.sbircea@gmail.com', '$2y$10$unbtGzU5dxB8zNTQHbS3..XDePYy9eu2orN1T4hSgdfHtjTq4KGNi', '2026-05-17 19:34:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `masini`
--
ALTER TABLE `masini`
  ADD PRIMARY KEY (`id_masina`),
  ADD UNIQUE KEY `numar_inmatriculare` (`numar_inmatriculare`),
  ADD UNIQUE KEY `vin` (`vin`);

--
-- Indexes for table `utilizatori`
--
ALTER TABLE `utilizatori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `masini`
--
ALTER TABLE `masini`
  MODIFY `id_masina` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `utilizatori`
--
ALTER TABLE `utilizatori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
