-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2026 at 09:12 PM
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
  `sofer_id` int(11) DEFAULT NULL,
  `data_amenzi` date NOT NULL,
  `motiv` varchar(200) NOT NULL,
  `valoare` decimal(8,2) NOT NULL,
  `plata_status` enum('neplatita','platita','in_contestare') NOT NULL DEFAULT 'neplatita',
  `data_plata` date DEFAULT NULL,
  `dosar_nr` varchar(50) DEFAULT NULL,
  `tip_penalizare` enum('doar_amenda','puncte_retinute','carnet_retras','suspendare_temporara','altele') NOT NULL DEFAULT 'doar_amenda',
  `observatii` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `anvelope`
--

CREATE TABLE `anvelope` (
  `id` int(11) NOT NULL,
  `masina_id` int(11) NOT NULL,
  `sezon` enum('Vara','Iarna','All-Season') NOT NULL,
  `stare` enum('bun','uzat','deteriorat') NOT NULL DEFAULT 'bun',
  `km_pus` int(11) NOT NULL,
  `km_estimat_schimb` int(11) DEFAULT NULL,
  `data_punere` date NOT NULL,
  `cost` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
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
  `observatii` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

--
-- Triggers `asignari_soferi`
--
DELIMITER $$
CREATE TRIGGER `tr_prevent_dual_assignment` BEFORE INSERT ON `asignari_soferi` FOR EACH ROW BEGIN
    IF (SELECT COUNT(*) FROM asignari_soferi 
        WHERE masina_id = NEW.masina_id 
        AND data_sfarsit IS NULL) > 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Mașina e deja asignată!';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_prevent_sofer_dual_cars` BEFORE INSERT ON `asignari_soferi` FOR EACH ROW BEGIN
    IF (SELECT COUNT(*) FROM asignari_soferi 
        WHERE sofer_id = NEW.sofer_id 
        AND data_sfarsit IS NULL) > 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Șoferul e deja asignat la altă mașină!';
    END IF;
END
$$
DELIMITER ;

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
  `observatii` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `combustibil`
--

CREATE TABLE `combustibil` (
  `id` int(11) NOT NULL,
  `masina_id` int(11) NOT NULL,
  `sofer_id` int(11) DEFAULT NULL,
  `data_alimentare` date NOT NULL,
  `tip` enum('benzina','motorina','gpl','electrica') NOT NULL,
  `cantitate` decimal(8,2) NOT NULL,
  `unitate` enum('litri','kWh') NOT NULL,
  `pret_unitate` decimal(6,2) NOT NULL,
  `cost_total` decimal(8,2) NOT NULL,
  `km_la_alimentare` int(11) NOT NULL,
  `locatie` varchar(100) DEFAULT NULL,
  `observatii` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foaie_parcurs`
--

CREATE TABLE `foaie_parcurs` (
  `id` int(11) NOT NULL,
  `sofer_id` int(11) NOT NULL,
  `masina_id` int(11) NOT NULL,
  `data_start` date NOT NULL,
  `data_sfarsit` date DEFAULT NULL,
  `km_start` int(11) NOT NULL,
  `km_sfarsit` int(11) DEFAULT NULL,
  `motiv` varchar(200) DEFAULT NULL,
  `obligatorie` enum('da','nu') NOT NULL DEFAULT 'da',
  `observatii` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
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
  `cost` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

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

--
-- Dumping data for table `masini`
--

INSERT INTO `masini` (`id_masina`, `marca`, `model`, `numar_inmatriculare`, `vin`, `an_fabricatie`, `sezon_anvelope`, `km_actuali`, `status`) VALUES
(2, 'OPEL', 'ASTRA H', 'SB 06 SEA', 'WP0ZZZ99ZLS123456', 2013, 'Iarna', 185000, 'activa');

-- --------------------------------------------------------

--
-- Table structure for table `revizii`
--

CREATE TABLE `revizii` (
  `id` int(11) NOT NULL,
  `masina_id` int(11) NOT NULL,
  `data_revizie` date NOT NULL,
  `km_la_revizie` int(11) NOT NULL,
  `tip` enum('schimb_ulei','schimb_filtru_aer','schimb_filtru_ulei','revizie_complet_15000km','revizie_complet_30000km','revizie_complet_60000km','verificare_frane','verificare_lichid_racire','verificare_suspensie','verificare_anvelope','service_speciala') NOT NULL,
  `cost` decimal(8,2) DEFAULT NULL,
  `atelier` varchar(100) DEFAULT NULL,
  `observatii` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
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
  `km_la_intrare` int(11) NOT NULL,
  `status` enum('programat','in_lucru','finalizat') NOT NULL DEFAULT 'programat',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
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
  `status` enum('activ','inactiv','suspendat') NOT NULL DEFAULT 'activ',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
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
(6, 'test', 'test@test.com', '$2y$10$mHvbv0C8NWCiSrWD/91yqekDLA6lF5YCXe/7SgyB84.XS2FD/4zD2', '2026-05-28 21:19:30');

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
  `cost` decimal(8,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_romanian_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `amenzi`
--
ALTER TABLE `amenzi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_amenzi_masina` (`masina_id`),
  ADD KEY `idx_amenzi_sofer` (`sofer_id`),
  ADD KEY `idx_amenzi_status` (`plata_status`);

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
  ADD KEY `sofer_id` (`sofer_id`),
  ADD KEY `masina_id` (`masina_id`);

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
  ADD PRIMARY KEY (`id_masina`),
  ADD UNIQUE KEY `numar_inmatriculare` (`numar_inmatriculare`),
  ADD UNIQUE KEY `vin` (`vin`);

--
-- Indexes for table `revizii`
--
ALTER TABLE `revizii`
  ADD PRIMARY KEY (`id`),
  ADD KEY `masina_id` (`masina_id`);

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
  ADD UNIQUE KEY `username` (`username`),
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
  MODIFY `id_masina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `revizii`
--
ALTER TABLE `revizii`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  ADD CONSTRAINT `amenzi_ibfk_1` FOREIGN KEY (`masina_id`) REFERENCES `masini` (`id_masina`),
  ADD CONSTRAINT `amenzi_ibfk_2` FOREIGN KEY (`sofer_id`) REFERENCES `soferi` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `anvelope`
--
ALTER TABLE `anvelope`
  ADD CONSTRAINT `anvelope_ibfk_1` FOREIGN KEY (`masina_id`) REFERENCES `masini` (`id_masina`);

--
-- Constraints for table `asignari_soferi`
--
ALTER TABLE `asignari_soferi`
  ADD CONSTRAINT `asignari_soferi_ibfk_1` FOREIGN KEY (`sofer_id`) REFERENCES `soferi` (`id`),
  ADD CONSTRAINT `asignari_soferi_ibfk_2` FOREIGN KEY (`masina_id`) REFERENCES `masini` (`id_masina`);

--
-- Constraints for table `asigurari`
--
ALTER TABLE `asigurari`
  ADD CONSTRAINT `asigurari_ibfk_1` FOREIGN KEY (`masina_id`) REFERENCES `masini` (`id_masina`),
  ADD CONSTRAINT `asigurari_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`);

--
-- Constraints for table `combustibil`
--
ALTER TABLE `combustibil`
  ADD CONSTRAINT `combustibil_ibfk_1` FOREIGN KEY (`masina_id`) REFERENCES `masini` (`id_masina`),
  ADD CONSTRAINT `combustibil_ibfk_2` FOREIGN KEY (`sofer_id`) REFERENCES `soferi` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `foaie_parcurs`
--
ALTER TABLE `foaie_parcurs`
  ADD CONSTRAINT `foaie_parcurs_ibfk_1` FOREIGN KEY (`sofer_id`) REFERENCES `soferi` (`id`),
  ADD CONSTRAINT `foaie_parcurs_ibfk_2` FOREIGN KEY (`masina_id`) REFERENCES `masini` (`id_masina`);

--
-- Constraints for table `itp`
--
ALTER TABLE `itp`
  ADD CONSTRAINT `itp_ibfk_1` FOREIGN KEY (`masina_id`) REFERENCES `masini` (`id_masina`);

--
-- Constraints for table `revizii`
--
ALTER TABLE `revizii`
  ADD CONSTRAINT `revizii_ibfk_1` FOREIGN KEY (`masina_id`) REFERENCES `masini` (`id_masina`);

--
-- Constraints for table `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_1` FOREIGN KEY (`masina_id`) REFERENCES `masini` (`id_masina`);

--
-- Constraints for table `viniete`
--
ALTER TABLE `viniete`
  ADD CONSTRAINT `viniete_ibfk_1` FOREIGN KEY (`masina_id`) REFERENCES `masini` (`id_masina`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
