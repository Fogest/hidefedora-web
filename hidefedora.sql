-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2014 at 05:14 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hidefedora`
--

-- --------------------------------------------------------

--
-- Table structure for table `blockedusers`
--

CREATE TABLE IF NOT EXISTS `blockedusers` (
  `pkey` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `date` datetime NOT NULL,
  `count` int(11) NOT NULL DEFAULT '1' COMMENT 'Number of times item has been reported',
  `approvalStatus` int(2) DEFAULT '0' COMMENT '0 = new, 1 = Approved, -1 = denied',
  `approvalDate` datetime DEFAULT NULL,
  `ip` bigint(14) DEFAULT NULL,
  `approvingUser` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `youtubeUrl` tinytext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`pkey`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `idx_blockedusers_id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



-- --------------------------------------------------------

--
-- Table structure for table `appeals`
--

CREATE TABLE IF NOT EXISTS `appeals` (
  `pkey` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `approvalStatus` tinyint(4) DEFAULT '0' COMMENT '0 = pending, 1 = accepted',
  `ip` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`pkey`),
  UNIQUE KEY `pkey_UNIQUE` (`pkey`),
  KEY `id_idx` (`id`),
  CONSTRAINT `id` FOREIGN KEY (`id`) REFERENCES `blockedusers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table to handle user appeals on their bans. ';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET latin1 NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 NOT NULL,
  `user_level` int(10) NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 NOT NULL,
  `account_creation_date` datetime NOT NULL,
  `account_creation_ip` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `user_level`, `email`, `account_creation_date`, `account_creation_ip`) VALUES
(1, 'UsernameInHere', 'sha-256 password here', 2, 'email@example.com', '2014-11-27 00:00:00', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
