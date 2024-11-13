-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 13, 2024 at 02:33 AM
-- Server version: 8.0.35
-- PHP Version: 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pengaduan_warga`
--

-- --------------------------------------------------------

--
-- Table structure for table `csrf_tokens`
--

CREATE TABLE `csrf_tokens` (
  `token` varchar(64) NOT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `expire` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `csrf_tokens`
--

INSERT INTO `csrf_tokens` (`token`, `user_id`, `created_at`, `expire`) VALUES
('038a492800ed8a1ae0a03c619fbf6bc96ce1841fa55166d2f09ac4efcadc2fe7', 4, '2024-11-12 13:10:33', '2024-11-13 06:10:33'),
('1b8a405bc9bbc6fcea77d5656e7fac5e8ae6798e09dd118ef45d7bc37b0eb23c', 3, '2024-11-11 07:31:22', '2024-11-12 00:31:22'),
('1c93aeeea75a0ba09c44ed05bdbf09432d8662f5b6974a36e0f514a106755ab3', 4, '2024-11-11 07:27:26', '2024-11-12 00:27:26'),
('2525e1801c2c58f16c6f615784105ae0279f26fd300ab50dcc6ac47399934efc', 3, '2024-11-07 07:33:26', '2024-11-08 00:33:26'),
('2a31adb20f4a90c87c9c608f5d29a4b1e8358a114a82027316d4073d50609193', 4, '2024-11-12 13:32:56', '2024-11-13 06:32:56'),
('479443ed6edcc8e77fcec0723155be2bd6c8f069f41e183b06be3d077631eb81', 4, '2024-11-12 06:10:17', '2024-11-12 23:10:17'),
('5c2f7ac21642e845dac4eb27c016b0e5399e8267d6637b431644385a38503cbc', 1, '2024-11-07 07:33:59', '2024-11-08 00:33:59'),
('61424f4a9ce4adec7f2a05fd42ee2a83f944b9927e0a3a51d515228d72f257d2', 3, '2024-11-07 07:32:47', '2024-11-08 00:32:47'),
('6a4f0f6c24f6873aa8d9f977082fc7fd9598a763951a36140f013e908485fc18', 4, '2024-11-12 06:56:51', '2024-11-12 23:56:51'),
('7256face855db5a55af0c115d4c48c2d521f024852474a8c8aeb90dc9d30d755', 4, '2024-11-12 06:55:13', '2024-11-12 23:55:13'),
('77ae83e6f85f669befd9f46da4914462fd5b4371fa079ea4623fe3077a8aaf4d', 1, '2024-11-11 07:08:13', '2024-11-12 00:08:13'),
('792f06ea1dfa6ed858f71dba2a1cc2f2c3d9e03abc89ed10aa187c3cf0b38a6c', 3, '2024-11-12 13:11:19', '2024-11-13 06:11:19'),
('81d3f2fe4052f48494a2ca60fa413342a66a0a8a36863c6d489fc5d6db84c5e7', 4, '2024-11-13 02:33:03', '2024-11-13 19:33:03'),
('88d09d52700874fcdecb67a70d397d4732f66f1ee5b107f1ca6c884875f3632f', 4, '2024-11-12 00:32:48', '2024-11-12 17:32:48'),
('89aaa7eab1e01d426b76ce175bcfe767acf56b6d40b85f830353bbd50936dd16', 3, '2024-11-07 07:32:07', '2024-11-08 00:32:07'),
('89b6c63036c84dadf5d51bc4bdd372f266d3fec65ccdadfb354b95c56ed2bc47', 3, '2024-11-08 02:17:00', '2024-11-08 19:17:00'),
('911a47d71a310e723461c7287843ce3e762984dd4b27e20855727e3c92044917', 4, '2024-11-12 07:04:02', '2024-11-13 00:04:02'),
('9ebe64eb3394f5c67654cf1cbf4381663a760078acce8264185d5014ceefa3d7', 1, '2024-11-08 05:40:42', '2024-11-08 22:40:42'),
('9f1ebaf84eb0584ad85e95c3e285ba2a3963aa70aa89f87cd3aacb20ece76aef', 3, '2024-11-08 07:11:20', '2024-11-09 00:11:20'),
('d9cbeda48e07c3d515582bb9b76bd8459908bf6a3fc26b9b4784dc0de36e9218', 3, '2024-11-12 13:32:13', '2024-11-13 06:32:13'),
('dc76606fb91e6e9be1ad7e4ad339b99f744082476411d0718e65a3827d3fe4c9', 1, '2024-11-08 02:30:03', '2024-11-08 19:30:03');

-- --------------------------------------------------------

--
-- Table structure for table `pengaduan`
--

CREATE TABLE `pengaduan` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('pending','proses','selesai') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengaduan`
--

INSERT INTO `pengaduan` (`id`, `user_id`, `judul`, `isi`, `foto`, `status`, `created_at`) VALUES
(2, 3, 'Lampu jalan mati', 'qwertyuiop', '1731051533-Screenshot 2024-11-05 at 14.04.17.png', 'selesai', '2024-11-08 07:38:53'),
(3, 3, 'Pohon tumbang', 'asdfghjkl', '1731310610-Screenshot 2024-11-10 at 16.32.39.png', 'selesai', '2024-11-11 07:36:50'),
(4, 3, 'Izzan', 'Ganteng', '1731417139-Screenshot 2024-11-12 at 07.36.24.png', 'proses', '2024-11-12 13:12:19'),
(5, 3, 'p', 'p', '1731418350-Screenshot 2024-11-11 at 08.45.02.png', 'pending', '2024-11-12 13:32:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Admin1', '$2y$10$3J6y/J86e02SqVP/PdCazuASIfI/F8ltmkyV1BeKtYK3hsxeLn.o2', 'admin', '2024-11-06 06:39:35'),
(2, 'User1', '$2y$10$ZIwc9GBbk7lavAV38ivCPuwtb1AMsoTwmpozhvUG3iF800/RoZD0q', 'user', '2024-11-06 06:41:34'),
(3, 'Test1', '$2y$10$xM8A3Y1pO2GpLF4RDvJOcub8TCsuxEXIvOtXc4cFBZ9RlQY4lUbcC', 'user', '2024-11-07 07:20:58'),
(4, 'Izzan', '$2y$10$YEhTPXtMP2j.LQ8ji5uClOxu.R/dFKDFJLTJIiw.0hv1LDoVb99wa', 'admin', '2024-11-11 07:23:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `csrf_tokens`
--
ALTER TABLE `csrf_tokens`
  ADD PRIMARY KEY (`token`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pengaduan`
--
ALTER TABLE `pengaduan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD CONSTRAINT `pengaduan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
