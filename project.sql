-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 18, 2025 at 04:08 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_cfm`
--

CREATE TABLE `data_cfm` (
  `id` int NOT NULL,
  `carline` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `mesin` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `no` int NOT NULL,
  `applicator` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `man_no` int NOT NULL,
  `kind` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `size` float NOT NULL,
  `knop_spacer` char(1) COLLATE utf8mb4_general_ci NOT NULL,
  `dial` int NOT NULL,
  `no_prog` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_cfm`
--

INSERT INTO `data_cfm` (`id`, `carline`, `mesin`, `no`, `applicator`, `man_no`, `kind`, `size`, `knop_spacer`, `dial`, `no_prog`) VALUES
(21, '841W', 'CM14NH-05', 1, '7116-3153-02', 123, 'CIVUS', 0.5, 'F', 3, 4),
(22, '841W', 'CM14NH-05', 2, '7114-4729-02', 124, 'CIVUS', 0.3, 'F', 1, 27),
(23, '841W', 'CM14NH-05', 3, '7116-5751-02', 125, 'CIVUS', 0.5, 'C', 5, 5),
(24, '841W', 'CM14NH-05', 4, '7017-1180-02', 126, 'CIVUS', 0.3, 'G', 4, 2),
(41, '841W', 'CM14NH-05', 1, '7116-2893-02', 123, 'CIVUS', 0.5, 'F', 3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `data_crimping`
--

CREATE TABLE `data_crimping` (
  `no` int NOT NULL,
  `mesin` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `term` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `wire` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `size` float NOT NULL,
  `acc` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `f_c_h` float NOT NULL,
  `toleransi1` float NOT NULL,
  `1_2_f_c_h` float NOT NULL,
  `r_c_h` float NOT NULL,
  `toleransi2` float NOT NULL,
  `1_2_r_c_h` float NOT NULL,
  `f_c_w_min` float NOT NULL,
  `f_c_w_max` float NOT NULL,
  `r_c_w_min` float NOT NULL,
  `r_c_w_max` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_crimping`
--

INSERT INTO `data_crimping` (`no`, `mesin`, `term`, `wire`, `size`, `acc`, `f_c_h`, `toleransi1`, `1_2_f_c_h`, `r_c_h`, `toleransi2`, `1_2_r_c_h`, `f_c_w_min`, `f_c_w_max`, `r_c_w_min`, `r_c_w_max`) VALUES
(63, 'CM14NH-05', '7114-4729-02', 'CIVUS', 0.35, '-', 0.8, 0.05, 0.02, 1.4, 0.1, 0.05, 1.3, 1.5, 1.4, 1.8),
(64, 'CM14NH-05', '7116-3153-02', 'CIVUS', 0.35, '7172-5625-90', 0.95, 0.05, 0.02, 1.5, 0.1, 0.05, 1.3, 1.5, 1.4, 1.8),
(65, 'CM14NH-05', '7116-5751-02', 'CIVUS', 0.35, '7172-5737-60', 0.95, 0.05, 0.02, 1.8, 0.1, 0.05, 1.3, 1.5, 1.4, 1.8),
(69, 'CM14NH-05', '7017-1180-02', 'CIVUS', 0.35, '-', 0.8, 0.05, 0.02, 1.4, 0.1, 0.05, 1.3, 1.5, 1.4, 1.8),
(70, 'CM14NH-05', '7116-2893-02', 'CIVUS', 0.35, '7172-5625-90', 0.95, 0.05, 0.02, 1.5, 0.1, 0.05, 1.3, 1.5, 1.4, 1.8);

-- --------------------------------------------------------

--
-- Table structure for table `data_kanban`
--

CREATE TABLE `data_kanban` (
  `id` int NOT NULL,
  `machine` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `npg` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `noproc` int NOT NULL,
  `ctrl_no` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `kind` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `size` float NOT NULL,
  `col` char(1) COLLATE utf8mb4_general_ci NOT NULL,
  `c_l` int NOT NULL,
  `term_b` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `strip_b` int NOT NULL,
  `half_strip_b` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `man_b` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `acc_b1` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `term_a` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `strip_a` int NOT NULL,
  `half_strip_a` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `man_a` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `acc_a1` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `qty` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_kanban`
--

INSERT INTO `data_kanban` (`id`, `machine`, `npg`, `noproc`, `ctrl_no`, `kind`, `size`, `col`, `c_l`, `term_b`, `strip_b`, `half_strip_b`, `man_b`, `acc_b1`, `term_a`, `strip_a`, `half_strip_a`, `man_a`, `acc_a1`, `qty`) VALUES
(64, 'AC81NH-01', 'A0490', 490, 'Z082', 'CAVS', 0.5, 'B', 786, '7114-4729-02', 4, '', 'CM14NHS-03', '-', '7017-1180-02', 10, '-', 'CM20NHS-01', '-', 40),
(65, 'AC81NH-03', 'N0001', 1, 'Z263', 'CIVUS', 0.35, 'W', 2781, '7116-3153-02', 4, '-', 'CM14NHS-03', '-', '7116-3153-02', 4, '-', '-', '-', 24),
(66, 'AC81NH-03', 'N0650', 650, 'Z270', 'CIVUS', 0.35, 'L', 2736, '7116-3153-02', 4, '-', '-', '-', '7116-3153-02', 4, '-', 'CM20NHS-01', '-', 24),
(67, 'AC81NH-13', 'N1160', 1160, 'Z291', 'IVSSH-F', 2, 'R', 2368, '7116-5751-02', 4, '-', 'CM14NHS-03', '-', '7116-2893-02', 5, '-', 'CM20NHS-01', '-', 40),
(72, 'AC81NH-01', 'A0490', 600, 'Z082', 'CAVS', 0.5, 'B', 786, '7114-4729-02', 4, '-', 'CM14NHS-03', '-', '7017-1180-02', 10, '-', 'CM20NHS-01', '-', 40),
(73, 'AC81NH-03', 'N0001', 899, 'Z263', 'CIVUS', 0.35, 'W', 2781, '7116-3153-02', 4, '-', '-', '-', '7116-3153-02', 4, '-', 'CM14NHS-03', '-', 24),
(74, 'AC81NH-03', 'N0650', 123, 'Z270', 'CIVUS', 0.35, 'L', 2736, '7116-3153-02', 4, '-', 'CM14NHS-03', '-', '7116-3153-02', 4, '-', '-', '-', 24),
(75, 'AC81NH-13', 'N1160', 4560, 'Z291', 'IVSSH-F', 2, 'R', 2368, '7116-5751-02', 4, '-', '-', '-', '7116-2893-02', 5, '-', 'CM14NHS-03', '-', 40),
(77, 'AC81NH-03', 'N0001', 2, 'Z263', 'CIVUS', 0.35, 'W', 2781, '7116-3153-02', 4, '-', 'CM14NHS-03', '-', '7116-3153-02', 4, '-', '-', '-', 24),
(78, 'AC81NH-03', 'N0650', 3, 'Z270', 'CIVUS', 0.35, 'L', 2736, '7116-3153-02', 4, '-', '-', '-', '7116-3153-02', 4, '-', 'CM14NHS-03', '-', 24),
(79, 'AC81NH-13', 'N1160', 4, 'Z291', 'IVSSH-F', 2, 'R', 2368, '7116-3153-02', 4, '-', '-', '-', '7116-3153-02', 5, '-', 'CM14NHS-03', '-', 40),
(80, 'AC81NH-01', 'A0490', 5, 'Z082', 'CAVS', 0.5, 'B', 786, '7116-3153-02', 4, '-', 'CM14NHS-03', '-', '7116-3153-02', 10, '-', 'CM20NHS-01', '-', 40),
(84, 'AC81NH-01', 'A0490', 6, 'Z082', 'CAVS', 0.5, 'B', 786, '7116-3153-02', 4, '-', 'CM14NHS-03', '-', '7116-3153-02', 10, '-', 'CM20NHS-01', '-', 40),
(85, 'AC81NH-03', 'N0001', 9, 'Z263', 'CIVUS', 0.35, 'W', 2781, '7116-3153-02', 4, '-', '-', '-', '7116-3153-02', 4, '-', 'CM14NHS-03', '-', 24),
(86, 'AC81NH-03', 'N0650', 7, 'Z270', 'CIVUS', 0.35, 'L', 2736, '7116-3153-02', 4, '-', 'CM14NHS-03', '-', '7116-3153-02', 4, '-', '-', '-', 24),
(87, 'AC81NH-13', 'N1160', 8, 'Z291', 'IVSSH-F', 2, 'R', 2368, '7116-3153-02', 4, '-', '-', '-', '7116-3153-02', 5, '-', 'CM14NHS-03', '-', 40);

-- --------------------------------------------------------

--
-- Table structure for table `data_lko`
--

CREATE TABLE `data_lko` (
  `id` int NOT NULL,
  `carline` varchar(255) DEFAULT NULL,
  `mesin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `time` time DEFAULT NULL,
  `shift` varchar(50) DEFAULT NULL,
  `noIssue` varchar(255) DEFAULT NULL,
  `scanKanban` varchar(255) DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `kind` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `col` varchar(255) DEFAULT NULL,
  `terminal` varchar(255) DEFAULT NULL,
  `lotTerminal` varchar(255) DEFAULT NULL,
  `f_c_h` varchar(255) DEFAULT NULL,
  `r_c_h` varchar(255) DEFAULT NULL,
  `f_c_w` varchar(255) DEFAULT NULL,
  `r_c_w` varchar(255) DEFAULT NULL,
  `c_l` varchar(255) DEFAULT NULL,
  `kodeDefect` varchar(255) DEFAULT NULL,
  `qtyM` int NOT NULL,
  `code_error` varchar(50) NOT NULL,
  `downtime` time NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `data_lko`
--

INSERT INTO `data_lko` (`id`, `carline`, `mesin`, `time`, `shift`, `noIssue`, `scanKanban`, `qty`, `kind`, `size`, `col`, `terminal`, `lotTerminal`, `f_c_h`, `r_c_h`, `f_c_w`, `r_c_w`, `c_l`, `kodeDefect`, `qtyM`, `code_error`, `downtime`, `created_at`) VALUES
(553, '841W', 'CM14NH-05', '15:49:36', 'A', '7', '007', 24, 'CIVUS', '0.35', 'L', '7116-3153-02', '7', '1', '1.5', '1.5', '1.5', '2736', '-', 0, '-', '00:00:00', '2025-03-17 09:18:19'),
(554, '841W', 'CM14NH-05', '15:49:36', 'A', '8', '008', 40, 'IVSSH-F', '2', 'R', '7116-3153-02', '8', '1', '1.5', '1.5', '1.5', '2368', '-', 0, '-', '00:00:00', '2025-03-17 09:18:30'),
(555, '841W', 'CM14NH-05', '15:49:36', 'A', '9', '009', 24, 'CIVUS', '0.35', 'W', '7116-3153-02', '9', '1', '1.5', '1.5', '1.5', '2781', '-', 0, '-', '00:00:00', '2025-03-17 09:18:42'),
(556, '841W', 'CM14NH-05', '15:49:36', 'A', '123', '0123', 24, 'CIVUS', '0.35', 'L', '7116-3153-02', '10', '1', '1.5', '1.5', '1.5', '2736', '-', 0, '-', '00:00:00', '2025-03-17 09:19:06');

-- --------------------------------------------------------

--
-- Table structure for table `data_stroke`
--

CREATE TABLE `data_stroke` (
  `no` int NOT NULL,
  `carline` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `mesin` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `applicator` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `max_stroke` int NOT NULL,
  `current_stroke` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_stroke`
--

INSERT INTO `data_stroke` (`no`, `carline`, `mesin`, `applicator`, `max_stroke`, `current_stroke`) VALUES
(1, '841W', 'CM14NH-05', '7114-4729-02', 200000, 1429),
(2, '841W', 'CM14NH-05', '7116-5751-02', 200000, 1057),
(6, '841W', 'CM14NH-05', '7116-3153-02', 200000, 2814),
(7, '841W', 'CM14NH-05', '7116-3154-02', 200000, 100),
(8, '841W', 'CM14NH-05', '7017-1180-02', 200000, 457),
(9, '841W', 'CM14NH-05', '7116-2893-02', 200000, 902);

-- --------------------------------------------------------

--
-- Table structure for table `defect`
--

CREATE TABLE `defect` (
  `no` int NOT NULL,
  `item_defect` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `defect`
--

INSERT INTO `defect` (`no`, `item_defect`) VALUES
(1, 'Wrong colour wire'),
(2, 'Wrong kind wire'),
(3, 'Wrong size wire'),
(4, 'Wrong C/L'),
(5, 'Damage insulation'),
(6, 'Insulation tercrimping'),
(7, 'Stripping tidak rata'),
(8, 'Insulation mundur'),
(9, 'Missing crimping'),
(10, 'Salah terminal'),
(11, 'Terminal berkarat'),
(12, 'Terminal penyok'),
(13, 'Terminal terpotong'),
(14, 'Terminal open'),
(15, 'Terminal melintir'),
(16, 'Stabilizer penyok'),
(17, 'Bridge keluar spect'),
(18, 'Flash'),
(19, 'Core maju'),
(20, 'Core mundur'),
(21, 'Standart front'),
(22, 'Standart rear keluar spect'),
(23, 'Terminal tergores'),
(24, 'Fraying core'),
(25, 'Crimping tanpa core'),
(26, 'Crimping tanpa stripping'),
(27, 'Terminal dengan benda lain'),
(28, 'Terminal bent (up/down)'),
(29, 'Front retak'),
(30, 'Rear unbalance'),
(31, 'Bellmouth tidak standart'),
(32, 'Missing seal'),
(33, 'Salah seal'),
(34, 'Seal tercrimping'),
(35, 'Seal mundur'),
(36, 'Seal terbalik'),
(37, 'Damage seal'),
(38, 'Cut core'),
(39, 'Core berkarat'),
(40, 'Core tidak rata');

-- --------------------------------------------------------

--
-- Table structure for table `downtime`
--

CREATE TABLE `downtime` (
  `id` int NOT NULL,
  `kode` char(2) COLLATE utf8mb4_general_ci NOT NULL,
  `item` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `downtime`
--

INSERT INTO `downtime` (`id`, `kode`, `item`) VALUES
(1, 'a', 'NK (Nunggu Kanban)'),
(2, 'b', 'NM (Nunggu Material)'),
(3, 'c', 'Down time'),
(4, 'c1', 'Repair mesin by MTC'),
(5, 'c2', 'Menunggu MTC'),
(6, 'c3', 'Tensile'),
(7, 'd', 'Other'),
(8, 'd1', 'Briefing'),
(9, 'd2', 'Ketoilet '),
(10, 'd3', ' Keklinik'),
(11, 'd4', 'ljin minum'),
(12, 'd5', '4M transisi');

-- --------------------------------------------------------

--
-- Table structure for table `error_codes`
--

CREATE TABLE `error_codes` (
  `id` int NOT NULL,
  `kode` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `item` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nik` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `name`, `nik`) VALUES
(1, 'operator', 'feb', '123'),
(2, 'teknisi', 'bel', '321'),
(23, 'operator', 'boy', '090'),
(24, 'operator', 'king', '0857'),
(25, 'operator', 'awq', '222');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_cfm`
--
ALTER TABLE `data_cfm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_crimping`
--
ALTER TABLE `data_crimping`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `data_kanban`
--
ALTER TABLE `data_kanban`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_lko`
--
ALTER TABLE `data_lko`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_stroke`
--
ALTER TABLE `data_stroke`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `defect`
--
ALTER TABLE `defect`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `downtime`
--
ALTER TABLE `downtime`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `error_codes`
--
ALTER TABLE `error_codes`
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
-- AUTO_INCREMENT for table `data_cfm`
--
ALTER TABLE `data_cfm`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `data_crimping`
--
ALTER TABLE `data_crimping`
  MODIFY `no` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `data_kanban`
--
ALTER TABLE `data_kanban`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `data_lko`
--
ALTER TABLE `data_lko`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=557;

--
-- AUTO_INCREMENT for table `data_stroke`
--
ALTER TABLE `data_stroke`
  MODIFY `no` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `defect`
--
ALTER TABLE `defect`
  MODIFY `no` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `downtime`
--
ALTER TABLE `downtime`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `error_codes`
--
ALTER TABLE `error_codes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
