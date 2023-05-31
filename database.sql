-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 31, 2023 at 02:26 PM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `testtable`
--

-- --------------------------------------------------------

--
-- Table structure for table `users2`
--

CREATE TABLE `users2` (
  `id` int(11) NOT NULL,
  `login` varchar(256) NOT NULL,
  `password_hash` varchar(256) DEFAULT NULL,
  `user_hash_cookie` varchar(256) DEFAULT NULL,
  `register_time` date DEFAULT NULL,
  `role` varchar(256) DEFAULT NULL,
  `vk_user_id` int(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users2`
--

INSERT INTO `users2` (`id`, `login`, `password_hash`, `user_hash_cookie`, `register_time`, `role`, `vk_user_id`) VALUES
(24, 'admin', '$2y$10$p00C.NgYDSz3d0W9OW73teYfmxwCowNFaPrg00Q0oLqlUVkJ0WEua', 'c8dc3988b545fa04d631dfc757bd033f', '2023-05-26', 'admin', NULL),
(44, 'ivan', '$2y$10$lI.S3CWq4qpme2Bc8Q0pkOTVyV9Uj82FGPXPhJLfZFzGMLXm5jW8G', '61a7739f536e084836aa33517a0ecd4e', '2023-05-26', 'admin', NULL),
(45, 'ozzy', '$2y$10$vdmaqkcmBdhIE187p/jB6.jm15pgyq/N7F2rG6kcDcKSfLJm4/oRW', NULL, '2023-05-27', 'user', NULL),
(91, 'Иван', NULL, '0e543145058be7e1dbcc845642a82799', '2023-05-30', 'vk_user', 805486867),
(96, 'qwerrt', '$2y$10$xyeFz05YQFHDm9eCAxEL.ejnlY94ni5rnumjDyrTMX2Q.ytoIVWIC', '2d2d7435d6e1a3df287eb3b1a03e98e5', '2023-05-31', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users2`
--
ALTER TABLE `users2`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users2`
--
ALTER TABLE `users2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;
