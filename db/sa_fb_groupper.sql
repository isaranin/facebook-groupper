-- phpMyAdmin SQL Dump
-- version 4.1.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 18, 2016 at 08:42 PM
-- Server version: 5.6.14
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sa_fb_groupper`
--

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

DROP TABLE IF EXISTS `collections`;
CREATE TABLE IF NOT EXISTS `collections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='collection of groups' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fb_access_tokens`
--

DROP TABLE IF EXISTS `fb_access_tokens`;
CREATE TABLE IF NOT EXISTS `fb_access_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL,
  `token` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `feed`
--

DROP TABLE IF EXISTS `feed`;
CREATE TABLE IF NOT EXISTS `feed` (
  `groupid` bigint(20) NOT NULL,
  `postid` bigint(20) NOT NULL,
  `authorid` bigint(20) NOT NULL,
  `message` mediumtext NOT NULL,
  `type` int(11) NOT NULL,
  `link` int(11) NOT NULL,
  `published` tinyint(1) DEFAULT NULL,
  `visible` tinyint(1) DEFAULT NULL,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  `attachments` mediumtext,
  PRIMARY KEY (`groupid`,`postid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `url` text COMMENT 'group url',
  `code` tinytext COMMENT 'code (part of url)',
  `name` text,
  `description` mediumtext,
  `icon` text,
  `cover` text,
  `privacy` tinytext,
  `email` text,
  `ownerid` bigint(20) NOT NULL,
  `syncfeed` tinyint(1) NOT NULL,
  `lastsync` datetime DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='facebook groups' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `groups_to_collections`
--

DROP TABLE IF EXISTS `groups_to_collections`;
CREATE TABLE IF NOT EXISTS `groups_to_collections` (
  `group_id` int(11) NOT NULL,
  `collection_id` int(11) NOT NULL,
  PRIMARY KEY (`group_id`,`collection_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='relation groups to collection';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
