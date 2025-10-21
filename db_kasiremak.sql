-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2025 at 05:24 PM
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
-- Database: `db_kasiremak`
--

-- --------------------------------------------------------

--
-- Table structure for table `table_barang`
--

CREATE TABLE `table_barang` (
  `id_barang` varchar(100) NOT NULL,
  `barcode` varchar(20) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `satuan` varchar(20) NOT NULL,
  `stock_minimal` int(11) NOT NULL,
  `gambar` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `table_barang`
--

INSERT INTO `table_barang` (`id_barang`, `barcode`, `nama_barang`, `harga_beli`, `harga_jual`, `stock`, `satuan`, `stock_minimal`, `gambar`) VALUES
('BRG-014', '8993189270291', 'Minyak Goreng  Fetta', 14000, 15000, 6, 'botol', 0, '1760085285-7001.jpg'),
('BRG-015', '998866109864', 'Mama Lemon', 12000, 13000, 3, 'piece', 0, '1760085480-4676.jpg'),
('BRG-016', '8991111109107', 'Susu Kental Manis Indomilk', 19000, 20000, 0, 'kaleng', 0, '1760085626-2581.jpg'),
('BRG-017', '8968001010', 'Indomie Ayam Bawang', 3500, 4000, 1, 'piece', 0, '1760085800-9429.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_beli_detail`
--

CREATE TABLE `tbl_beli_detail` (
  `id` int(11) NOT NULL,
  `no_beli` varchar(20) NOT NULL,
  `tgl_beli` date NOT NULL,
  `kode_brg` varchar(10) NOT NULL,
  `nama_brg` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `jml_harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_beli_head`
--

CREATE TABLE `tbl_beli_head` (
  `no_beli` varchar(20) NOT NULL,
  `tgl_beli` date NOT NULL,
  `suplier` varchar(255) NOT NULL,
  `total` int(11) NOT NULL,
  `keterangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_beli_head`
--

INSERT INTO `tbl_beli_head` (`no_beli`, `tgl_beli`, `suplier`, `total`, `keterangan`) VALUES
('PB0001', '2025-10-06', 'PT Sembako', 0, ''),
('PB0004', '2025-10-06', 'PT Sembako', 0, ''),
('PB0005', '2025-10-09', 'PT Sembako', 0, ''),
('PB0006', '2025-10-10', '', 57000, ''),
('PB0007', '2025-10-10', '', 54000, ''),
('PB0008', '2025-10-10', '', 34000, ''),
('PB0009', '2025-10-10', '', 7000, ''),
('PB0010', '2025-10-10', '', 15000, ''),
('PB0011', '2025-10-10', '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE `tbl_customer` (
  `id_customer` int(11) NOT NULL,
  `nama` varchar(256) NOT NULL,
  `telpon` varchar(25) NOT NULL,
  `deskripsi` varchar(256) NOT NULL,
  `alamat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jual_detail`
--

CREATE TABLE `tbl_jual_detail` (
  `id` int(11) NOT NULL,
  `no_jual` varchar(20) NOT NULL,
  `tgl_jual` date NOT NULL,
  `barcode` varchar(100) NOT NULL,
  `nama_brg` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `jml_harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_jual_detail`
--

INSERT INTO `tbl_jual_detail` (`id`, `no_jual`, `tgl_jual`, `barcode`, `nama_brg`, `qty`, `harga_jual`, `jml_harga`) VALUES
(1, 'PJ0001', '2025-10-10', '8993189270291', 'Minyak Goreng  Fetta', 1, 15000, 15000),
(2, 'PJ0006', '2025-10-10', '8991111109107', '', 1, 0, 0),
(3, 'PJ0007', '2025-10-10', '998866109864', '', 1, 13000, 13000),
(4, 'PJ0008', '2025-10-10', '8991111109107', 'Susu Kental Manis Indomilk', 1, 20000, 20000),
(5, 'PJ0009', '2025-10-10', '8968001010', 'Indomie Ayam Bawang', 1, 4000, 4000),
(6, 'PJ0010', '2025-10-10', '998866109864', 'Mama Lemon', 1, 13000, 13000),
(7, 'PJ0011', '2025-10-10', '998866109864', 'Mama Lemon', 1, 13000, 13000),
(8, '<br /><b>Notice</b>:', '2025-10-10', '998866109864', 'Mama Lemon', 1, 13000, 13000),
(9, '<br /><b>Notice</b>:', '2025-10-10', '8991111109107', 'Susu Kental Manis Indomilk', 1, 20000, 20000),
(10, 'PJ0012', '2025-10-10', '8991111109107', 'Susu Kental Manis Indomilk', 1, 20000, 20000),
(11, 'PJ0013', '2025-10-10', '8991111109107', 'Susu Kental Manis Indomilk', 1, 20000, 20000),
(12, 'PJ0014', '2025-10-10', '8993189270291', 'Minyak Goreng  Fetta', 1, 15000, 15000),
(13, 'PJ0015', '2025-10-10', '998866109864', 'Mama Lemon', 1, 13000, 13000);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jual_head`
--

CREATE TABLE `tbl_jual_head` (
  `no_jual` varchar(20) NOT NULL,
  `tgl_jual` date NOT NULL,
  `customer` varchar(255) NOT NULL,
  `total` int(11) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `jml_bayar` int(11) NOT NULL,
  `kembalian` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_jual_head`
--

INSERT INTO `tbl_jual_head` (`no_jual`, `tgl_jual`, `customer`, `total`, `keterangan`, `jml_bayar`, `kembalian`) VALUES
('<br /><b>Notice</b>:', '2025-10-10', '', 13000, '', 0, 0),
('PJ0001', '2025-10-10', '', 48000, '', 0, 0),
('PJ0002', '2025-10-10', '', 34000, '', 0, 0),
('PJ0003', '2025-10-10', '', 4000, '', 0, 0),
('PJ0004', '2025-10-10', '', 20000, '', 0, 0),
('PJ0005', '2025-10-10', '', 15000, '', 0, 0),
('PJ0006', '2025-10-10', '', 20000, '', 0, 0),
('PJ0007', '2025-10-10', '', 13000, '', 0, 0),
('PJ0008', '2025-10-10', '', 20000, '', 0, 0),
('PJ0009', '2025-10-10', '', 4000, '', 0, 0),
('PJ0010', '2025-10-10', '', 13000, '', 0, 0),
('PJ0011', '2025-10-10', '', 13000, '', 0, 0),
('PJ0012', '2025-10-10', '', 20000, '', 0, 0),
('PJ0013', '2025-10-10', '', 20000, '', 0, 0),
('PJ0014', '2025-10-10', '', 15000, '', 0, 0),
('PJ0015', '2025-10-10', '', 13000, '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_supplier`
--

CREATE TABLE `tbl_supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama` varchar(256) NOT NULL,
  `telpon` varchar(25) NOT NULL,
  `deskripsi` varchar(256) NOT NULL,
  `alamat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_supplier`
--

INSERT INTO `tbl_supplier` (`id_supplier`, `nama`, `telpon`, `deskripsi`, `alamat`) VALUES
(1, 'PT Sembako', '085817952659', 'Toko Sembako', 'Jl.Jati Tanjakakan'),
(5, 'PT INDOMARCO', '083897536861', '', 'Jl.Mauk');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `level` enum('1','2','3') NOT NULL COMMENT '1=administrator, 2=supervisor, 3=operator',
  `foto` varchar(255) DEFAULT 'default.webp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`userid`, `username`, `fullname`, `password`, `address`, `level`, `foto`) VALUES
(1, 'admin222@gmail.com', 'aji', '$2y$10$EXWKLkeOIhx2S3K5iKaO.uhJYDXusZnaLZ8ijXjI96wyAse60.gXq', 'jati tanjakan', '1', '885-user.png'),
(2, 'kasir', 'amalia', '$2y$10$uoSc4EMwBJbESd6Cf6m0luWXDvxabG7SfD2IaEZjCPFtdDCrFxrQi', 'Rajeg', '3', 'default.webp');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `table_barang`
--
ALTER TABLE `table_barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `tbl_beli_detail`
--
ALTER TABLE `tbl_beli_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_beli_head`
--
ALTER TABLE `tbl_beli_head`
  ADD PRIMARY KEY (`no_beli`);

--
-- Indexes for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indexes for table `tbl_jual_detail`
--
ALTER TABLE `tbl_jual_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_jual_head`
--
ALTER TABLE `tbl_jual_head`
  ADD PRIMARY KEY (`no_jual`);

--
-- Indexes for table `tbl_supplier`
--
ALTER TABLE `tbl_supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_beli_detail`
--
ALTER TABLE `tbl_beli_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_jual_detail`
--
ALTER TABLE `tbl_jual_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_supplier`
--
ALTER TABLE `tbl_supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
