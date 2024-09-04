-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2024 at 06:57 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `datasys`
--

-- --------------------------------------------------------

--
-- Table structure for table `leaders`
--

CREATE TABLE `leaders` (
  `id` int(11) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `precint_no` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `age` int(11) NOT NULL,
  `address` text NOT NULL,
  `civil_status` varchar(255) NOT NULL,
  `sex` varchar(255) NOT NULL,
  `leaders_photo` varchar(255) NOT NULL,
  `UID` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `leaders`
--

INSERT INTO `leaders` (`id`, `barangay`, `contact_number`, `precint_no`, `full_name`, `birthdate`, `age`, `address`, `civil_status`, `sex`, `leaders_photo`, `UID`) VALUES
(17, 'Bagbaguin', '0947-513-6907', '154E', 'Sofia Penaranda', '2024-07-31', 22, '121 Guijo St.', 'Single', 'Male', '1x1.png', 'UID0001');

--
-- Triggers `leaders`
--
DELIMITER $$
CREATE TRIGGER `trg_generate_uid` BEFORE INSERT ON `leaders` FOR EACH ROW BEGIN
    DECLARE last_id INT;

    -- Get the last number in the UID sequence
    SELECT IFNULL(MAX(CAST(SUBSTRING(UID, 4, 4) AS UNSIGNED)), 0) INTO last_id FROM leaders;

    -- Set the new UID value
    SET NEW.UID = CONCAT('UID', LPAD(last_id + 1, 4, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `member_name` varchar(255) NOT NULL,
  `member_birthdate` date NOT NULL,
  `member_contact` varchar(255) NOT NULL,
  `member_precinct` varchar(255) NOT NULL,
  `member_photo` varchar(255) NOT NULL,
  `leader_id` int(11) NOT NULL,
  `UIDM` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `member_name`, `member_birthdate`, `member_contact`, `member_precinct`, `member_photo`, `leader_id`, `UIDM`) VALUES
(111, 'Realino Peralta', '2024-08-01', '0947-513-6907', '154F', '', 17, 'UIDM0001');

--
-- Triggers `members`
--
DELIMITER $$
CREATE TRIGGER `trg_generate_uid_members` BEFORE INSERT ON `members` FOR EACH ROW BEGIN
    DECLARE last_id INT;

    -- Get the last number in the UIDM sequence for members
    SELECT IFNULL(MAX(CAST(SUBSTRING(UIDM, 5, 4) AS UNSIGNED)), 0) INTO last_id FROM members;

    -- Set the new UID value for members
    SET NEW.UIDM = CONCAT('UIDM', LPAD(last_id + 1, 4, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `token`) VALUES
(1, 'admin', '$2y$10$tWfIYCXmJC33ipc4UEC0ROMWLg1tuuCblAu1R4W.6LF78CDT.t/fG', 'admin@admin.com', 'admin', 'e6ed23080cfe01c7033e32bef9435684079ac6209ba6aeeaf8cc8370a9fc4ca5'),
(3, 'AUBREY', '$2y$10$FMXQiIsYjSzcoL72yg6.BOVQYAxv5Q0RChcofjLGSII97x5NRDAhC', 'Zafiroleonardo@gmail.com', 'user', '65f5cdb84b76941e5cf3a958ebf2922f6a64870709df8a17a73fc20ee2a64e5d');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `leaders`
--
ALTER TABLE `leaders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UID` (`UID`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UID` (`UIDM`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `leaders`
--
ALTER TABLE `leaders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
