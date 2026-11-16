-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 16, 2026 at 06:39 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sistem_pajak`
--

-- --------------------------------------------------------

--
-- Table structure for table `demo_data_state`
--

CREATE TABLE `demo_data_state` (
  `seed_key` varchar(50) NOT NULL,
  `seeded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `demo_data_state`
--

INSERT INTO `demo_data_state` (`seed_key`, `seeded_at`) VALUES
('initial_demo_data', '2026-11-16 05:01:04');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_pajak`
--

CREATE TABLE `jenis_pajak` (
  `id_jenis_pajak` int NOT NULL,
  `kode_pajak` varchar(20) NOT NULL,
  `nama_pajak` varchar(100) NOT NULL,
  `deskripsi` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jenis_pajak`
--

INSERT INTO `jenis_pajak` (`id_jenis_pajak`, `kode_pajak`, `nama_pajak`, `deskripsi`) VALUES
(1, 'PPH21', 'Pajak Penghasilan Pasal 21', 'Contoh pajak atas gaji, upah, honorarium, dan penghasilan lain orang pribadi.'),
(2, 'PPH23', 'Pajak Penghasilan Pasal 23', 'Contoh pajak atas penghasilan dari modal, jasa, atau hadiah tertentu.'),
(3, 'PPHBADAN', 'Pajak Penghasilan Badan', 'Contoh pajak atas penghasilan yang diterima atau diperoleh badan usaha.'),
(4, 'PPN', 'Pajak Pertambahan Nilai', 'Contoh pajak atas konsumsi barang dan jasa kena pajak.');

-- --------------------------------------------------------

--
-- Table structure for table `kewajiban_pajak`
--

CREATE TABLE `kewajiban_pajak` (
  `id_kewajiban` int NOT NULL,
  `id_wajib_pajak` int NOT NULL,
  `id_jenis_pajak` int NOT NULL,
  `masa_pajak` varchar(20) NOT NULL,
  `status_kewajiban` enum('Aktif','Tidak Aktif') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kewajiban_pajak`
--

INSERT INTO `kewajiban_pajak` (`id_kewajiban`, `id_wajib_pajak`, `id_jenis_pajak`, `masa_pajak`, `status_kewajiban`) VALUES
(1, 1, 1, '10/2026', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `objek_pajak`
--

CREATE TABLE `objek_pajak` (
  `id_objek` int NOT NULL,
  `id_wajib_pajak` int NOT NULL,
  `nama_objek` varchar(150) NOT NULL,
  `kategori_objek` varchar(100) NOT NULL,
  `nilai_objek` decimal(15,2) DEFAULT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `objek_pajak`
--

INSERT INTO `objek_pajak` (`id_objek`, `id_wajib_pajak`, `nama_objek`, `kategori_objek`, `nilai_objek`, `keterangan`) VALUES
(1, 3, 'Kantor PT Contoh Nusantara', 'Tanah dan Bangunan', '1500000000.00', 'Contoh aset usaha yang dilaporkan oleh wajib pajak badan.');

-- --------------------------------------------------------

--
-- Table structure for table `pelaporan_pajak`
--

CREATE TABLE `pelaporan_pajak` (
  `id_pelaporan` int NOT NULL,
  `id_kewajiban` int NOT NULL,
  `tanggal_lapor` date NOT NULL,
  `nilai_pajak` decimal(15,2) NOT NULL,
  `status_laporan` enum('Draft','Disampaikan','Perlu Perbaikan') DEFAULT 'Draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pelaporan_pajak`
--

INSERT INTO `pelaporan_pajak` (`id_pelaporan`, `id_kewajiban`, `tanggal_lapor`, `nilai_pajak`, `status_laporan`) VALUES
(1, 1, '2026-11-05', '1250000.00', 'Disampaikan');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran_pajak`
--

CREATE TABLE `pembayaran_pajak` (
  `id_pembayaran` int NOT NULL,
  `id_pelaporan` int NOT NULL,
  `tanggal_bayar` datetime DEFAULT NULL,
  `jumlah_bayar` decimal(15,2) NOT NULL,
  `metode_bayar` varchar(50) DEFAULT NULL,
  `nomor_bukti` varchar(60) DEFAULT NULL,
  `status_bayar` enum('Menunggu','Berhasil','Gagal') DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pembayaran_pajak`
--

INSERT INTO `pembayaran_pajak` (`id_pembayaran`, `id_pelaporan`, `tanggal_bayar`, `jumlah_bayar`, `metode_bayar`, `nomor_bukti`, `status_bayar`) VALUES
(1, 1, '2026-11-10 10:30:00', '1250000.00', 'Transfer Bank', 'BPN-DEMO-20261110-001', 'Berhasil');

-- --------------------------------------------------------

--
-- Table structure for table `pemeriksaan_pajak`
--

CREATE TABLE `pemeriksaan_pajak` (
  `id_pemeriksaan` int NOT NULL,
  `id_wajib_pajak` int NOT NULL,
  `id_petugas` int NOT NULL,
  `tanggal_pemeriksaan` date NOT NULL,
  `hasil_pemeriksaan` text,
  `status_pemeriksaan` enum('Proses','Selesai') DEFAULT 'Proses'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pemeriksaan_pajak`
--

INSERT INTO `pemeriksaan_pajak` (`id_pemeriksaan`, `id_wajib_pajak`, `id_petugas`, `tanggal_pemeriksaan`, `hasil_pemeriksaan`, `status_pemeriksaan`) VALUES
(1, 3, 1, '2026-11-12', 'Contoh pemeriksaan dokumen dan kesesuaian pelaporan pajak.', 'Proses');

-- --------------------------------------------------------

--
-- Table structure for table `perusahaan`
--

CREATE TABLE `perusahaan` (
  `id_perusahaan` int NOT NULL,
  `id_wajib_pajak` int NOT NULL,
  `nama_pt` varchar(150) NOT NULL,
  `bentuk_badan_usaha` varchar(50) NOT NULL,
  `bidang_usaha` varchar(100) DEFAULT NULL,
  `tanggal_berdiri` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `perusahaan`
--

INSERT INTO `perusahaan` (`id_perusahaan`, `id_wajib_pajak`, `nama_pt`, `bentuk_badan_usaha`, `bidang_usaha`, `tanggal_berdiri`) VALUES
(1, 3, 'PT Contoh Nusantara', 'Perseroan Terbatas', 'Perdagangan umum', '2018-03-15');

-- --------------------------------------------------------

--
-- Table structure for table `petugas_pajak`
--

CREATE TABLE `petugas_pajak` (
  `id_petugas` int NOT NULL,
  `nama_petugas` varchar(100) NOT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `jabatan` varchar(60) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `petugas_pajak`
--

INSERT INTO `petugas_pajak` (`id_petugas`, `nama_petugas`, `nip`, `jabatan`, `email`, `no_telepon`) VALUES
(1, 'Andi Pratama', '198501012010011001', 'Account Representative', 'andi.pratama@example.test', '081200000101'),
(2, 'Siti Rahmawati', '199002022015022002', 'Pemeriksa Pajak', 'siti.rahmawati@example.test', '081200000102');

-- --------------------------------------------------------

--
-- Table structure for table `wajib_pajak`
--

CREATE TABLE `wajib_pajak` (
  `id_wajib_pajak` int NOT NULL,
  `npwp` varchar(30) NOT NULL,
  `nama_wajib_pajak` varchar(150) NOT NULL,
  `jenis_wajib_pajak` enum('Orang Pribadi','Badan') NOT NULL,
  `alamat` text,
  `email` varchar(100) DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `wajib_pajak`
--

INSERT INTO `wajib_pajak` (`id_wajib_pajak`, `npwp`, `nama_wajib_pajak`, `jenis_wajib_pajak`, `alamat`, `email`, `no_telepon`) VALUES
(1, '12.345.678.9-012.000', 'Budi Santoso', 'Orang Pribadi', 'Jl. Melati No. 10, Bandung', 'budi.santoso@example.test', '081200000201'),
(2, '23.456.789.0-123.000', 'Maya Putri', 'Orang Pribadi', 'Jl. Kenanga No. 5, Jakarta', 'maya.putri@example.test', '081200000202'),
(3, '01.234.567.8-901.000', 'PT Contoh Nusantara', 'Badan', 'Jl. Sudirman No. 25, Jakarta', 'pajak@contoh-nusantara.example.test', '0215550101');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `demo_data_state`
--
ALTER TABLE `demo_data_state`
  ADD PRIMARY KEY (`seed_key`);

--
-- Indexes for table `jenis_pajak`
--
ALTER TABLE `jenis_pajak`
  ADD PRIMARY KEY (`id_jenis_pajak`),
  ADD UNIQUE KEY `kode_pajak` (`kode_pajak`);

--
-- Indexes for table `kewajiban_pajak`
--
ALTER TABLE `kewajiban_pajak`
  ADD PRIMARY KEY (`id_kewajiban`),
  ADD KEY `id_wajib_pajak` (`id_wajib_pajak`),
  ADD KEY `id_jenis_pajak` (`id_jenis_pajak`);

--
-- Indexes for table `objek_pajak`
--
ALTER TABLE `objek_pajak`
  ADD PRIMARY KEY (`id_objek`),
  ADD KEY `id_wajib_pajak` (`id_wajib_pajak`);

--
-- Indexes for table `pelaporan_pajak`
--
ALTER TABLE `pelaporan_pajak`
  ADD PRIMARY KEY (`id_pelaporan`),
  ADD KEY `id_kewajiban` (`id_kewajiban`);

--
-- Indexes for table `pembayaran_pajak`
--
ALTER TABLE `pembayaran_pajak`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD UNIQUE KEY `nomor_bukti` (`nomor_bukti`),
  ADD KEY `id_pelaporan` (`id_pelaporan`);

--
-- Indexes for table `pemeriksaan_pajak`
--
ALTER TABLE `pemeriksaan_pajak`
  ADD PRIMARY KEY (`id_pemeriksaan`),
  ADD KEY `id_wajib_pajak` (`id_wajib_pajak`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indexes for table `perusahaan`
--
ALTER TABLE `perusahaan`
  ADD PRIMARY KEY (`id_perusahaan`),
  ADD UNIQUE KEY `id_wajib_pajak` (`id_wajib_pajak`);

--
-- Indexes for table `petugas_pajak`
--
ALTER TABLE `petugas_pajak`
  ADD PRIMARY KEY (`id_petugas`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indexes for table `wajib_pajak`
--
ALTER TABLE `wajib_pajak`
  ADD PRIMARY KEY (`id_wajib_pajak`),
  ADD UNIQUE KEY `npwp` (`npwp`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jenis_pajak`
--
ALTER TABLE `jenis_pajak`
  MODIFY `id_jenis_pajak` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kewajiban_pajak`
--
ALTER TABLE `kewajiban_pajak`
  MODIFY `id_kewajiban` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `objek_pajak`
--
ALTER TABLE `objek_pajak`
  MODIFY `id_objek` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pelaporan_pajak`
--
ALTER TABLE `pelaporan_pajak`
  MODIFY `id_pelaporan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pembayaran_pajak`
--
ALTER TABLE `pembayaran_pajak`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pemeriksaan_pajak`
--
ALTER TABLE `pemeriksaan_pajak`
  MODIFY `id_pemeriksaan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `perusahaan`
--
ALTER TABLE `perusahaan`
  MODIFY `id_perusahaan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `petugas_pajak`
--
ALTER TABLE `petugas_pajak`
  MODIFY `id_petugas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wajib_pajak`
--
ALTER TABLE `wajib_pajak`
  MODIFY `id_wajib_pajak` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kewajiban_pajak`
--
ALTER TABLE `kewajiban_pajak`
  ADD CONSTRAINT `kewajiban_pajak_ibfk_1` FOREIGN KEY (`id_wajib_pajak`) REFERENCES `wajib_pajak` (`id_wajib_pajak`) ON DELETE CASCADE,
  ADD CONSTRAINT `kewajiban_pajak_ibfk_2` FOREIGN KEY (`id_jenis_pajak`) REFERENCES `jenis_pajak` (`id_jenis_pajak`) ON DELETE CASCADE;

--
-- Constraints for table `objek_pajak`
--
ALTER TABLE `objek_pajak`
  ADD CONSTRAINT `objek_pajak_ibfk_1` FOREIGN KEY (`id_wajib_pajak`) REFERENCES `wajib_pajak` (`id_wajib_pajak`) ON DELETE CASCADE;

--
-- Constraints for table `pelaporan_pajak`
--
ALTER TABLE `pelaporan_pajak`
  ADD CONSTRAINT `pelaporan_pajak_ibfk_1` FOREIGN KEY (`id_kewajiban`) REFERENCES `kewajiban_pajak` (`id_kewajiban`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran_pajak`
--
ALTER TABLE `pembayaran_pajak`
  ADD CONSTRAINT `pembayaran_pajak_ibfk_1` FOREIGN KEY (`id_pelaporan`) REFERENCES `pelaporan_pajak` (`id_pelaporan`) ON DELETE CASCADE;

--
-- Constraints for table `pemeriksaan_pajak`
--
ALTER TABLE `pemeriksaan_pajak`
  ADD CONSTRAINT `pemeriksaan_pajak_ibfk_1` FOREIGN KEY (`id_wajib_pajak`) REFERENCES `wajib_pajak` (`id_wajib_pajak`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemeriksaan_pajak_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `petugas_pajak` (`id_petugas`) ON DELETE CASCADE;

--
-- Constraints for table `perusahaan`
--
ALTER TABLE `perusahaan`
  ADD CONSTRAINT `perusahaan_ibfk_1` FOREIGN KEY (`id_wajib_pajak`) REFERENCES `wajib_pajak` (`id_wajib_pajak`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
