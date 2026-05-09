-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2026 at 03:28 PM
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
-- Table structure for table `amenzi`
--

CREATE TABLE `amenzi` (
  `id` int(11) NOT NULL,
  `masina_id` int(11) NOT NULL,
  `sofer_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `ora` time DEFAULT NULL,
  `suma` decimal(8,2) NOT NULL,
  `motiv` varchar(200) NOT NULL,
  `localitate` varchar(100) DEFAULT NULL,
  `nr_proces_verbal` varchar(50) DEFAULT NULL,
  `puncte_penalizare` int(11) DEFAULT 0,
  `status` enum('neplatita','platita','contestata') NOT NULL DEFAULT 'neplatita',
  `data_scadenta` date DEFAULT NULL,
  `data_plata` date DEFAULT NULL,
  `observatii` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `anvelope`
--

CREATE TABLE `anvelope` (
  `id` int(11) NOT NULL,
  `masina_id` int(11) NOT NULL,
  `sezon` enum('vara','iarna','all_season') NOT NULL,
  `marca` varchar(50) NOT NULL,
  `dimensiune` varchar(20) NOT NULL,
  `an_fabricatie` year(4) DEFAULT NULL,
  `km_montaj` int(11) NOT NULL DEFAULT 0,
  `data_cumparare` date DEFAULT NULL,
  `garantie_luni` int(11) DEFAULT NULL,
  `garantie_expirare` date DEFAULT NULL,
  `pozitie` enum('fata_stanga','fata_dreapta','spate_stanga','spate_dreapta','depozit') NOT NULL,
  `status` enum('montata','in_depozit','scoasa_din_uz') NOT NULL DEFAULT 'montata',
  `observatii` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asignari_soferi`
--

CREATE TABLE `asignari_soferi` (
  `id` int(11) NOT NULL,
  `sofer_id` int(11) NOT NULL,
  `masina_id` int(11) NOT NULL,
  `data_start` date NOT NULL,
  `data_sfarsit` date DEFAULT NULL,
  `motiv_asignare` enum('titular','inlocuitor','delegatie') NOT NULL,
  `km_la_preluare` int(11) NOT NULL,
  `km_la_predare` int(11) DEFAULT NULL,
  `km_efectuati` int(11) GENERATED ALWAYS AS (`km_la_predare` - `km_la_preluare`) STORED,
  `observatii` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asigurari`
--

CREATE TABLE `asigurari` (
  `id` int(11) NOT NULL,
  `masina_id` int(11) NOT NULL,
  `tip` enum('RCA','CASCO','RCA+CASCO') NOT NULL,
  `societate` varchar(100) NOT NULL,
  `nr_polita` varchar(50) NOT NULL,
  `data_start` date NOT NULL,
  `data_expirare` date NOT NULL,
  `prima` decimal(10,2) NOT NULL,
  `dosar_dauna` varchar(50) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `observatii` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `combustibil`
--

CREATE TABLE `combustibil` (
  `id` int(11) NOT NULL,
  `masina_id` int(11) NOT NULL,
  `sofer_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `ora` time DEFAULT NULL,
  `litri` decimal(5,2) NOT NULL,
  `pret_litru` decimal(5,3) NOT NULL,
  `total` decimal(8,2) GENERATED ALWAYS AS (`litri` * `pret_litru`) STORED,
  `tip_combustibil` enum('benzina','motorina','electric','gpl') NOT NULL,
  `benzinarie` varchar(100) DEFAULT NULL,
  `nr_bon` varchar(50) DEFAULT NULL,
  `observatii` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foaie_parcurs`
--

CREATE TABLE `foaie_parcurs` (
  `id` int(11) NOT NULL,
  `masina_id` int(11) NOT NULL,
  `sofer_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `loc_plecare` varchar(100) DEFAULT NULL,
  `ora_plecare` time DEFAULT NULL,
  `loc_sosire` varchar(100) DEFAULT NULL,
  `ora_sosire` time DEFAULT NULL,
  `km_plecare` int(11) NOT NULL,
  `km_sosire` int(11) NOT NULL,
  `km_efectuati` int(11) GENERATED ALWAYS AS (`km_sosire` - `km_plecare`) STORED,
  `km_oras` int(11) DEFAULT NULL,
  `km_exterior` int(11) DEFAULT NULL,
  `litri_combustibil` decimal(5,2) DEFAULT NULL,
  `nr_act` varchar(50) DEFAULT NULL,
  `observatii` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `itp`
--

CREATE TABLE `itp` (
  `id` int(11) NOT NULL,
  `masina_id` int(11) NOT NULL,
  `data_efectuare` date NOT NULL,
  `data_expirare` date NOT NULL,
  `statia_itp` varchar(100) DEFAULT NULL,
  `rezultat` enum('admis','respins') NOT NULL,
  `observatii` text DEFAULT NULL,
  `cost` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `masini`
--

CREATE TABLE `masini` (
  `id` int(11) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `an_fabricatie` year(4) NOT NULL,
  `numar_inmatriculare` varchar(10) NOT NULL,
  `serie_sasiu` varchar(17) NOT NULL,
  `culoare` varchar(30) DEFAULT NULL,
  `combustibil` enum('benzina','motorina','electric','hibrid','gpl') NOT NULL,
  `km_actuali` int(11) NOT NULL DEFAULT 0,
  `status` enum('activa','in_service','scoasa_din_parc') NOT NULL DEFAULT 'activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `masina_id` int(11) NOT NULL,
  `data_intrare` date NOT NULL,
  `data_iesire` date DEFAULT NULL,
  `motiv` enum('revizie','reparatie','accident','anvelope','ITP','altele') NOT NULL,
  `descriere` text NOT NULL,
  `lucrari_efectuate` text DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `atelier` varchar(100) DEFAULT NULL,
  `km_la_intrare` int(11) DEFAULT NULL,
  `status` enum('programat','in_lucru','finalizat') NOT NULL DEFAULT 'programat'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `soferi`
--

CREATE TABLE `soferi` (
  `id` int(11) NOT NULL,
  `nume` varchar(50) NOT NULL,
  `prenume` varchar(50) NOT NULL,
  `cnp` char(13) NOT NULL,
  `permis_nr` varchar(20) NOT NULL,
  `permis_valabilitate` date NOT NULL,
  `categorie_permis` enum('A','A1','A2','AM','B','B1','BE','C','CE','D','DE') NOT NULL,
  `telefon` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` enum('activ','inactiv','suspendat') NOT NULL DEFAULT 'activ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `utilizatori`
--

CREATE TABLE `utilizatori` (
  `id` int(11) NOT NULL,
  `nume` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `parola` varchar(255) NOT NULL,
  `rol` enum('admin','dispecer','mecanic','sofer') NOT NULL DEFAULT 'dispecer',
  `status` enum('activ','inactiv') NOT NULL DEFAULT 'activ',
  `ultima_autentificare` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `viniete`
--

CREATE TABLE `viniete` (
  `id` int(11) NOT NULL,
  `masina_id` int(11) NOT NULL,
  `serie` varchar(30) NOT NULL,
  `categorie` enum('A','B','C','D') NOT NULL,
  `valabilitate` enum('7_zile','30_zile','90_zile','1_an') NOT NULL,
  `data_start` date NOT NULL,
  `data_expirare` date NOT NULL,
  `cost` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `amenzi`
--
ALTER TABLE `amenzi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `masina_id` (`masina_id`),
  ADD KEY `sofer_id` (`sofer_id`);

--
-- Indexes for table `anvelope`
--
ALTER TABLE `anvelope`
  ADD PRIMARY KEY (`id`),
  ADD KEY `masina_id` (`masina_id`);

--
-- Indexes for table `asignari_soferi`
--
ALTER TABLE `asignari_soferi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sofer_id` (`sofer_id`),
  ADD KEY `masina_id` (`masina_id`);

--
-- Indexes for table `asigurari`
--
ALTER TABLE `asigurari`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nr_polita` (`nr_polita`),
  ADD KEY `masina_id` (`masina_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `combustibil`
--
ALTER TABLE `combustibil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `masina_id` (`masina_id`),
  ADD KEY `sofer_id` (`sofer_id`);

--
-- Indexes for table `foaie_parcurs`
--
ALTER TABLE `foaie_parcurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `masina_id` (`masina_id`),
  ADD KEY `sofer_id` (`sofer_id`);

--
-- Indexes for table `itp`
--
ALTER TABLE `itp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `masina_id` (`masina_id`);

--
-- Indexes for table `masini`
--
ALTER TABLE `masini`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numar_inmatriculare` (`numar_inmatriculare`),
  ADD UNIQUE KEY `serie_sasiu` (`serie_sasiu`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `masina_id` (`masina_id`);

--
-- Indexes for table `soferi`
--
ALTER TABLE `soferi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cnp` (`cnp`);

--
-- Indexes for table `utilizatori`
--
ALTER TABLE `utilizatori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `viniete`
--
ALTER TABLE `viniete`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serie` (`serie`),
  ADD KEY `masina_id` (`masina_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `amenzi`
--
ALTER TABLE `amenzi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `anvelope`
--
ALTER TABLE `anvelope`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asignari_soferi`
--
ALTER TABLE `asignari_soferi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asigurari`
--
ALTER TABLE `asigurari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `combustibil`
--
ALTER TABLE `combustibil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foaie_parcurs`
--
ALTER TABLE `foaie_parcurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `itp`
--
ALTER TABLE `itp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `masini`
--
ALTER TABLE `masini`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `soferi`
--
ALTER TABLE `soferi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `utilizatori`
--
ALTER TABLE `utilizatori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `viniete`
--
ALTER TABLE `viniete`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `amenzi`
--
ALTER TABLE `amenzi`
  ADD CONSTRAINT `amenzi_ibfk_1` FOREIGN KEY (`masina_id`) REFERENCES `masini` (`id`),
  ADD CONSTRAINT `amenzi_ibfk_2` FOREIGN KEY (`sofer_id`) REFERENCES `soferi` (`id`);

--
-- Constraints for table `anvelope`
--
ALTER TABLE `anvelope`
  ADD CONSTRAINT `anvelope_ibfk_1` FOREIGN KEY (`masina_id`) REFERENCES `masini` (`id`);

--
-- Constraints for table `asignari_soferi`
--
ALTER TABLE `asignari_soferi`
  ADD CONSTRAINT `asignari_soferi_ibfk_1` FOREIGN KEY (`sofer_id`) REFERENCES `soferi` (`id`),
  ADD CONSTRAINT `asignari_soferi_ibfk_2` FOREIGN KEY (`masina_id`) REFERENCES `masini` (`id`);

--
-- Constraints for table `asigurari`
--
ALTER TABLE `asigurari`
  ADD CONSTRAINT `asigurari_ibfk_1` FOREIGN KEY (`masina_id`) REFERENCES `masini` (`id`),
  ADD CONSTRAINT `asigurari_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`);

--
-- Constraints for table `combustibil`
--
ALTER TABLE `combustibil`
  ADD CONSTRAINT `combustibil_ibfk_1` FOREIGN KEY (`masina_id`) REFERENCES `masini` (`id`),
  ADD CONSTRAINT `combustibil_ibfk_2` FOREIGN KEY (`sofer_id`) REFERENCES `soferi` (`id`);

--
-- Constraints for table `foaie_parcurs`
--
ALTER TABLE `foaie_parcurs`
  ADD CONSTRAINT `foaie_parcurs_ibfk_1` FOREIGN KEY (`masina_id`) REFERENCES `masini` (`id`),
  ADD CONSTRAINT `foaie_parcurs_ibfk_2` FOREIGN KEY (`sofer_id`) REFERENCES `soferi` (`id`);

--
-- Constraints for table `itp`
--
ALTER TABLE `itp`
  ADD CONSTRAINT `itp_ibfk_1` FOREIGN KEY (`masina_id`) REFERENCES `masini` (`id`);

--
-- Constraints for table `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_1` FOREIGN KEY (`masina_id`) REFERENCES `masini` (`id`);

--
-- Constraints for table `viniete`
--
ALTER TABLE `viniete`
  ADD CONSTRAINT `viniete_ibfk_1` FOREIGN KEY (`masina_id`) REFERENCES `masini` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
