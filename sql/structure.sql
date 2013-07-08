-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 08, 2013 at 08:36 AM
-- Server version: 5.5.31-0ubuntu0.13.04.1
-- PHP Version: 5.4.9-4ubuntu2.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `verlihub`
--

-- --------------------------------------------------------

--
-- Table structure for table `banlist`
--

CREATE TABLE IF NOT EXISTS `banlist` (
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

-- --------------------------------------------------------

--
-- Table structure for table `conn_types`
--

CREATE TABLE IF NOT EXISTS `conn_types` (
  `identifier` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(64) COLLATE utf8_unicode_ci DEFAULT 'no description',
  `tag_min_slots` int(4) DEFAULT '0',
  `tag_max_slots` int(4) DEFAULT '0',
  `tag_min_limit` double DEFAULT '-1',
  `tag_min_ls_ratio` double DEFAULT '-1',
  PRIMARY KEY (`identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content_backup`
--

CREATE TABLE IF NOT EXISTS `content_backup` (
  `cid` int(11) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0',
  `uid` int(11) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `title` tinytext,
  `magnetlink` tinytext,
  `tag` text NOT NULL,
  `updatedOn` datetime NOT NULL,
  `createdOn` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `custom_redirects`
--

CREATE TABLE IF NOT EXISTS `custom_redirects` (
  `address` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  `flag` tinyint(2) NOT NULL,
  `enable` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dchub_branch`
--

CREATE TABLE IF NOT EXISTS `dchub_branch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dchub_content`
--

CREATE TABLE IF NOT EXISTS `dchub_content` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dchub_groups`
--

CREATE TABLE IF NOT EXISTS `dchub_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `description` text NOT NULL,
  `identifier` tinytext NOT NULL,
  `moderators` text NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `createdOn` datetime NOT NULL,
  `updatedOn` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Stand-in structure for view `dchub_hot`
--
CREATE TABLE IF NOT EXISTS `dchub_hot` (
`cid` int(11)
,`votes` bigint(21)
,`type` tinytext
,`time` int(11)
,`name` tinytext
,`tag` text
,`uid` int(11)
,`magnetlink` tinytext
,`deleted` tinyint(4)
);
-- --------------------------------------------------------

--
-- Table structure for table `dchub_importedusers`
--

CREATE TABLE IF NOT EXISTS `dchub_importedusers` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` tinytext CHARACTER SET latin1,
  `password_` tinytext CHARACTER SET latin1,
  `email` tinytext CHARACTER SET latin1,
  `ipaddress` tinytext CHARACTER SET latin1,
  `fullname` tinytext CHARACTER SET latin1,
  `roll_course` tinytext CHARACTER SET latin1,
  `roll_number` int(11) DEFAULT NULL,
  `roll_year` int(11) DEFAULT NULL,
  `branch` tinytext CHARACTER SET latin1,
  `hostel` tinytext CHARACTER SET latin1,
  `room` tinytext CHARACTER SET latin1,
  `phone` tinytext CHARACTER SET latin1,
  `question` tinytext CHARACTER SET latin1,
  `answer` tinytext CHARACTER SET latin1,
  `friend` int(11) DEFAULT NULL,
  `class` int(11) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `note` tinytext CHARACTER SET latin1,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `dchub_lcvotes`
--
CREATE TABLE IF NOT EXISTS `dchub_lcvotes` (
`cid` int(11)
,`votes` bigint(21)
,`type` tinytext
,`time` int(11)
,`name` tinytext
,`tag` text
,`uid` int(11)
,`magnetlink` tinytext
,`deleted` tinyint(1)
);
-- --------------------------------------------------------

--
-- Table structure for table `dchub_log`
--

CREATE TABLE IF NOT EXISTS `dchub_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `logtype` tinytext,
  `nick` tinytext,
  `nick_to` tinytext,
  `message` text,
  `flag` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dchub_message`
--

CREATE TABLE IF NOT EXISTS `dchub_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `toid` int(11) NOT NULL,
  `fromid` int(11) NOT NULL,
  `msg` text NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `createdOn` datetime NOT NULL,
  `updatedOn` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dchub_post`
--

CREATE TABLE IF NOT EXISTS `dchub_post` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dchub_rc`
--

CREATE TABLE IF NOT EXISTS `dchub_rc` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Stand-in structure for view `dchub_rcview`
--
CREATE TABLE IF NOT EXISTS `dchub_rcview` (
`cid` int(11)
,`votes` bigint(21)
,`type` tinytext
,`time` int(11)
,`name` tinytext
,`tag` text
,`uid` int(11)
,`magnetlink` tinytext
,`deleted` tinyint(1)
);
-- --------------------------------------------------------

--
-- Table structure for table `dchub_recommend`
--

CREATE TABLE IF NOT EXISTS `dchub_recommend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `type` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dchub_request`
--

CREATE TABLE IF NOT EXISTS `dchub_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `request_file` text NOT NULL,
  `volunteer` text NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `createdOn` datetime NOT NULL,
  `updatedOn` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dchub_users`
--

CREATE TABLE IF NOT EXISTS `dchub_users` (
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
  `security_ques` tinytext NOT NULL,
  `security_ans` tinytext NOT NULL,
  `friend` tinytext NOT NULL,
  `class` int(11) DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0',
  `note` tinytext,
  `groups` text NOT NULL,
  `lastnotificationid` int(11) NOT NULL,
  `lastmsgid` int(11) NOT NULL,
  `createdOn` datetime NOT NULL,
  `updatedOn` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dc_clients`
--

CREATE TABLE IF NOT EXISTS `dc_clients` (
  `name` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  `tag_id` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  `min_version` decimal(4,4) NOT NULL DEFAULT '-0.9999',
  `max_version` decimal(4,4) NOT NULL DEFAULT '-0.9999',
  `ban` tinyint(1) NOT NULL DEFAULT '0',
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_trigger`
--

CREATE TABLE IF NOT EXISTS `file_trigger` (
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

-- --------------------------------------------------------

--
-- Table structure for table `kicklist`
--

CREATE TABLE IF NOT EXISTS `kicklist` (
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

-- --------------------------------------------------------

--
-- Stand-in structure for view `msgarchive`
--
CREATE TABLE IF NOT EXISTS `msgarchive` (
`tonick` tinytext
,`fromnick` tinytext
,`msg` text
,`createdOn` datetime
);
-- --------------------------------------------------------

--
-- Table structure for table `pi_iplog`
--

CREATE TABLE IF NOT EXISTS `pi_iplog` (
  `date` int(11) DEFAULT NULL,
  `action` smallint(6) DEFAULT '0',
  `ip` bigint(20) DEFAULT NULL,
  `nick` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `info` int(11) DEFAULT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `ind_ip` (`ip`),
  KEY `ind_nick` (`nick`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pi_isp`
--

CREATE TABLE IF NOT EXISTS `pi_isp` (
  `ipmin` bigint(20) NOT NULL DEFAULT '0',
  `ipmax` bigint(20) NOT NULL DEFAULT '0',
  `cc` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descprefix` varchar(16) COLLATE utf8_unicode_ci DEFAULT '[???]',
  `nickpattern` varchar(64) COLLATE utf8_unicode_ci DEFAULT '\\[---\\]',
  `errmsg` varchar(128) COLLATE utf8_unicode_ci DEFAULT 'Your nick must be like this patern %[pattern]',
  `conntype` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `connmsg` varchar(128) COLLATE utf8_unicode_ci DEFAULT 'Your connection type does not match %[pattern]',
  `minshare` int(11) DEFAULT '-1',
  `minsharereg` int(11) DEFAULT '-1',
  `minsharevip` int(11) DEFAULT '-1',
  `minshareop` int(11) DEFAULT '-1',
  `maxshare` int(11) DEFAULT '-1',
  `maxsharereg` int(11) DEFAULT '-1',
  `maxsharevip` int(11) DEFAULT '-1',
  `maxshareop` int(11) DEFAULT '-1',
  PRIMARY KEY (`ipmin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pi_messages`
--

CREATE TABLE IF NOT EXISTS `pi_messages` (
  `sender` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `date_sent` int(11) NOT NULL,
  `sender_ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `receiver` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `date_expires` int(11) DEFAULT '0',
  `subject` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`sender`,`date_sent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pi_plug`
--

CREATE TABLE IF NOT EXISTS `pi_plug` (
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

-- --------------------------------------------------------

--
-- Table structure for table `pi_stats`
--

CREATE TABLE IF NOT EXISTS `pi_stats` (
  `realtime` int(11) NOT NULL,
  `uptime` int(11) DEFAULT NULL,
  `users_total` int(11) DEFAULT '0',
  `users_zone0` int(11) DEFAULT '0',
  `users_zone1` int(11) DEFAULT '0',
  `users_zone2` int(11) DEFAULT '0',
  `users_zone3` int(11) DEFAULT '0',
  `users_zone4` int(11) DEFAULT '0',
  `users_zone5` int(11) DEFAULT '0',
  `users_zone6` int(11) DEFAULT '0',
  `upload_total` double DEFAULT '0',
  `upload_zone0` double DEFAULT '0',
  `upload_zone1` double DEFAULT '0',
  `upload_zone2` double DEFAULT '0',
  `upload_zone3` double DEFAULT '0',
  `upload_zone4` double DEFAULT '0',
  `upload_zone5` double DEFAULT '0',
  `upload_zone6` double DEFAULT '0',
  `share_total_gb` int(11) DEFAULT '0',
  `freq_search_active` double DEFAULT '0',
  `freq_search_passive` double DEFAULT '0',
  `freq_user_login` double DEFAULT '0',
  `freq_user_logout` double DEFAULT '0',
  PRIMARY KEY (`realtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reglist`
--

CREATE TABLE IF NOT EXISTS `reglist` (
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

-- --------------------------------------------------------

--
-- Table structure for table `SetupList`
--

CREATE TABLE IF NOT EXISTS `SetupList` (
  `file` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `var` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `val` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`file`,`var`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temp_rights`
--

CREATE TABLE IF NOT EXISTS `temp_rights` (
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

-- --------------------------------------------------------

--
-- Table structure for table `unbanlist`
--

CREATE TABLE IF NOT EXISTS `unbanlist` (
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

-- --------------------------------------------------------

--
-- Structure for view `dchub_hot`
--
DROP TABLE IF EXISTS `dchub_hot`;

CREATE ALGORITHM=UNDEFINED DEFINER=`verlihub`@`localhost` SQL SECURITY DEFINER VIEW `dchub_hot` AS select `dchub_lcvotes`.`cid` AS `cid`,`dchub_lcvotes`.`votes` AS `votes`,`dchub_lcvotes`.`type` AS `type`,`dchub_lcvotes`.`time` AS `time`,`dchub_lcvotes`.`name` AS `name`,`dchub_lcvotes`.`tag` AS `tag`,`dchub_lcvotes`.`uid` AS `uid`,`dchub_lcvotes`.`magnetlink` AS `magnetlink`,`dchub_lcvotes`.`deleted` AS `deleted` from `dchub_lcvotes` union select `dchub_rcview`.`cid` AS `cid`,`dchub_rcview`.`votes` AS `votes`,`dchub_rcview`.`type` AS `type`,`dchub_rcview`.`time` AS `time`,`dchub_rcview`.`name` AS `name`,`dchub_rcview`.`tag` AS `tag`,`dchub_rcview`.`uid` AS `uid`,`dchub_rcview`.`magnetlink` AS `magnetlink`,`dchub_rcview`.`deleted` AS `deleted` from `dchub_rcview` where (`dchub_rcview`.`deleted` = 0) order by `votes` desc;

-- --------------------------------------------------------

--
-- Structure for view `dchub_lcvotes`
--
DROP TABLE IF EXISTS `dchub_lcvotes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`verlihub`@`localhost` SQL SECURITY DEFINER VIEW `dchub_lcvotes` AS select `rec`.`cid` AS `cid`,count(`rec`.`cid`) AS `votes`,`rec`.`type` AS `type`,`cont`.`timestamp` AS `time`,`cont`.`title` AS `name`,`cont`.`tag` AS `tag`,`cont`.`uid` AS `uid`,`cont`.`magnetlink` AS `magnetlink`,`cont`.`deleted` AS `deleted` from (`dchub_recommend` `rec` join `dchub_content` `cont`) where ((`rec`.`cid` = `cont`.`cid`) and (`rec`.`type` = 'lc')) group by `rec`.`cid`;

-- --------------------------------------------------------

--
-- Structure for view `dchub_rcview`
--
DROP TABLE IF EXISTS `dchub_rcview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`verlihub`@`localhost` SQL SECURITY DEFINER VIEW `dchub_rcview` AS select `rec`.`cid` AS `cid`,count(`rec`.`cid`) AS `votes`,`rec`.`type` AS `type`,`cont`.`timestamp` AS `time`,`cont`.`title` AS `name`,`cont`.`tag` AS `tag`,`cont`.`uid` AS `uid`,`cont`.`magnetlink` AS `magnetlink`,`cont`.`deleted` AS `deleted` from (`dchub_recommend` `rec` join `dchub_rc` `cont`) where ((`rec`.`cid` = `cont`.`cid`) and (`rec`.`type` = 'rc')) group by `rec`.`cid`;

-- --------------------------------------------------------

--
-- Structure for view `msgarchive`
--
DROP TABLE IF EXISTS `msgarchive`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `msgarchive` AS select `touser`.`nick1` AS `tonick`,`fromuser`.`nick1` AS `fromnick`,`dchub_message`.`msg` AS `msg`,`dchub_message`.`createdOn` AS `createdOn` from ((`dchub_message` join `dchub_users` `touser`) join `dchub_users` `fromuser`) where ((`dchub_message`.`toid` = `touser`.`id`) and (`dchub_message`.`fromid` = `fromuser`.`id`) and (`dchub_message`.`deleted` = 0)) union select `dchub_log`.`nick_to` AS `tonick`,`dchub_log`.`nick` AS `fromnick`,`dchub_log`.`message` AS `msg`,`dchub_log`.`timedate` AS `createdOn` from `dchub_log` where (`dchub_log`.`logtype` = 'PM');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
