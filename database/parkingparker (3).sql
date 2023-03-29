-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2023 at 03:11 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `parkingparker`
--

-- --------------------------------------------------------

--
-- Table structure for table `lots`
--

CREATE TABLE `lots` (
  `lotId` int(11) NOT NULL,
  `placeId` varchar(50) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `lotSlot` int(11) DEFAULT 1,
  `lotSlotNames` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lots`
--

INSERT INTO `lots` (`lotId`, `placeId`, `name`, `address`, `lat`, `lng`, `lotSlot`, `lotSlotNames`) VALUES
(1, 'ChIJRZMY2aAFvTMRwz8z_Z6pjZQ', 'SM City Batangas North Parking', 'Q34C+Q29, Unnamed Road, Batangas, Philippines', 13.7569032, 121.070095, 1, NULL),
(2, 'ChIJtdg_3sEFvTMR47e2M_NQYiM', 'SM City Batangas East Parking', 'Q34C+F39, SM Transport Terminal, Batangas, Philippines', 13.7561519, 121.0702388, 100, NULL),
(3, 'ChIJlyF2sbMFvTMRxb96msG4eCE', 'SM City Batangas South Parking', 'Q339+WR7, Concha Rd, Batangas, Philippines', 13.7547848, 121.0695405, 1, NULL),
(4, 'ChIJ_UmiBYAFvTMRS6qBXLhi6oc', 'SM City Batangas West Parking', 'Q348+9VP, Juaning Rd, Batangas, Philippines', 13.7559647, 121.0671996, 100, NULL),
(5, 'ChIJm0NxGCIFvTMR1S-6tTPhIkc', 'Batangas Medical Center Annex Parking', '333 Ebora Rd, Batangas, Philippines', 13.7656356, 121.0672794, 10, NULL),
(6, 'ChIJBxLqxNgFvTMR2rmyIARtalY', 'EG LY GARAGE', 'Q345+H7P, Poblacion, Batangas, Philippines', 13.756482, 121.0582088, 10, NULL),
(7, 'ChIJTbNB3JMFvTMRrPT4wFWgy6U', 'UNICITY Parking', '23 De Jesus, Poblacion, Batangas, 4200 Batangas, Philippines', 13.7583974, 121.0563328, 10, NULL),
(8, 'ChIJdz5B35kFvTMRaoBLbl-KGm8', 'BCL PAY PARKING', '32 D. Silang, Poblacion 19, Batangas, 4200 Batangas, Philippines', 13.753458, 121.055777, 20, NULL),
(9, 'ChIJRw_mkCAFvTMR9ujN-4DqGWo', 'Garahe ni Ka Teddy', 'P3W9+PV6, Batangas, Philippines', 13.7467994, 121.0696514, 100, NULL),
(10, 'ChIJKxZvS0bQlzMRuObAA_D136c', 'Parking - Ayala Malls South Park', 'C26W+WM4, Alabang, Muntinlupa, Metro Manila, Philippines', 14.4122575, 121.0467159, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lots_prices`
--

CREATE TABLE `lots_prices` (
  `lotPriceId` int(11) NOT NULL,
  `lotId` int(11) DEFAULT NULL,
  `price` double(11,2) DEFAULT NULL,
  `hours` double(11,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lots_prices`
--

INSERT INTO `lots_prices` (`lotPriceId`, `lotId`, `price`, `hours`) VALUES
(36, 1, 200.00, 1.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `paymentId` int(11) NOT NULL,
  `paymentChannel` varchar(45) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `reservationCode` varchar(256) DEFAULT NULL,
  `paymentDT` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`paymentId`, `paymentChannel`, `amount`, `reservationCode`, `paymentDT`) VALUES
(1, 'paypal', 100, 'ba051922435e5c19f30be516fdd3e76695fd495b73803ebf8f968aebde7bdb30', '2023-03-03 11:12:22'),
(2, 'paypal', 100, 'ee9d8b518788efa585a6b41eafb20928065b683862012d848b51a4b83ba443a9', '2023-03-03 11:15:57'),
(3, 'paypal', 100, 'e0145e46b1d07a7e0567a0d825249e86036b31f47febe75a64c9913017a96c30', '2023-03-04 18:23:51'),
(4, 'paypal', 100, '5fd25b8c752787c0f537b74dce0731b758da91ae123d8d05b3f25c021c765609', '2023-03-13 07:10:38'),
(5, 'paypal', 100, '5cd67dd4adf4b920be7828a8646ac2e8f93b4e037a2e0e34dfe6d87ffedb21de', '2023-03-13 09:04:09'),
(6, 'paypal', 100, '26d14b50ff120d5c31f6667ae4fb482527381015e8718359d1826b2df8460685', '2023-03-13 09:06:55'),
(7, 'paypal', NULL, 'b33a4db98619f478f8916866c9acf816504c12d6c4771b68c27ff980cf740115', '2023-03-13 09:11:28'),
(8, 'paypal', 100, 'b33a4db98619f478f8916866c9acf816504c12d6c4771b68c27ff980cf740115', '2023-03-13 09:14:00'),
(9, 'paypal', 100, 'b33a4db98619f478f8916866c9acf816504c12d6c4771b68c27ff980cf740115', '2023-03-13 09:14:01'),
(10, 'paypal', 100, 'e96db5457684917c751457b990cf4d4e846de2f5b89ab92ba80a9ffe8c8c3fb3', '2023-03-23 17:36:15'),
(11, 'paypal', 100, '3a1c96fb6f2adc0c5598c3ae9c643486033f805ce971c80827a7a4ca6dff6e3e', '2023-03-23 17:37:54'),
(12, 'paypal', 100, '4a2f6288d97bf74fb1fd2849dffd19a5c7d71ec4b29b225fb1351e96b5e1c3c8', '2023-03-23 17:38:35'),
(13, 'paypal', 100, 'e4025568f89ee0ee9aa13642a865b99834de3ef69a21739e2881ad762f2d7026', '2023-03-27 11:39:58');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservationId` int(11) NOT NULL,
  `reservationCode` varchar(256) DEFAULT NULL,
  `lotId` int(11) DEFAULT NULL,
  `lotPriceId` int(11) DEFAULT NULL,
  `vehicleId` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `startDateTime` datetime DEFAULT NULL,
  `endDateTime` datetime DEFAULT NULL,
  `isPaid` varchar(1) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `reservationDateTime` datetime DEFAULT NULL,
  `reservationDT` datetime DEFAULT current_timestamp(),
  `amountPaid` double(11,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservationId`, `reservationCode`, `lotId`, `lotPriceId`, `vehicleId`, `userId`, `startDateTime`, `endDateTime`, `isPaid`, `status`, `reservationDateTime`, `reservationDT`, `amountPaid`) VALUES
(1, '5cd67dd4adf4b920be7828a8646ac2e8f93b4e037a2e0e34dfe6d87ffedb21de', 1, 36, 1, 1, '2023-03-13 12:00:00', '2023-03-13 13:00:00', '1', '0', '2023-03-13 12:00:00', '2023-03-13 08:40:51', 100.00),
(2, '26d14b50ff120d5c31f6667ae4fb482527381015e8718359d1826b2df8460685', 2, NULL, 1, 1, '2023-03-14 12:00:00', '2023-03-15 12:00:00', '1', '0', '2023-03-14 12:00:00', '2023-03-13 09:05:01', 200.00),
(3, 'b33a4db98619f478f8916866c9acf816504c12d6c4771b68c27ff980cf740115', 2, NULL, 1, 1, '2023-03-13 12:00:00', '2023-03-14 12:00:00', '1', '0', '2023-03-13 12:00:00', '2023-03-13 09:11:11', 100.00),
(4, '89f2e38ddcd491082d3bef5921d613d23ac173244b25932107217b9e790565cc', NULL, NULL, 1, 1, '2023-03-23 15:35:00', '2023-03-24 15:35:00', '0', '0', '2023-03-23 15:35:00', '2023-03-23 17:32:59', NULL),
(5, '02b6c5134720cc0ed6e5225731eb55c8504ee4335c813b265771c89446e28796', NULL, NULL, 1, 1, '2023-03-23 17:33:00', '2023-03-24 17:33:00', '0', '0', '2023-03-23 17:33:00', '2023-03-23 17:34:28', NULL),
(6, 'e96db5457684917c751457b990cf4d4e846de2f5b89ab92ba80a9ffe8c8c3fb3', 2, NULL, 1, 1, '2023-03-23 17:35:00', '2023-03-24 17:35:00', '1', '0', '2023-03-23 17:35:00', '2023-03-23 17:35:55', 100.00),
(7, '3a1c96fb6f2adc0c5598c3ae9c643486033f805ce971c80827a7a4ca6dff6e3e', 2, NULL, 1, 1, '2023-03-24 05:37:00', '2023-03-25 05:37:00', '1', '0', '2023-03-24 05:37:00', '2023-03-23 17:37:41', 100.00),
(8, '4a2f6288d97bf74fb1fd2849dffd19a5c7d71ec4b29b225fb1351e96b5e1c3c8', 1, NULL, 1, 1, '2023-03-25 17:38:00', '2023-03-26 17:38:00', '1', '0', '2023-03-25 17:38:00', '2023-03-23 17:38:23', 100.00),
(9, '9934bc940b9b169fdd2f719192e36372ebc642e68eef36a44f4e7752a009e510', 1, NULL, 1, 1, '2023-03-23 17:38:00', '2023-03-24 17:38:00', '0', '0', '2023-03-23 17:38:00', '2023-03-23 17:38:53', NULL),
(10, '590d522f61479f39e23a5eedcd21a7c26e72eb13d5c904d44a3f2e4a9943b844', 1, 36, 1, 1, '2023-03-24 19:10:00', '2023-03-24 20:10:00', '0', '0', '2023-03-24 19:10:00', '2023-03-23 18:10:42', NULL),
(11, 'e4025568f89ee0ee9aa13642a865b99834de3ef69a21739e2881ad762f2d7026', 2, NULL, 1, 1, '2023-03-27 12:00:00', '2023-03-28 12:00:00', '1', '0', '2023-03-27 12:00:00', '2023-03-27 11:39:38', 100.00),
(12, '478b41e16df9bd0a610efe92034719140c05bc12926581c6a99cf93cc0133913', 4, NULL, 1, 1, '2023-03-27 11:48:00', '2023-03-28 11:48:00', '0', '0', '2023-03-27 11:48:00', '2023-03-27 11:48:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `contactNumber` varchar(13) NOT NULL,
  `password` varchar(24) DEFAULT NULL,
  `lastName` varchar(45) DEFAULT NULL,
  `firstName` varchar(45) DEFAULT NULL,
  `middleName` varchar(45) DEFAULT NULL,
  `userType` int(11) DEFAULT 0,
  `email` varchar(50) DEFAULT NULL,
  `isLocked` varchar(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `contactNumber`, `password`, `lastName`, `firstName`, `middleName`, `userType`, `email`, `isLocked`) VALUES
(1, '00000000001', '123', 'Bisquera', 'Edgardo', 'Creer', 1, 'test', '0'),
(2, '00000000002', '123', 'Bisquera', 'Edgardo', 'Creer', 0, NULL, '0'),
(3, '00000000003', '123123', 'Bisquera', 'Angelo', 'Creer', 0, NULL, '0'),
(4, '01', 'asd', 'asd', 'asd', 'asd', 0, NULL, '0'),
(5, '00000000005', '123', 'asd', 'aqsd', 'asd', 0, NULL, '0'),
(6, '005', '123123', '123', '123', '123', 0, NULL, '0');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicleId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `plateNum` varchar(20) DEFAULT NULL,
  `vehicleType` varchar(20) DEFAULT NULL,
  `vehicleBrand` varchar(45) DEFAULT NULL,
  `vehicleModel` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicleId`, `userId`, `plateNum`, `vehicleType`, `vehicleBrand`, `vehicleModel`) VALUES
(1, 1, 'AAA-0000', 'Truck', 'Ford', 'Ranger'),
(2, 1, 'AAA', 'Hatchback', 'sd', 'sd'),
(3, 1, 'AAA', 'Hatchback', 'assd', 'sdd'),
(4, 1, 'AAA', 'Hatchback', 'sddd', 'sdgasd'),
(5, 1, 'AAA', 'Hatchback', 'sddd', 'asg'),
(6, 1, 'AAA', 'Hatchback', 'sd', 'sddd'),
(7, 1, 'AAA', 'Crossover', 'asd', 'sd'),
(8, 1, 'BBB', 'Convertible', 'sddd', 'sd'),
(9, 1, 'NGL-4313', 'Truck', 'Ford', 'Ranger');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lots`
--
ALTER TABLE `lots`
  ADD PRIMARY KEY (`lotId`);

--
-- Indexes for table `lots_prices`
--
ALTER TABLE `lots_prices`
  ADD PRIMARY KEY (`lotPriceId`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`paymentId`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservationId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`,`contactNumber`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicleId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lots_prices`
--
ALTER TABLE `lots_prices`
  MODIFY `lotPriceId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `paymentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
