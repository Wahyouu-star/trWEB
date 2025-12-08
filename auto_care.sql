-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2025 at 03:13 PM
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
-- Database: `auto_care`
--

-- --------------------------------------------------------

--
-- Table structure for table `consultation_log`
--

CREATE TABLE `consultation_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mekanik_name` varchar(100) NOT NULL,
  `mekanik_bidang` varchar(100) NOT NULL,
  `chat_key` varchar(255) NOT NULL,
  `last_message_time` datetime NOT NULL,
  `status` varchar(50) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultation_log`
--

INSERT INTO `consultation_log` (`id`, `user_id`, `mekanik_name`, `mekanik_bidang`, `chat_key`, `last_message_time`, `status`) VALUES
(1, 1, 'Budi Sentosa', 'Body Repair', 'chat_history_chrissBudiSentosa', '2025-12-08 19:26:15', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text NOT NULL,
  `payment_method` varchar(50) DEFAULT 'Transfer Bank',
  `status` varchar(50) DEFAULT 'Menunggu Pembayaran',
  `order_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `shipping_address`, `payment_method`, `status`, `order_date`) VALUES
(1, 1, 120000.00, 'salatiga', 'Transfer Bank', 'Menunggu Pembayaran', '2025-12-08 08:14:57'),
(2, 1, 260000.00, 'Kerban', 'Transfer Bank', 'Menunggu Pembayaran', '2025-12-08 09:31:53'),
(3, 1, 300000.00, 'demak', 'Transfer Bank', 'Menunggu Pembayaran', '2025-12-08 09:34:39'),
(4, 1, 250000.00, 'Tingkir', 'Transfer Bank', 'Menunggu Pembayaran', '2025-12-08 09:37:42');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `product_name`, `unit_price`, `quantity`) VALUES
(1, 1, 3, 'Filter Oli Diameter 80 mm', 10000.00, 2),
(2, 1, 4, 'Shockbreaker 45 cm', 100000.00, 1),
(3, 2, 2, 'Busi Gap 0,8 mm', 10000.00, 1),
(4, 2, 6, 'Alternator 12V', 250000.00, 1),
(5, 3, 10, 'Radiator Full Set', 300000.00, 1),
(6, 4, 6, 'Alternator 12V', 250000.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pengingat`
--

CREATE TABLE `pengingat` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `jenis_servis` varchar(100) DEFAULT NULL,
  `jarak` int(11) DEFAULT NULL,
  `durasi` int(11) DEFAULT NULL,
  `waktu` time DEFAULT NULL,
  `interval_servis` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengingat`
--

INSERT INTO `pengingat` (`id`, `user_id`, `jenis_servis`, `jarak`, `durasi`, `waktu`, `interval_servis`, `created_at`) VALUES
(2, 1, 'Ganti Oli', 10000, 2, '10:33:00', '6 bulan / 10000 km', '2025-12-08 02:32:36');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image_path`, `stock`) VALUES
(1, 'Aki 12 volt, 45 Ah', 100000, 'IMG/aki.jpg', 10),
(2, 'Busi Gap 0,8 mm', 10000, 'IMG/busi.jpg', 10),
(3, 'Filter Oli Diameter 80 mm', 10000, 'IMG/filteroli.jpg', 10),
(4, 'Shockbreaker 45 cm', 100000, 'IMG/shockbreaker.jpg', 10),
(5, 'Knalpot Diameter 2.5 inch', 100000, 'IMG/knalpot.jpg', 10),
(6, 'Alternator 12V', 250000, 'IMG/alternator.jpg', 10),
(7, 'Fanbelt 90 cm', 50000, 'IMG/fanbelt.jpg', 10),
(8, 'Filter Udara', 35000, 'IMG/filterudara.jpg', 10),
(9, 'Kampas Rem Depan', 75000, 'IMG/kampasrem.jpg', 10),
(10, 'Radiator Full Set', 300000, 'IMG/radiator.jpg', 10),
(11, 'Ban', 1000000, 'IMG/1765189199_ban.jpg', 10);

-- --------------------------------------------------------

--
-- Table structure for table `riwayat`
--

CREATE TABLE `riwayat` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `jenis_servis` varchar(100) DEFAULT NULL,
  `jarak` int(11) DEFAULT NULL,
  `durasi` int(11) DEFAULT NULL,
  `waktu` time DEFAULT NULL,
  `interval_servis` varchar(50) DEFAULT NULL,
  `selesai_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riwayat`
--

INSERT INTO `riwayat` (`id`, `user_id`, `jenis_servis`, `jarak`, `durasi`, `waktu`, `interval_servis`, `selesai_pada`) VALUES
(1, 1, 'Ganti Oli', 3000, 1, '13:30:00', '3 bulan / 5000 km', '2025-12-07 16:21:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `tgl_lahir` date DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `telp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `created_at`, `tgl_lahir`, `gender`, `telp`, `email`) VALUES
(1, 'Christian Wahyu', 'chriss', '$2y$10$OtnX5zlyA6yTJCAuvZ.Ij.RrNW7iO/lWwPR32SVbQ02oc8z/0Ljxy', '2025-12-07 16:08:18', '1945-08-17', 'Perempuan', '986778567', 'chriss@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `consultation_log`
--
ALTER TABLE `consultation_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `pengingat`
--
ALTER TABLE `pengingat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `riwayat`
--
ALTER TABLE `riwayat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `consultation_log`
--
ALTER TABLE `consultation_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pengingat`
--
ALTER TABLE `pengingat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `riwayat`
--
ALTER TABLE `riwayat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `pengingat`
--
ALTER TABLE `pengingat`
  ADD CONSTRAINT `pengingat_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `riwayat`
--
ALTER TABLE `riwayat`
  ADD CONSTRAINT `riwayat_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
