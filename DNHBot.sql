-- phpMyAdmin SQL Dump
-- version 5.1.1deb3+focal1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 04, 2021 at 08:07 AM
-- Server version: 8.0.26-0ubuntu0.20.04.2
-- PHP Version: 7.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `DNHBot`
--

-- --------------------------------------------------------

--
-- Table structure for table `patterns`
--

CREATE TABLE `patterns` (
  `id` int NOT NULL,
  `user_id` bigint NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patterns`
--

INSERT INTO `patterns` (`id`, `user_id`, `name`, `value`, `type`) VALUES
(1, 2, 'Example.com - .COM', '/(?<=organisation:)(?s)(.*)(?=created)/im', 'owner'),
(2, 2, '.uz', '/(?<=org:)(?s)(.*)(?=registrar)/im', 'owner'),
(3, 2, '.uz', '/(?<=Registrant:)(?s)(.*)(?=Creation Date)/im', 'owner'),
(4, 2, '.uz', '/(?<=expiration date:)(?s)(.*?)(?=\\n)/im', 'expiration_date'),
(5, 2, '.uz', '/(?<=paid-till:)(?s)(.*?)(?=\\n)/im', 'expiration_date'),
(6, 2, '.uz', '/(?<=Creation Date:)(?s)(.*)(?=Registry Expiry Date)/im', 'creation_date'),
(7, 2, 'general', '/(?<=created:)(?s)(.*?)(?=\\n)/im', 'creation_date'),
(8, 2, '.uz', '/(?<=registrar:)(?s)(.*)(?=registrar iana id)/im', 'registrar'),
(9, 2, '.uz', '/(?<=domain name:)(.*?)(?=\\n)/im', 'domain'),
(10, 2, 'general', '/(?<=domain:)(.*?)(?=\\n)/im', 'domain'),
(11, 2, '.com - Example.com', '/(?<=Registry Expiry Date:)(?s)(.*?)(?=\\n)/im', 'expiration_date'),
(12, 2, '.com - Example.com', '/(?<=has address)(?s)(.*?)(?=\n)/im', 'ip'),
(13, 2, 'parmonov98.uz', '/(?<=creation date:)(?s)(.*?)(?=\\n)/im', 'creation_date'),
(14, 2, 'parmonov98.uz', '/(?<=registrar:)(?s)(.*?)(?=\\n\n)/im', 'registrar'),
(15, 2, 'parmonov98.uz', '/(?<=organization:)(?s)(.*?)(?=\n)/im', 'registrar'),
(16, 2, '.com - Example.com', '/(?<=expires:)(?s)(.*?)(?=\n)/i', 'expiration_date'),
(17, 2, 'parmonov98.uz', '/(?<=\\nregistrar:)(?s)(.*?)(?=\\n)/im', 'registrar'),
(18, 2, '.ua - dev.ua', '/(?<=organization:)(?s)(.*?)(?=\\n)/im', 'owner');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint NOT NULL,
  `telegram_id` bigint NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `lang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'uz'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `telegram_id`, `first_name`, `last_name`, `type`, `status`, `lang`) VALUES
(2, 94665561, 'Murod', 'Parmonov', 'user', 'active', 'ru');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `patterns`
--
ALTER TABLE `patterns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_11ADDDD0A76ED395` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1483A5E9CC0B3066` (`telegram_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `patterns`
--
ALTER TABLE `patterns`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `patterns`
--
ALTER TABLE `patterns`
  ADD CONSTRAINT `FK_11ADDDD0A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
