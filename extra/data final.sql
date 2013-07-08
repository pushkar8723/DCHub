-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 08, 2013 at 05:15 PM
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

--
-- Dumping data for table `dchub_branch`
--

INSERT INTO `dchub_branch` (`id`, `branch`) VALUES
(1, 'ECE'),
(2, 'EEE'),
(3, 'CSE'),
(4, 'IT'),
(5, 'Mech'),
(6, 'Civil'),
(7, 'Prod'),
(8, 'Bio-Tech'),
(9, 'Architecture'),
(10, 'MCA'),
(11, 'PHD'),
(12, 'Staff'),
(13, 'Chem and Polymer '),
(14, 'Chemical Engineering'),
(15, 'B. Pharma'),
(16, 'Hotel Management'),
(17, 'MSC'),
(18, 'MBA');

--
-- Dumping data for table `dchub_groups`
--

INSERT INTO `dchub_groups` (`id`, `name`, `description`, `identifier`, `moderators`, `deleted`, `createdOn`, `updatedOn`) VALUES
(1, 'Everybody', '', 'Everybody', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, '2k10', '', '2k10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, '2k11', '', '2k11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, '2k12', '', '2k12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, '2k13', '', '2k13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'ECE', '', '1', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'EEE', '', '2', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'CSE', '', '3', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'IT', '', '4', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'Mech', '', '5', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'Civil', '', '6', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'Prod', '', '7', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'Bio-Tech', '', '8', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'Architecture', '', '9', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'MCA', '', '10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'PHD', '', '11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'Staff', '', '12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'Chem and Polymer ', '', '13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'Chemical Engineering', '', '14', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'B. Pharma', '', '15', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'Hotel Management', '', '16', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'MSC', '', '17', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'MBA', '', '18', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 'ECE-2k10', '', '1-2k10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 'ECE-2k11', '', '1-2k11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 'ECE-2k12', '', '1-2k12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, 'ECE-2k13', '', '1-2k13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 'EEE-2k10', '', '2-2k10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(42, 'EEE-2k11', '', '2-2k11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, 'EEE-2k12', '', '2-2k12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, 'EEE-2k13', '', '2-2k13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, 'CSE-2k10', '', '3-2k10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, 'CSE-2k11', '', '3-2k11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, 'CSE-2k12', '', '3-2k12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, 'CSE-2k13', '', '3-2k13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, 'IT-2k10', '', '4-2k10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, 'IT-2k11', '', '4-2k11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, 'IT-2k12', '', '4-2k12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, 'IT-2k13', '', '4-2k13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(53, 'Mech-2k10', '', '5-2k10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(54, 'Mech-2k11', '', '5-2k11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, 'Mech-2k12', '', '5-2k12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(56, 'Mech-2k13', '', '5-2k13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, 'Civil-2k10', '', '6-2k10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, 'Civil-2k11', '', '6-2k11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(59, 'Civil-2k12', '', '6-2k12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60, 'Civil-2k13', '', '6-2k13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(61, 'Prod-2k10', '', '7-2k10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, 'Prod-2k11', '', '7-2k11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(63, 'Prod-2k12', '', '7-2k12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, 'Prod-2k13', '', '7-2k13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(65, 'Bio-Tech-2k10', '', '8-2k10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 'Bio-Tech', '', '8-2k11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(67, 'Bio-Tech', '', '8-2k12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, 'Bio-Tech', '', '8-2k13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, 'Architecture-2k10', '', '9-2k10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70, 'Architecture-2k11', '', '9-2k11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(71, 'Architecture-2k12', '', '9-2k12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, 'Architecture-2k13', '', '9-2k13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, 'MCA-2k10', '', '10-2k10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, 'MCA-2k11', '', '10-2k11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, 'MCA-2k12', '', '10-2k12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(76, 'MCA-2k13', '', '10-2k13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(77, 'PHD-2k10', '', '11-2k10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(78, 'PHD-2k11', '', '11-2k11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(79, 'PHD-2k12', '', '11-2k12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(80, 'PHD-2k13', '', '11-2k13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(81, 'Staff-2k10', '', '12-2k10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(82, 'Staff-2k11', '', '12-2k11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(83, 'Staff-2k12', '', '12-2k12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(84, 'Staff-2k13', '', '12-2k13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(85, 'Chem and Polymer-2k10', '', '13-2k10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(86, 'Chem and Polymer-2k11', '', '13-2k11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(87, 'Chem and Polymer-2k12', '', '13-2k12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(88, 'Chem and Polymer-2k13', '', '13-2k13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(89, 'Chemical Engineering-2k10', '', '14-2k10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90, 'Chemical Engineering-2k11', '', '14-2k11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(91, 'Chemical Engineering-2k12', '', '14-2k12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(92, 'Chemical Engineering-2k13', '', '14-2k13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(93, 'B. Pharma-2k10', '', '15-2k10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(94, 'B. Pharma-2k11', '', '15-2k11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(95, 'B. Pharma-2k12', '', '15-2k12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(96, 'B. Pharma-2k13', '', '15-2k13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 'Hotel Management-2k10', '', '16-2k10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 'Hotel Management-2k11', '', '16-2k11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(99, 'Hotel Management-2k12', '', '16-2k12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(100, 'Hotel Management-2k13', '', '16-2k13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(101, 'MSC-2k10', '', '17-2k10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 'MSC-2k11', '', '17-2k11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(103, 'MSC-2k12', '', '17-2k12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(104, 'MSC-2k13', '', '17-2k13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(105, 'MBA-2k10', '', '18-2k10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(106, 'MBA-2k11', '', '18-2k11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(107, 'MBA-2k12', '', '18-2k12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(108, 'MBA-2k13', '', '18-2k13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(164, 'H-1', '', 'H-1', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(165, 'H-2', '', 'H-2', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(166, 'H-3', '', 'H-3', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(167, 'H-4', '', 'H-4', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(168, 'H-5', '', 'H-5', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(169, 'H-6', '', 'H-6', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(170, 'H-7', '', 'H-7', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(171, 'H-8', '', 'H-8', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(172, 'H-9', '', 'H-9', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(173, 'H-10', '', 'H-10', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(174, 'H-11', '', 'H-11', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(175, 'H-12', '', 'H-12', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(176, 'H-13', '', 'H-13', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(177, 'H-RS', '', 'H-RS', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
