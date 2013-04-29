-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 29, 2013 at 01:36 PM
-- Server version: 5.5.31-0ubuntu0.13.04.1
-- PHP Version: 5.4.9-4ubuntu2

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
  `nick_op` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8_unicode_ci,
  `share_size` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  UNIQUE KEY `ip` (`ip`,`nick`),
  KEY `nick_index` (`nick`),
  KEY `date_index` (`date_limit`),
  KEY `range_index` (`range_fr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conn_types`
--

CREATE TABLE IF NOT EXISTS `conn_types` (
  `identifier` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(64) COLLATE utf8_unicode_ci DEFAULT 'no description',
  `tag_min_slots` tinyint(4) DEFAULT '0',
  `tag_max_slots` tinyint(4) DEFAULT '100',
  `tag_min_limit` double DEFAULT '-1',
  `tag_min_ls_ratio` double DEFAULT '-1',
  PRIMARY KEY (`identifier`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `conn_types`
--

INSERT INTO `conn_types` (`identifier`, `description`, `tag_min_slots`, `tag_max_slots`, `tag_min_limit`, `tag_min_ls_ratio`) VALUES
('default', 'Default settings for missing conn types', 0, 100, -1, -1),
('28.8Kbps', 'Modem 28.8 Kbps', 0, 100, -1, -1),
('33.6Kbps', 'Modem 33.6 Kbps', 0, 100, -1, -1),
('56Kbps', 'Modem 56 Kbps', 0, 100, -1, -1),
('Modem', 'Modem', 0, 100, -1, -1),
('ISDN', 'ISDN', 0, 100, -1, -1),
('Cable', 'Cable', 0, 100, -1, -1),
('DSL', 'xDSL connections', 0, 100, -1, -1),
('Satellite', 'Sattelite connections', 0, 100, -1, -1),
('Microwave', 'Microwave', 0, 100, -1, -1),
('Wireless', 'WiFi connection', 0, 100, -1, -1),
('LAN(T1)', 'Local Area Network T1 ~ 10Mbps', 0, 100, -1, -1),
('LAN(T3)', 'Local Area Network T3 ~100Mbps', 0, 100, -1, -1);

-- --------------------------------------------------------

--
-- Table structure for table `custom_redirects`
--

CREATE TABLE IF NOT EXISTS `custom_redirects` (
  `address` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  `flag` tinyint(2) NOT NULL,
  `enable` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`address`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `file_trigger`
--

INSERT INTO `file_trigger` (`command`, `send_as`, `def`, `descr`, `min_class`, `max_class`, `flags`, `seconds`) VALUES
('+motd', 'MOTD', '%[CFG]/motd', 'Message of the day', 0, 10, 4, 0),
('+motd_reg', 'MOTD', '%[CFG]/motd_reg', 'Message of the day', 1, 10, 4, 0),
('+motd_vip', 'MOTD', '%[CFG]/motd_vip', 'Message of the day', 2, 10, 4, 0),
('+motd_op', 'MOTD', '%[CFG]/motd_op', 'Message of the day', 3, 10, 4, 0),
('+motd_cheef', 'MOTD', '%[CFG]/motd_cheef', 'Message of the day', 4, 10, 4, 0),
('+motd_admin', 'MOTD', '%[CFG]/motd_admin', 'Message of the day', 5, 10, 4, 0),
('+motd_master', 'MOTD', '%[CFG]/motd_master', 'Message of the day', 10, 10, 4, 0),
('+help_usr', 'HELP', '%[CFG]/help_usr', 'Help text', 0, 10, 8, 0),
('+help_reg', 'HELP', '%[CFG]/help_reg', 'Help text', 1, 10, 8, 0),
('+help_vip', 'HELP', '%[CFG]/help_vip', 'Help text', 2, 10, 8, 0),
('+help_op', 'HELP', '%[CFG]/help_op', 'Help text', 3, 10, 8, 0),
('+help_cheef', 'HELP', '%[CFG]/help_cheef', 'Help text', 4, 10, 8, 0),
('+help_admin', 'HELP', '%[CFG]/help_admin', 'Help text', 5, 10, 8, 0),
('+help_master', 'HELP', '%[CFG]/help_master', 'Help text', 10, 10, 8, 0),
('+rules', 'RULES', '%[CFG]/rules', 'Hub rules', 0, 10, 2, 0),
('+faq', 'FAQ', '%[CFG]/faq', 'Frequently asked quenstions', 0, 10, 2, 0),
('+sysversion', 'SystemVersion', '/proc/version', 'Operating System Version', 5, 10, 4, 0),
('+admin_reminder', 'Reminder', '\r\n	Congratulations you successfully installed Verlihub%[VERSION]\r\n	\r\n	Please visit the Web sites for imformation about the Verlihub project. It can be found here http://www.verlihub-project.org\r\n	\r\n	Verlihub Admin Zone DC Hub: dchub://hub.verlihub-project.org:7777\r\n	\r\n	Verlihub discussion forum: http://forums.verlihub-project.org\r\n	', 'Admin reminder', 5, 10, 62, 0),
('+vh_donation', 'Donation', '\r\n	If you are interested in making a donation to the author or developers of the verlihub\r\n	project please visit our website for more information. http://www.verlihub-project.org/donations.php\r\n\r\n	To diable this message, type the command !modtrigger +vh_donation -c11 in mainchat.\r\n	', 'Donation Reminder', 10, 10, 62, 0),
('+updates', 'Updates', '\r\n	Many things have been fixed since 0.9.8d-RC2 and this is considered to be our most functional\r\n	and stable release to date. While the functionality of the software is richer then ever,\r\n	its resource usage is at an all time low. It is now an extremely scaleable server.\r\n	All known exploits have now been permanently closed to help protect both you AND other\r\n	hubs in the DC community.\r\n	\r\n	Get Rid of this message by typing the command !modtrigger +updates -c11 in mainchat.\r\n	', 'Updates', 5, 10, 62, 0),
('+news', 'News', '\r\n	This will be the final release candidate of the Verlihub 0.9.8 tree. Work has now begun\r\n	on the next major version of verlihub with planned support for new protocol extensions as\r\n	well as some significant new features.\r\n	Thanks to everyone who contributes their free time and effort for all aspects of the verlihub project.\r\n	\r\n	Get Rid of this message by typing the command !modtrigger +news -c11 in mainchat.\r\n	', 'News', 5, 10, 62, 0),
('new', '', '/etc/verlihub/notice', NULL, 0, 10, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `kicklist`
--

CREATE TABLE IF NOT EXISTS `kicklist` (
  `nick` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `host` text COLLATE utf8_unicode_ci,
  `share_size` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8_unicode_ci,
  `op` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `is_drop` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`nick`,`time`),
  KEY `op_index` (`op`),
  KEY `ip_index` (`ip`),
  KEY `drop_index` (`is_drop`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pi_plug`
--

INSERT INTO `pi_plug` (`nick`, `path`, `dest`, `detail`, `autoload`, `reload`, `unload`, `error`, `lastload`) VALUES
('isp', '/usr/local/lib/libisp_pi.so', NULL, 'Internet Service Provider settings, country codes, nick prefix, desc tag,...', 0, 0, 0, NULL, NULL),
('lua', '/usr/local/lib/liblua_pi.so', NULL, 'Support for lua scripts', 0, 0, 0, NULL, NULL),
('perl', '/usr/local/lib/libperl_pi.so', NULL, 'Support for perl scripts', 0, 0, 0, NULL, NULL),
('msg', '/usr/local/lib/libmessanger_pi.so', NULL, 'Offline messages system', 0, 0, 0, NULL, NULL),
('flood', '/usr/local/lib/libfloodprot_pi.so', NULL, 'Advanced flood protection', 0, 0, 0, NULL, NULL),
('log', '/usr/local/lib/libiplog_pi.so', NULL, 'Log ip''s, nicks; history commands', 0, 0, 0, NULL, NULL),
('forbid', '/usr/local/lib/libforbid_pi.so', NULL, 'Filter chat from forbidden words', 0, 0, 0, NULL, NULL),
('chat', '/usr/local/lib/libchatroom_pi.so', NULL, 'Multiple chatrooms to separate chat topics', 0, 0, 0, NULL, NULL),
('replace', '/usr/local/lib/libreplace_pi.so', NULL, 'Replace some words by other', 0, 0, 0, NULL, NULL),
('stats', '/usr/local/lib/libstats_pi.so', NULL, 'Statistics plugin, trace diverse value sin the database', 0, 0, 0, NULL, NULL),
('hublink', '/usr/local/lib/libhublink_pi.so', NULL, 'Link multiple hubs together', 0, 0, 0, NULL, NULL);

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
  `reg_op` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
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
  `alternate_ip` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`nick`),
  KEY `login_index` (`login_last`),
  KEY `logout_index` (`logout_last`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `reglist`
--

INSERT INTO `reglist` (`nick`, `class`, `class_protect`, `class_hidekick`, `hide_kick`, `hide_keys`, `hide_share`, `reg_date`, `reg_op`, `pwd_change`, `pwd_crypt`, `login_pwd`, `login_last`, `logout_last`, `login_cnt`, `login_ip`, `error_last`, `error_cnt`, `error_ip`, `enabled`, `email`, `note_op`, `note_usr`, `alternate_ip`) VALUES
('DeathEater', 10, 0, 0, 0, 0, 0, 1357382094, 'admin_root', 0, 1, 'CCPIvz7evziI6', 1357458495, 1357464631, 11, '192.168.152.158', NULL, 0, NULL, 1, NULL, NULL, NULL, NULL),
('pinger', -1, 0, 0, 0, 0, 0, 1357382094, 'installation', 0, 1, NULL, 1357932036, 1357933917, 1, '192.168.152.150', NULL, 0, NULL, 1, NULL, 'generic pinger nick', NULL, NULL),
('dchublist', -1, 0, 0, 0, 0, 0, 1357382094, 'installation', 0, 1, NULL, 0, 0, 0, NULL, NULL, 0, NULL, 1, NULL, 'dchublist pinger', NULL, NULL),
('gajodhar_DE', 0, 0, 0, 0, 0, 0, 1357396744, 'web', 0, 0, 'hellgate', 0, 0, 0, '192.168.152.158', NULL, 0, NULL, 1, 'pushkar8723@gmail.com', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `SetupList`
--

CREATE TABLE IF NOT EXISTS `SetupList` (
  `file` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `var` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `val` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`file`,`var`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `SetupList`
--

INSERT INTO `SetupList` (`file`, `var`, `val`) VALUES
('config', 'hub_name', 'hub of root'),
('config', 'hub_desc', 'No Description'),
('config', 'hub_category', NULL),
('config', 'hub_owner', NULL),
('config', 'hub_version', '0.9.8e-r2'),
('config', 'hub_version_special', NULL),
('config', 'hub_security', 'VerliHub'),
('config', 'hub_security_desc', 'Hub-Security for broadcasting messages'),
('config', 'opchat_name', 'OpChat'),
('config', 'opchat_desc', 'Operator chat - only for OPs'),
('config', 'hub_host', '192.168.152.58:411'),
('config', 'listen_ip', '0.0.0.0'),
('config', 'listen_port', '411'),
('config', 'extra_listen_ports', NULL),
('config', 'hublist_host', NULL),
('config', 'hublist_port', '2501'),
('config', 'hublist_send_minshare', '1'),
('config', 'timer_hublist_period', '0'),
('config', 'max_users', '6000'),
('config', 'max_extra_regs', '25'),
('config', 'max_extra_vips', '50'),
('config', 'max_extra_ops', '100'),
('config', 'max_extra_cheefs', '100'),
('config', 'max_extra_admins', '200'),
('config', 'max_users0', '600'),
('config', 'max_users1', '5400'),
('config', 'max_users2', '1000'),
('config', 'max_users3', '1000'),
('config', 'max_users4', '1000'),
('config', 'max_users5', '1000'),
('config', 'max_users6', '1000'),
('config', 'min_share', '1024'),
('config', 'min_share_reg', '1024'),
('config', 'min_share_vip', '1024'),
('config', 'min_share_ops', '0'),
('config', 'min_share_factor_passive', '1.000000'),
('config', 'min_share_use_hub', '0'),
('config', 'min_share_use_hub_reg', '0'),
('config', 'min_share_use_hub_vip', '0'),
('config', 'max_share', '10485760'),
('config', 'max_share_reg', '10485760'),
('config', 'max_share_vip', '10485760'),
('config', 'max_share_ops', '10485760'),
('config', 'int_search', '32'),
('config', 'int_search_pas', '48'),
('config', 'int_search_reg', '16'),
('config', 'int_search_reg_pass', '48'),
('config', 'int_search_vip', '8'),
('config', 'int_search_op', '1'),
('config', 'min_search_chars', '5'),
('config', 'max_passive_sr', '25'),
('config', 'delayed_search', '1'),
('config', 'max_nick', '64'),
('config', 'min_nick', '3'),
('config', 'nick_chars', NULL),
('config', 'nick_prefix', NULL),
('config', 'nick_prefix_cc', '0'),
('config', 'nick_prefix_autoreg', NULL),
('config', 'autoreg_class', '-1'),
('config', 'nicklist_on_login', '1'),
('config', 'optimize_userlist', '0'),
('config', 'ul_portion', '50'),
('config', 'max_message_size', '10240'),
('config', 'max_chat_msg', '256'),
('config', 'max_chat_lines', '5'),
('config', 'delayed_chat', '0'),
('config', 'int_chat_ms', '1000'),
('config', 'chat_default_on', '1'),
('config', 'mainchat_class', '0'),
('config', 'max_flood_counter_pm', '5'),
('config', 'int_flood_pm_period', '5'),
('config', 'int_flood_pm_limit', '5'),
('config', 'classdif_reg', '2'),
('config', 'classdif_kick', '0'),
('config', 'classdif_pm', '10'),
('config', 'classdif_download', '10'),
('config', 'min_class_use_hub', '0'),
('config', 'min_class_use_hub_passive', '0'),
('config', 'min_class_register', '4'),
('config', 'min_class_redir', '4'),
('config', 'min_class_bc', '4'),
('config', 'min_class_bc_guests', '4'),
('config', 'min_class_bc_regs', '4'),
('config', 'min_class_bc_vips', '4'),
('config', 'bc_reply', NULL),
('config', 'plugin_mod_class', '5'),
('config', 'topic_mod_class', '5'),
('config', 'cmd_start_op', '!'),
('config', 'cmd_start_user', '+'),
('config', 'dest_report_chat', '0'),
('config', 'dest_regme_chat', '0'),
('config', 'dest_drop_chat', '0'),
('config', 'disable_me_cmd', '0'),
('config', 'disable_regme_cmd', '0'),
('config', 'disable_usr_cmds', '0'),
('config', 'disable_report_cmd', '0'),
('config', 'allways_ask_password', '0'),
('config', 'default_password_encryption', '1'),
('config', 'password_min_len', '6'),
('config', 'pwd_tmpban', '10'),
('config', 'wrongpassword_report', '1'),
('config', 'botinfo_report', '0'),
('config', 'send_user_ip', '0'),
('config', 'send_user_info', '1'),
('config', 'int_myinfo', '60'),
('config', 'int_nicklist', '60'),
('config', 'int_login', '60'),
('config', 'max_class_int_login', '4'),
('config', 'tban_kick', '300'),
('config', 'tban_max', '86400'),
('config', 'log_level', '0'),
('config', 'dns_lookup', '0'),
('config', 'report_dns_lookup', '0'),
('config', 'hide_all_kicks', '0'),
('config', 'timer_conn_period', '4'),
('config', 'timer_serv_period', '1'),
('config', 'min_frequency', '0.300000'),
('config', 'max_upload_kbps', '2000000.000000'),
('config', 'step_delay', '50'),
('config', 'timer_reloadcfg_period', '300'),
('config', 'use_reglist_cache', '1'),
('config', 'use_penlist_cache', '1'),
('config', 'delayed_login', '1'),
('config', 'delayed_myinfo', '1'),
('config', 'drop_invalid_key', '0'),
('config', 'delayed_ping', '60'),
('config', 'timeout_key', '60.000000'),
('config', 'timeout_nick', '30.000000'),
('config', 'timeout_login', '600.000000'),
('config', 'timeout_myinfo', '40.000000'),
('config', 'timeout_flush', '30.000000'),
('config', 'timeout_setpass', '300.000000'),
('config', 'show_tags', '2'),
('config', 'tag_allow_none', '1'),
('config', 'tag_allow_sock5', '1'),
('config', 'tag_sum_hubs', '2'),
('config', 'tag_min_class_ignore', '3'),
('config', 'show_desc_len', '-1'),
('config', 'desc_insert_mode', '0'),
('config', 'show_email', '1'),
('config', 'show_speed', '1'),
('config', 'tag_max_hs_ratio', '100.000000'),
('config', 'tag_max_hubs', '100'),
('config', 'tag_min_version_plusplus', '0.000000'),
('config', 'tag_max_version_plusplus', '100.000000'),
('config', 'tag_min_version_dcgui', '0.000000'),
('config', 'tag_max_version_dcgui', '100.000000'),
('config', 'tag_min_version_odc', '0.000000'),
('config', 'tag_max_version_odc', '100.000000'),
('config', 'tag_min_version_dc', '0.000000'),
('config', 'tag_max_version_dc', '100.000000'),
('config', 'tag_min_version_dcpro', '0.000000'),
('config', 'tag_max_version_dcpro', '100.000000'),
('config', 'tag_min_version_strongdc', '0.000000'),
('config', 'tag_max_version_strongdc', '100.000000'),
('config', 'tag_min_version_idc', '0.000000'),
('config', 'tag_max_version_idc', '100.000000'),
('config', 'tag_min_version_zdc', '0.000000'),
('config', 'tag_max_version_zdc', '100.000000'),
('config', 'tag_min_version_apexdc', '0.000000'),
('config', 'tag_max_version_apexdc', '100.000000'),
('config', 'tag_min_version_zion', '0.000000'),
('config', 'tag_max_version_zion', '100.000000'),
('config', 'cc_zone1', NULL),
('config', 'cc_zone2', NULL),
('config', 'cc_zone3', NULL),
('config', 'ip_zone4_min', NULL),
('config', 'ip_zone4_max', NULL),
('config', 'ip_zone5_min', NULL),
('config', 'ip_zone5_max', NULL),
('config', 'ip_zone6_min', NULL),
('config', 'ip_zone6_max', NULL),
('config', 'msg_chat_onoff', '<< To turn your chat on, use command +chat turn it off with +nochat >>'),
('config', 'msg_change_pwd', '<< Please change your password NOW using command +passwd new_passwd!!! See +help>>'),
('config', 'msg_banned', '<<You are banned and this is a default extra message>>'),
('config', 'msg_hub_full', '<<User limit exceeded, hub is full.>>'),
('config', 'msg_nick_prefix', '<<Invalid nick prefix>>'),
('config', 'msg_downgrade', '<<Your client version is too recent.>>'),
('config', 'msg_upgrade', '<<Your client version is too old, please upgrade!>>'),
('config', 'msg_replace_ban', NULL),
('config', 'login_share_min', 'You share %[share]MB, but the min share is %[min_share]MB. (active:%[min_share_active]MB / passive:%[min_share_passive])'),
('config', 'login_share_max', 'You share %[share]MB, but the max share is %[max_share]MB.'),
('config', 'autoreg_min_share', 'You need to share at least %[min_share] MB'),
('config', 'search_share_min', 'You can''t search on this hub unless you share %[min_share_use_hub].'),
('config', 'ctm_share_min', 'You can''t download on this hub unless you share %[min_share_use_hub].'),
('config', 'msg_welcome_guest', NULL),
('config', 'msg_welcome_reg', NULL),
('config', 'msg_welcome_vip', NULL),
('config', 'msg_welcome_op', NULL),
('config', 'msg_welcome_cheef', NULL),
('config', 'msg_welcome_admin', NULL),
('config', 'msg_welcome_master', NULL),
('config', 'save_lang', '0');

-- --------------------------------------------------------

--
-- Table structure for table `temp_rights`
--

CREATE TABLE IF NOT EXISTS `temp_rights` (
  `nick` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `nick_op` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8_unicode_ci,
  `share_size` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_unban` int(11) DEFAULT NULL,
  `unban_op` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unban_reason` text COLLATE utf8_unicode_ci,
  UNIQUE KEY `ip` (`ip`,`nick`,`date_unban`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wi_complaints`
--

CREATE TABLE IF NOT EXISTS `wi_complaints` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `title` tinytext,
  `description` longtext,
  `reply` longtext,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wi_content`
--

CREATE TABLE IF NOT EXISTS `wi_content` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `title` tinytext,
  `description` tinytext,
  `magnetlink` tinytext,
  `tag` text NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `wi_content`
--

INSERT INTO `wi_content` (`cid`, `deleted`, `uid`, `timestamp`, `title`, `description`, `magnetlink`, `tag`) VALUES
(1, 0, 1, NULL, 'IronMan 3', NULL, NULL, 'Movie, action, superhero');

-- --------------------------------------------------------

--
-- Table structure for table `wi_content_tags`
--

CREATE TABLE IF NOT EXISTS `wi_content_tags` (
  `cid` int(11) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wi_tags`
--

CREATE TABLE IF NOT EXISTS `wi_tags` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) DEFAULT NULL,
  `tagname` tinytext,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wi_users`
--

CREATE TABLE IF NOT EXISTS `wi_users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` tinytext,
  `deleted` tinyint(1) DEFAULT NULL,
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
  `note` tinytext,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `wi_users`
--

INSERT INTO `wi_users` (`uid`, `nickname`, `deleted`, `password_`, `email`, `ipaddress`, `fullname`, `roll_course`, `roll_number`, `roll_year`, `branch`, `hostel`, `room`, `phone`, `question`, `answer`, `friend`, `class`, `note`) VALUES
(1, 'gajodhar_DE', 0, 'hellgate', 'pushkar8723@gmail.com', '192.168.152.158', 'Pushkar Anand', 'BE', 1322, 2010, 'Information Technology', '12', '158', '', 'You Shall not PASS', 'goaway', -1, 10, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
