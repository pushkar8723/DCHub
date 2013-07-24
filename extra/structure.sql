-- MySQL dump 10.14  Distrib 5.5.32-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: verlihub
-- ------------------------------------------------------
-- Server version	5.5.32-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `SetupList`
--

DROP TABLE IF EXISTS `SetupList`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SetupList` (
  `file` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `var` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `val` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`file`,`var`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `banlist`
--

DROP TABLE IF EXISTS `banlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banlist` (
  `ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nick` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ban_type` tinyint(4) DEFAULT '0',
  `host` text COLLATE utf8_unicode_ci,
  `range_fr` bigint(32) DEFAULT NULL,
  `range_to` bigint(32) DEFAULT NULL,
  `date_start` int(11) DEFAULT '0',
  `date_limit` int(11) DEFAULT NULL,
  `nick_op` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8_unicode_ci,
  `share_size` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  UNIQUE KEY `ip` (`ip`,`nick`),
  KEY `nick_index` (`nick`),
  KEY `date_index` (`date_limit`),
  KEY `range_index` (`range_fr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `conn_types`
--

DROP TABLE IF EXISTS `conn_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conn_types` (
  `identifier` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(64) COLLATE utf8_unicode_ci DEFAULT 'no description',
  `tag_min_slots` int(4) DEFAULT '0',
  `tag_max_slots` int(4) DEFAULT '0',
  `tag_min_limit` double DEFAULT '-1',
  `tag_min_ls_ratio` double DEFAULT '-1',
  PRIMARY KEY (`identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `custom_redirects`
--

DROP TABLE IF EXISTS `custom_redirects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_redirects` (
  `address` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  `flag` tinyint(2) NOT NULL,
  `enable` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dc_clients`
--

DROP TABLE IF EXISTS `dc_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dc_clients` (
  `name` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  `tag_id` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  `min_version` decimal(4,4) NOT NULL DEFAULT '-0.9999',
  `max_version` decimal(4,4) NOT NULL DEFAULT '-0.9999',
  `ban` tinyint(1) NOT NULL DEFAULT '0',
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dchub_branch`
--

DROP TABLE IF EXISTS `dchub_branch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dchub_branch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dchub_content`
--

DROP TABLE IF EXISTS `dchub_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dchub_content` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) DEFAULT '0',
  `uid` int(11) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `title` tinytext,
  `magnetlink` tinytext,
  `tag` text NOT NULL,
  `updatedOn` datetime NOT NULL,
  `createdOn` datetime NOT NULL,
  `priority` int(11) NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=721 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dchub_download`
--

DROP TABLE IF EXISTS `dchub_download`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dchub_download` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` text COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `createdOn` datetime NOT NULL,
  `updatedOn` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dchub_groups`
--

DROP TABLE IF EXISTS `dchub_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dchub_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `description` text NOT NULL,
  `identifier` tinytext NOT NULL,
  `moderators` text NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `createdOn` datetime NOT NULL,
  `updatedOn` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `dchub_hot`
--

DROP TABLE IF EXISTS `dchub_hot`;
/*!50001 DROP VIEW IF EXISTS `dchub_hot`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `dchub_hot` (
  `cid` tinyint NOT NULL,
  `votes` tinyint NOT NULL,
  `type` tinyint NOT NULL,
  `time` tinyint NOT NULL,
  `name` tinyint NOT NULL,
  `tag` tinyint NOT NULL,
  `uid` tinyint NOT NULL,
  `magnetlink` tinyint NOT NULL,
  `deleted` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `dchub_importedusers`
--

DROP TABLE IF EXISTS `dchub_importedusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dchub_importedusers` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` tinytext,
  `password_` tinytext,
  `email` tinytext,
  `ipaddress` tinytext,
  `fullname` tinytext,
  `roll_course` tinytext,
  `roll_number` int(11) DEFAULT NULL,
  `roll_year` int(11) DEFAULT NULL,
  `branch` tinytext,
  `hostel` tinytext,
  `room` tinytext,
  `phone` tinytext,
  `question` tinytext,
  `answer` tinytext,
  `friend` int(11) DEFAULT NULL,
  `class` int(11) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `note` tinytext,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=4045 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `dchub_lcvotes`
--

DROP TABLE IF EXISTS `dchub_lcvotes`;
/*!50001 DROP VIEW IF EXISTS `dchub_lcvotes`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `dchub_lcvotes` (
  `cid` tinyint NOT NULL,
  `votes` tinyint NOT NULL,
  `type` tinyint NOT NULL,
  `time` tinyint NOT NULL,
  `name` tinyint NOT NULL,
  `tag` tinyint NOT NULL,
  `uid` tinyint NOT NULL,
  `magnetlink` tinyint NOT NULL,
  `deleted` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `dchub_log`
--

DROP TABLE IF EXISTS `dchub_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dchub_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `logtype` tinytext,
  `nick` tinytext,
  `nick_to` tinytext,
  `message` text,
  `flag` tinyint(1) DEFAULT '0',
  `createdOn` datetime NOT NULL,
  `updatedOn` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=624424 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dchub_message`
--

DROP TABLE IF EXISTS `dchub_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dchub_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `toid` int(11) NOT NULL,
  `fromid` int(11) NOT NULL,
  `msg` text NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `createdOn` datetime NOT NULL,
  `updatedOn` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=352 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dchub_post`
--

DROP TABLE IF EXISTS `dchub_post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dchub_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL,
  `postby` text NOT NULL,
  `post` text NOT NULL,
  `timestamp` int(11) NOT NULL,
  `approvedby` int(11) NOT NULL DEFAULT '0',
  `createdOn` datetime NOT NULL,
  `updatedOn` datetime NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dchub_rc`
--

DROP TABLE IF EXISTS `dchub_rc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dchub_rc` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) DEFAULT '0',
  `uid` int(11) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `title` tinytext,
  `magnetlink` tinytext,
  `tag` text NOT NULL,
  `updatedOn` datetime NOT NULL,
  `createdOn` datetime NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `dchub_rcview`
--

DROP TABLE IF EXISTS `dchub_rcview`;
/*!50001 DROP VIEW IF EXISTS `dchub_rcview`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `dchub_rcview` (
  `cid` tinyint NOT NULL,
  `votes` tinyint NOT NULL,
  `type` tinyint NOT NULL,
  `time` tinyint NOT NULL,
  `name` tinyint NOT NULL,
  `tag` tinyint NOT NULL,
  `uid` tinyint NOT NULL,
  `magnetlink` tinyint NOT NULL,
  `deleted` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `dchub_recommend`
--

DROP TABLE IF EXISTS `dchub_recommend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dchub_recommend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `type` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15468 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dchub_request`
--

DROP TABLE IF EXISTS `dchub_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dchub_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `request_file` text NOT NULL,
  `volunteer` text NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `createdOn` datetime NOT NULL,
  `updatedOn` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dchub_tvschedule`
--

DROP TABLE IF EXISTS `dchub_tvschedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dchub_tvschedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` tinytext NOT NULL,
  `showname` tinytext NOT NULL,
  `showtitle` tinytext NOT NULL,
  `time` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dchub_users`
--

DROP TABLE IF EXISTS `dchub_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dchub_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nick1` tinytext,
  `nick2` tinytext,
  `password_` tinytext,
  `email` tinytext,
  `ipaddress` tinytext,
  `fullname` tinytext,
  `roll_course` tinytext,
  `roll_number` int(11) DEFAULT NULL,
  `roll_year` int(11) DEFAULT NULL,
  `branch` tinytext,
  `hostel` tinytext,
  `room` tinytext,
  `phone` tinytext,
  `gender` tinytext NOT NULL,
  `friend` tinytext NOT NULL,
  `class` int(11) DEFAULT '0',
  `lastShared` decimal(10,3) NOT NULL DEFAULT '0.000',
  `deleted` tinyint(1) DEFAULT '0',
  `note` tinytext,
  `groups` text NOT NULL,
  `lastnotificationid` int(11) NOT NULL,
  `lastmsgid` int(11) NOT NULL,
  `createdOn` datetime NOT NULL,
  `updatedOn` datetime NOT NULL,
  `lastLogin` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1703 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `file_trigger`
--

DROP TABLE IF EXISTS `file_trigger`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `file_trigger` (
  `command` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `send_as` varchar(25) COLLATE utf8_unicode_ci DEFAULT 'hub-security',
  `def` text COLLATE utf8_unicode_ci,
  `descr` text COLLATE utf8_unicode_ci,
  `min_class` int(2) DEFAULT NULL,
  `max_class` int(2) DEFAULT '10',
  `flags` int(2) DEFAULT '0',
  `seconds` int(15) DEFAULT '0',
  PRIMARY KEY (`command`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kicklist`
--

DROP TABLE IF EXISTS `kicklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kicklist` (
  `nick` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `host` text COLLATE utf8_unicode_ci,
  `share_size` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8_unicode_ci,
  `op` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `is_drop` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`nick`,`time`),
  KEY `op_index` (`op`),
  KEY `ip_index` (`ip`),
  KEY `drop_index` (`is_drop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `msgarchive`
--

DROP TABLE IF EXISTS `msgarchive`;
/*!50001 DROP VIEW IF EXISTS `msgarchive`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `msgarchive` (
  `tonick` tinyint NOT NULL,
  `fromnick` tinyint NOT NULL,
  `msg` tinyint NOT NULL,
  `createdOn` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `pi_plug`
--

DROP TABLE IF EXISTS `pi_plug`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pi_plug` (
  `nick` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `dest` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `detail` text COLLATE utf8_unicode_ci,
  `autoload` tinyint(1) DEFAULT '1',
  `reload` tinyint(1) DEFAULT '0',
  `unload` tinyint(1) DEFAULT '0',
  `error` text COLLATE utf8_unicode_ci,
  `lastload` int(11) DEFAULT NULL,
  PRIMARY KEY (`nick`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reglist`
--

DROP TABLE IF EXISTS `reglist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reglist` (
  `nick` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `class` int(2) DEFAULT '1',
  `class_protect` int(2) DEFAULT '0',
  `class_hidekick` int(2) DEFAULT '0',
  `hide_kick` tinyint(1) DEFAULT '0',
  `hide_keys` tinyint(1) DEFAULT '0',
  `hide_share` tinyint(1) DEFAULT '0',
  `reg_date` int(11) DEFAULT NULL,
  `reg_op` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pwd_change` tinyint(1) DEFAULT '1',
  `pwd_crypt` tinyint(1) DEFAULT '1',
  `login_pwd` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `login_last` int(11) DEFAULT '0',
  `logout_last` int(11) DEFAULT '0',
  `login_cnt` int(11) DEFAULT '0',
  `login_ip` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `error_last` int(11) DEFAULT NULL,
  `error_cnt` int(11) DEFAULT '0',
  `error_ip` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `email` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note_op` text COLLATE utf8_unicode_ci,
  `note_usr` text COLLATE utf8_unicode_ci,
  `auth_ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alternate_ip` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdOn` datetime NOT NULL,
  `updatedOn` datetime NOT NULL,
  PRIMARY KEY (`nick`),
  KEY `login_index` (`login_last`),
  KEY `logout_index` (`logout_last`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `temp_rights`
--

DROP TABLE IF EXISTS `temp_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `temp_rights` (
  `nick` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `op` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `since` int(11) DEFAULT NULL,
  `st_chat` int(11) DEFAULT '1',
  `st_search` int(11) DEFAULT '1',
  `st_ctm` int(11) DEFAULT '1',
  `st_pm` int(11) DEFAULT '1',
  `st_kick` int(11) DEFAULT '1',
  `st_share0` int(11) DEFAULT '1',
  `st_reg` int(11) DEFAULT '1',
  `st_opchat` int(11) DEFAULT '1',
  KEY `creation_index` (`since`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `unbanlist`
--

DROP TABLE IF EXISTS `unbanlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unbanlist` (
  `ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nick` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ban_type` tinyint(4) DEFAULT '0',
  `host` text COLLATE utf8_unicode_ci,
  `range_fr` bigint(32) DEFAULT NULL,
  `range_to` bigint(32) DEFAULT NULL,
  `date_start` int(11) DEFAULT '0',
  `date_limit` int(11) DEFAULT NULL,
  `nick_op` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8_unicode_ci,
  `share_size` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_unban` int(11) DEFAULT NULL,
  `unban_op` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unban_reason` text COLLATE utf8_unicode_ci,
  UNIQUE KEY `ip` (`ip`,`nick`,`date_unban`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Final view structure for view `dchub_hot`
--

/*!50001 DROP TABLE IF EXISTS `dchub_hot`*/;
/*!50001 DROP VIEW IF EXISTS `dchub_hot`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`verlihub`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `dchub_hot` AS select `dchub_lcvotes`.`cid` AS `cid`,`dchub_lcvotes`.`votes` AS `votes`,`dchub_lcvotes`.`type` AS `type`,`dchub_lcvotes`.`time` AS `time`,`dchub_lcvotes`.`name` AS `name`,`dchub_lcvotes`.`tag` AS `tag`,`dchub_lcvotes`.`uid` AS `uid`,`dchub_lcvotes`.`magnetlink` AS `magnetlink`,`dchub_lcvotes`.`deleted` AS `deleted` from `dchub_lcvotes` union select `dchub_rcview`.`cid` AS `cid`,`dchub_rcview`.`votes` AS `votes`,`dchub_rcview`.`type` AS `type`,`dchub_rcview`.`time` AS `time`,`dchub_rcview`.`name` AS `name`,`dchub_rcview`.`tag` AS `tag`,`dchub_rcview`.`uid` AS `uid`,`dchub_rcview`.`magnetlink` AS `magnetlink`,`dchub_rcview`.`deleted` AS `deleted` from `dchub_rcview` where (`dchub_rcview`.`deleted` = 0) order by `votes` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `dchub_lcvotes`
--

/*!50001 DROP TABLE IF EXISTS `dchub_lcvotes`*/;
/*!50001 DROP VIEW IF EXISTS `dchub_lcvotes`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`verlihub`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `dchub_lcvotes` AS select `rec`.`cid` AS `cid`,count(`rec`.`cid`) AS `votes`,`rec`.`type` AS `type`,`cont`.`timestamp` AS `time`,`cont`.`title` AS `name`,`cont`.`tag` AS `tag`,`cont`.`uid` AS `uid`,`cont`.`magnetlink` AS `magnetlink`,`cont`.`deleted` AS `deleted` from (`dchub_recommend` `rec` join `dchub_content` `cont`) where ((`rec`.`cid` = `cont`.`cid`) and (`rec`.`type` = 'lc')) group by `rec`.`cid` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `dchub_rcview`
--

/*!50001 DROP TABLE IF EXISTS `dchub_rcview`*/;
/*!50001 DROP VIEW IF EXISTS `dchub_rcview`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`verlihub`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `dchub_rcview` AS select `rec`.`cid` AS `cid`,count(`rec`.`cid`) AS `votes`,`rec`.`type` AS `type`,`cont`.`timestamp` AS `time`,`cont`.`title` AS `name`,`cont`.`tag` AS `tag`,`cont`.`uid` AS `uid`,`cont`.`magnetlink` AS `magnetlink`,`cont`.`deleted` AS `deleted` from (`dchub_recommend` `rec` join `dchub_rc` `cont`) where ((`rec`.`cid` = `cont`.`cid`) and (`rec`.`type` = 'rc')) group by `rec`.`cid` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `msgarchive`
--

/*!50001 DROP TABLE IF EXISTS `msgarchive`*/;
/*!50001 DROP VIEW IF EXISTS `msgarchive`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `msgarchive` AS select `usr1`.`nick1` AS `tonick`,`usr2`.`nick1` AS `fromnick`,`dchub_message`.`msg` AS `msg`,`dchub_message`.`createdOn` AS `createdOn` from ((`dchub_message` join `dchub_users` `usr1`) join `dchub_users` `usr2`) where ((`usr1`.`id` = `dchub_message`.`toid`) and (`usr2`.`id` = `dchub_message`.`fromid`)) union select `dchub_log`.`nick_to` AS `tonick`,`dchub_log`.`nick` AS `fromnick`,`dchub_log`.`message` AS `msg`,`dchub_log`.`createdOn` AS `createdOn` from `dchub_log` where (`dchub_log`.`logtype` = 'PM') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-07-24 19:08:50
