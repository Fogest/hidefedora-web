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

--
-- Table structure for table `appeals`
--

CREATE TABLE IF NOT EXISTS `appeals` (
  `pkey` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `approvalStatus` tinyint(4) DEFAULT '0' COMMENT '0 = pending, 1 = accepted, -1 = denied',
  `ip` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`pkey`),
  UNIQUE KEY `pkey_UNIQUE` (`pkey`),
  KEY `id_idx` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table to handle user appeals on their bans. ' AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `blockedusers`
--

CREATE TABLE IF NOT EXISTS `blockedusers` (
  `pkey` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `displayName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profilePictureUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `date` datetime NOT NULL COMMENT 'Initial report date',
  `youtubeUrl` tinytext COLLATE utf8_unicode_ci,
  `approvalStatus` int(2) DEFAULT '0' COMMENT '0 = new, 1 = Approved, -1 = denied',
  `approvalDate` datetime DEFAULT NULL,
  `approvingUser` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`pkey`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `idx_blockedusers_id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17405 ;

-- --------------------------------------------------------

--
-- Table structure for table `reportingusers`
--

CREATE TABLE IF NOT EXISTS `reportingusers` (
  `ip` bigint(14) NOT NULL COMMENT 'The unique ip address of a user reporting.',
  `count` int(11) DEFAULT '1' COMMENT 'The number of reports the user has made',
  `rep` int(11) DEFAULT '1' COMMENT 'Rep goes up and down based on how their reporting is doing.',
  PRIMARY KEY (`ip`),
  UNIQUE KEY `ip_UNIQUE` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table to keep track of the various ip''s of our reporting users for weighting purposes';

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `pkey` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'The ID of the user they are reporting.',
  `ip` bigint(14) DEFAULT NULL,
  `date` datetime NOT NULL COMMENT 'Time of report',
  PRIMARY KEY (`pkey`),
  UNIQUE KEY `pkey_UNIQUE` (`pkey`),
  KEY `ID` (`id`),
  KEY `ip_idx` (`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table which collects everysingle report and logs all the data about the reporting user' AUTO_INCREMENT=7 ;

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

--
-- Constraints for table `appeals`
--
ALTER TABLE `appeals`
  ADD CONSTRAINT `id` FOREIGN KEY (`id`) REFERENCES `blockedusers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `ip` FOREIGN KEY (`ip`) REFERENCES `reportingusers` (`ip`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `profileId` FOREIGN KEY (`id`) REFERENCES `blockedusers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
