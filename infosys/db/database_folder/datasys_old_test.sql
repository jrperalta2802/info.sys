-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2024 at 01:17 AM
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
  `UID` varchar(11) NOT NULL,
  `printLeader_timestamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `leaders`
--

INSERT INTO `leaders` (`id`, `barangay`, `contact_number`, `precint_no`, `full_name`, `birthdate`, `age`, `address`, `civil_status`, `sex`, `leaders_photo`, `UID`, `printLeader_timestamp`) VALUES
(1, 'Bagbaguin', '0912-345-6789', '123A', 'Juan Dela Cruz', '2024-10-01', 22, '123 St Dominic', 'Single', 'Male', '1x1.jpg', 'UID000001', '2024-10-23 11:19:21'),
(2, 'Bagong Barrio', '0947-513-6907', '154E', 'Juan Jose Rojas', '2024-10-01', 22, '123 Grove St.', 'Single', 'Male', '1x1.png', 'UID000002', '2024-10-22 19:01:14');

--
-- Triggers `leaders`
--
DELIMITER $$
CREATE TRIGGER `trg_generate_uid` BEFORE INSERT ON `leaders` FOR EACH ROW BEGIN
    DECLARE last_id INT;

    -- Get the last number in the UID sequence
    SELECT IFNULL(MAX(CAST(SUBSTRING(UID, 4) AS UNSIGNED)), 0) INTO last_id FROM leaders;

    -- Set the new UID value, allowing for up to 6 digits (1 million)
    SET NEW.UID = CONCAT('UID', LPAD(last_id + 1, 6, '0'));
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
  `UIDM` varchar(12) NOT NULL,
  `printMember_timestamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `member_name`, `member_birthdate`, `member_contact`, `member_precinct`, `member_photo`, `leader_id`, `UIDM`, `printMember_timestamp`) VALUES
(2, 'Sebastian Vidal', '2024-10-10', '0992-846-4911', '124B', '', 2, 'UIDM000002', '2024-10-22 19:01:06'),
(5, 'Jane Smith', '2024-10-03', '0917-595-3521', '123B', '', 1, 'UIDM000003', NULL),
(6, 'Realino Peraltaddasd', '2024-10-03', '0992-846-4911', '124B', '', 1, 'UIDM000004', NULL);

--
-- Triggers `members`
--
DELIMITER $$
CREATE TRIGGER `trg_generate_uid_members` BEFORE INSERT ON `members` FOR EACH ROW BEGIN
    DECLARE last_id INT;

    -- Get the last number in the UIDM sequence
    SELECT IFNULL(MAX(CAST(SUBSTRING(UIDM, 5) AS UNSIGNED)), 0) INTO last_id FROM members;

    -- Set the new UIDM value, allowing for up to 6 digits (1 million)
    SET NEW.UIDM = CONCAT('UIDM', LPAD(last_id + 1, 6, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `reports_help`
--

CREATE TABLE `reports_help` (
  `UID` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `birthday` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `contact` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `assistance` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `time` time NOT NULL DEFAULT current_timestamp(),
  `comments` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `barangay` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(1, 'admin', '$2y$10$tWfIYCXmJC33ipc4UEC0ROMWLg1tuuCblAu1R4W.6LF78CDT.t/fG', 'admin@admin.com', 'admin', 'd4fcc07e83414d5d5e23f0f651319cfbd8b2d7715b09e7f706d4716b65db9ff4'),
(3, 'AUBREY', '$2y$10$FMXQiIsYjSzcoL72yg6.BOVQYAxv5Q0RChcofjLGSII97x5NRDAhC', 'Zafiroleonardo@gmail.com', 'user', 'a50ef922243c58e6a680bfaac6c6fbaa9b214bbaba70efa53a8a909f502d2c66'),
(4, 'ashley', '$2y$10$nAgUJ8JEpsRziaI7rouftuzQpoUr.yo5zgSlCcy6FX997UJvd4ZXO', 'ashdeleon88@gmail.com', 'user', 'afcdc0254d13acbc18312f84acf4976216e1de60ef3cfe05ccac5102e6cad5f2');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
