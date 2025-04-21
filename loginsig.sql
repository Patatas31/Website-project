-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2025 at 10:23 AM
-- Server version: 8.0.39
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `loginsig`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$VkqJorFWvjSY/nJfnEcwUOQ80b8Y/nzPd5h7FXxBiipa76g8byQDq', '2025-02-06 08:45:00', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `email`, `phone`, `password`) VALUES
(12, 'JD', 'amaya@gmail.com', '09231234511', '$2y$10$pG9XkG9FCAwAJP158lLARuTeu74camzGKZ.oJbhO0UMRIf7IIpPMC'),
(14, 'Miguel', 'icasiano@gmail.com', '09129765432', '$2y$10$iQGyXjjIHt.kufLIDAGWGum44QE/q28kF.NBkYAPhA5sTnIeTTEMy'),
(15, 'Jeremy', 'rivas@gmail.com', '09121231234', '$2y$10$J8Rq11Wov0AVb.BCNCsgquXwMK3qxs5eS3b65BttH4wyk0E539Uj6'),
(21, 'Dion', 'sobrevilla@gmail.com', '09569967143', '$2y$10$D7B0kHhCFpNMbNP/rSLhxOt2hcDxiL9TJjFb7dvIjIOYYhUnKkFvC');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `item_name`, `price`, `description`, `image_path`) VALUES
(1, 'BITI Shawarma Pita', 129.00, 'Enjoy a perfectly grilled pita wrap stuffed with juicy, seasoned meat, fresh vegetables, and our signature creamy garlic and savory sauces. Every bite is an explosion of flavors that will leave you wanting more!', 'img/shawarma_pita.jpg'),
(2, 'BITI Shawarma Rice', 139.00, 'For a more filling option, indulge in our savory shawarma rice, featuring tender, flavorful meat served over fragrant rice, topped with special sauces for the perfect bite!', 'img/shawarma_rice.jpg'),
(3, 'Solo Pita', 70.00, 'A tasty, grilled pita wrap filled with juicy, seasoned shawarma meat, fresh veggies, and our creamy garlic and savory sauces. Perfect for when you want a quick and flavorful bite without the extra!', 'img/solo_pita.jpg'),
(13, 'Solo Rice', 75.00, 'Enjoy tender shawarma meat served over fragrant rice, topped with our signature sauces. It’s a satisfying, hearty meal when you\'re craving something more filling, yet solo!', 'img/solo_rice.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `customer_id` int NOT NULL,
  `name` varchar(200) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `shipping_option` varchar(50) DEFAULT NULL,
  `delivery_address` text,
  `delivery_notes` text,
  `total_amount` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `name`, `phone`, `item_name`, `price`, `quantity`, `order_date`, `shipping_option`, `delivery_address`, `delivery_notes`, `total_amount`) VALUES
(114, 21, 'Dion', '09569967143', 'BITI Shawarma Pita', 129.00, 5, '2025-02-06 08:46:03', 'delivered', 'Caloocan', 'Spoon and fork', 675.00),
(115, 15, 'Jeremy', '09121231234', 'BITI Shawarma Pita', 129.00, 5, '2025-02-06 08:49:09', 'cancelled', 'commonwealth', 'n/a', 1425.00),
(116, 15, 'Jeremy', '09121231234', 'Solo Rice', 75.00, 10, '2025-02-06 08:49:16', 'cancelled', 'commonwealth', 'n/a', 1425.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
