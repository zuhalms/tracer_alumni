-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2025 at 10:36 PM
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
-- Database: `tracer_alumni_kampus`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_admin` varchar(100) NOT NULL,
  `level` enum('Super Admin','Admin Prodi') DEFAULT 'Admin Prodi',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`id_admin`, `username`, `password`, `nama_admin`, `level`, `created_at`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'Administrator', 'Super Admin', '2025-11-12 15:20:36');

-- --------------------------------------------------------

--
-- Table structure for table `tb_alumni`
--

CREATE TABLE `tb_alumni` (
  `id_alumni` int(11) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `program_studi` varchar(100) NOT NULL,
  `fakultas` varchar(100) DEFAULT NULL,
  `tahun_masuk` year(4) DEFAULT NULL,
  `tahun_lulus` year(4) NOT NULL,
  `email` varchar(50) NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `file_ijazah` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `status_verifikasi` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_alumni`
--

INSERT INTO `tb_alumni` (`id_alumni`, `nim`, `nama_lengkap`, `program_studi`, `fakultas`, `tahun_masuk`, `tahun_lulus`, `email`, `no_hp`, `password`, `file_ijazah`, `alamat`, `status_verifikasi`, `created_at`, `foto`) VALUES
(1, '60200116067', 'Zuhal ', 'Teknik Informatika', 'Sains dan Teknologi', '2016', '2021', 'zuhalsamas@gmail.com', '', '1a1bf07bcec1ffcb51c83d1ab8736ad7', NULL, '', 'Pending', '2025-11-12 16:24:20', 'uploads/foto_1_1762973009.jpg'),
(2, '60200116066', 'Muh. Aznal Baqi', 'Teknik Informatika', 'Sains dan Teknologi', '2016', '2021', 'aznalbaqi@gmail.com', '08123456789', 'a583c08a1adb1306a43731298ce50db1', NULL, 'Makassar', 'Pending', '2025-11-12 21:25:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_kuesioner`
--

CREATE TABLE `tb_kuesioner` (
  `id_kuesioner` int(11) NOT NULL,
  `id_alumni` int(11) DEFAULT NULL,
  `kepuasan_kurikulum` int(1) DEFAULT NULL,
  `kepuasan_dosen` int(1) DEFAULT NULL,
  `kepuasan_fasilitas` int(1) DEFAULT NULL,
  `relevansi_ilmu_kerja` int(1) DEFAULT NULL,
  `kompetensi_bidang` int(1) DEFAULT NULL,
  `saran_perbaikan` text DEFAULT NULL,
  `tanggal_isi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_pekerjaan`
--

CREATE TABLE `tb_pekerjaan` (
  `id_pekerjaan` int(11) NOT NULL,
  `id_alumni` int(11) DEFAULT NULL,
  `status_pekerjaan` enum('Bekerja','Wirausaha','Melanjutkan Studi','Belum Bekerja') NOT NULL,
  `nama_perusahaan` varchar(100) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `bidang_pekerjaan` varchar(100) DEFAULT NULL,
  `tahun_mulai_kerja` year(4) DEFAULT NULL,
  `gaji_pertama` varchar(50) DEFAULT NULL,
  `relevansi_pekerjaan` enum('Sangat Relevan','Relevan','Cukup Relevan','Tidak Relevan') DEFAULT NULL,
  `lama_mendapat_kerja` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `tb_alumni`
--
ALTER TABLE `tb_alumni`
  ADD PRIMARY KEY (`id_alumni`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tb_kuesioner`
--
ALTER TABLE `tb_kuesioner`
  ADD PRIMARY KEY (`id_kuesioner`),
  ADD KEY `id_alumni` (`id_alumni`);

--
-- Indexes for table `tb_pekerjaan`
--
ALTER TABLE `tb_pekerjaan`
  ADD PRIMARY KEY (`id_pekerjaan`),
  ADD KEY `id_alumni` (`id_alumni`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_alumni`
--
ALTER TABLE `tb_alumni`
  MODIFY `id_alumni` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_kuesioner`
--
ALTER TABLE `tb_kuesioner`
  MODIFY `id_kuesioner` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_pekerjaan`
--
ALTER TABLE `tb_pekerjaan`
  MODIFY `id_pekerjaan` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_kuesioner`
--
ALTER TABLE `tb_kuesioner`
  ADD CONSTRAINT `tb_kuesioner_ibfk_1` FOREIGN KEY (`id_alumni`) REFERENCES `tb_alumni` (`id_alumni`) ON DELETE CASCADE;

--
-- Constraints for table `tb_pekerjaan`
--
ALTER TABLE `tb_pekerjaan`
  ADD CONSTRAINT `tb_pekerjaan_ibfk_1` FOREIGN KEY (`id_alumni`) REFERENCES `tb_alumni` (`id_alumni`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
