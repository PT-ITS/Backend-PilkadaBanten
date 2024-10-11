-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2024 at 11:18 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `backend_pilkada_banten`
--

-- --------------------------------------------------------

--
-- Table structure for table `master_kabupaten`
--

CREATE TABLE `master_kabupaten` (
  `id` char(4) NOT NULL,
  `provinsi_id` char(2) NOT NULL,
  `name` tinytext NOT NULL,
  `id_jenis` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `master_kabupaten`
--

INSERT INTO `master_kabupaten` (`id`, `provinsi_id`, `name`, `id_jenis`) VALUES
('3601', '36', 'KAB. PANDEGLANG', 1),
('3602', '36', 'KAB. LEBAK', 1),
('3603', '36', 'KAB. TANGERANG', 1),
('3604', '36', 'KAB. SERANG', 1),
('3671', '36', 'KOTA TANGERANG', 2),
('3672', '36', 'KOTA CILEGON', 2),
('3673', '36', 'KOTA SERANG', 2),
('3674', '36', 'KOTA TANGERANG SELATAN', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `master_kabupaten`
--
ALTER TABLE `master_kabupaten`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
