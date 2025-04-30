-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2025 at 09:58 PM
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
-- Database: `newsdatabase`
--

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `pagenumb` int(5) NOT NULL,
  `header` varchar(60) NOT NULL,
  `thebody` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`pagenumb`, `header`, `thebody`) VALUES
(54876, 'باشگاه ما در حال برگذاری ثبت نام است', 'باشگاه ما در حال برگذاری ثبت نام است.\r\nباشگاه ما در حال برگذاری ثبت نام است.\r\nباشگاه ما در حال برگذاری ثبت نام است.\r\nباشگاه ما در حال برگذاری ثبت نام است.\r\nباشگاه ما در حال برگذاری ثبت نام است.'),
(75486, 'محمد امین رکورد خود را امروز میزند', 'محمد امین رکورد خود را امروز میزند برای او دعا کنید.');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
