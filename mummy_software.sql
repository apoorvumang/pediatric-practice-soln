-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 13, 2012 at 09:26 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mummy_software`
--

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE IF NOT EXISTS `doctors` (
  `username` varchar(255) NOT NULL,
  `name` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`username`, `name`, `password`) VALUES
('mahima', 'Mahima Anurag', '227dd828170f456f4fb2ac146846470b');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE IF NOT EXISTS `patients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_dob` varchar(6) NOT NULL,
  `name` varchar(25) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `middle_name` varchar(25) DEFAULT NULL,
  `last_name` varchar(25) NOT NULL,
  `father_name` varchar(25) DEFAULT NULL,
  `father_occ` varchar(25) DEFAULT NULL,
  `mother_name` varchar(25) DEFAULT NULL,
  `mother_occ` varchar(25) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `sibling` int(11) NOT NULL DEFAULT '0',
  `pin` varchar(6) DEFAULT NULL,
  `res_phone` varchar(10) DEFAULT NULL,
  `office_phone` varchar(10) DEFAULT NULL,
  `dob` date NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `sex` enum('M','F') NOT NULL DEFAULT 'M',
  `born_at` varchar(25) DEFAULT NULL,
  `mode_of_delivery` enum('Normal','Caesarean','Forceps','Vacuum') DEFAULT 'Normal',
  `birth_weight` varchar(10) DEFAULT NULL,
  `comp_preg` varchar(25) DEFAULT 'No',
  `comp_deli` varchar(25) DEFAULT 'No',
  `cry_birth` varchar(25) DEFAULT 'Yes',
  `admit_hospital` varchar(25) DEFAULT 'No',
  `injury_child` varchar(25) DEFAULT 'No',
  `prev_surgery` varchar(25) DEFAULT 'No',
  `allergy_child` varchar(25) DEFAULT 'No',
  `other_info` varchar(25) DEFAULT 'No',
  `diabetes` varchar(25) DEFAULT 'No',
  `asthma` varchar(25) DEFAULT 'No',
  `cardiac` varchar(25) DEFAULT 'No',
  `hypertension` varchar(25) DEFAULT 'No',
  `tuberculosis` varchar(25) DEFAULT 'No',
  `other_fam_history` varchar(25) DEFAULT 'No',
  `breast_feed_month` varchar(10) DEFAULT NULL,
  `solid_month` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `id_dob`, `name`, `first_name`, `middle_name`, `last_name`, `father_name`, `father_occ`, `mother_name`, `mother_occ`, `address`, `sibling`, `pin`, `res_phone`, `office_phone`, `dob`, `email`, `phone`, `sex`, `born_at`, `mode_of_delivery`, `birth_weight`, `comp_preg`, `comp_deli`, `cry_birth`, `admit_hospital`, `injury_child`, `prev_surgery`, `allergy_child`, `other_info`, `diabetes`, `asthma`, `cardiac`, `hypertension`, `tuberculosis`, `other_fam_history`, `breast_feed_month`, `solid_month`) VALUES
(19, '', 'Richa Vats', 'Richa', NULL, 'Vats', 'Rakesh Vats', '', '', '', 'WZ-28, Naraina Village,New Delhi', 20, NULL, NULL, NULL, '1989-03-26', '', '9968262526', 'F', NULL, 'Normal', NULL, 'No', 'No', 'Yes', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', NULL, NULL),
(20, '', 'Gaurav Vats', 'Gaurav', NULL, 'Vats', 'Rakesh Vats', '', '', '', 'WZ-28, Naraina Village,New Delhi', 19, NULL, NULL, NULL, '1991-04-10', '', '9968262526', 'M', NULL, 'Normal', NULL, 'No', 'No', 'Yes', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', NULL, NULL),
(21, '', 'Mohit Bansal', 'Mohit', NULL, 'Bansal', 'Darshan Bansal', '', '', '', 'T-4/6, URI Enclave,Delhi Cantonment, New Delhi,', 22, NULL, NULL, NULL, '1990-06-27', '', '', 'M', NULL, 'Normal', NULL, 'No', 'No', 'Yes', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', NULL, NULL),
(22, '', 'Sonam Bansal', 'Sonam', NULL, 'Bansal', 'Darshan Bansal', '', '', '', 'T- 4/6, URI Enclave, Delhi Cantonment, New Delhi', 21, NULL, NULL, NULL, '1985-05-20', '', '0113299546', 'F', NULL, 'Normal', NULL, 'No', 'No', 'Yes', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', NULL, NULL),
(23, '', 'UPENDER (GAURAV) SINGH', 'UPENDER (GAURAV)', NULL, 'SINGH', 'INDERJEET SINGH', '', 'PAMMI', '', 'B-41, GROUND FLOOR, NARAINA VIHAR, NEW DELHI, 110028', 0, NULL, NULL, NULL, '1992-06-20', '', '', 'M', NULL, 'Normal', NULL, 'No', 'No', 'Yes', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vaccines`
--

CREATE TABLE IF NOT EXISTS `vaccines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(35) NOT NULL,
  `no_of_days` int(11) NOT NULL,
  `dependent` int(11) NOT NULL,
  `sex` enum('B','F','M') NOT NULL DEFAULT 'B',
  `lower_limit` int(11) NOT NULL DEFAULT '0',
  `upper_limit` int(11) NOT NULL DEFAULT '999999',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=53 ;

--
-- Dumping data for table `vaccines`
--

INSERT INTO `vaccines` (`id`, `name`, `no_of_days`, `dependent`, `sex`, `lower_limit`, `upper_limit`) VALUES
(1, 'BCG', 30, 0, 'B', 0, 365),
(3, 'CERVARIX 1 (FEMALES ONLY)', 3285, 0, 'F', 3285, 99999),
(4, 'CERVARIX 2 (FEMALES ONLY)', 30, 3, 'F', 3315, 99999),
(5, 'CERVARIX 3 (FEMALES ONLY)', 150, 4, 'F', 3435, 999999),
(6, 'CHICKEN-POX 1', 455, 0, 'B', 455, 99999),
(7, 'CHICKENPOX BOOSTER', 1460, 0, 'B', 1460, 99999),
(8, 'DTwP/ DTaP & Oral Polio- 1', 60, 0, 'B', 45, 2555),
(9, 'DTwP/ DTaP & Oral Polio- 2', 30, 8, 'B', 75, 2555),
(10, 'DTwP/ DTaP & Oral Polio - 3', 30, 9, 'B', 98, 2555),
(11, 'DTwP/ DTaP & Oral Polio (BOOSTER 1)', 365, 10, 'B', 456, 547),
(12, 'DTwP/ DTaP & Oral Polio (BOOSTER 2)', 1080, 11, 'B', 1460, 2190),
(13, 'HEPATITIS-A 1', 372, 0, 'B', 365, 99999),
(14, 'HEPATITIS-A 2', 220, 13, 'B', 180, 99999),
(15, 'HEPATITIS-B - 1', 0, 0, 'B', 0, 99999),
(16, 'HEPATITIS-B - 2', 30, 15, 'B', 30, 999999),
(17, 'HEPATITIS-B - 3', 150, 16, 'B', 180, 99999),
(18, 'HIB BOOSTER', 365, 21, 'B', 456, 1825),
(19, 'HIB I', 60, 0, 'B', 45, 1825),
(20, 'HIB II', 30, 19, 'B', 75, 1825),
(21, 'HIB III', 30, 20, 'B', 98, 1825),
(22, 'INFLUENZA I DOSE', 220, 0, 'B', 180, 99999),
(23, 'INFLUENZA II DOSE', 30, 22, 'B', 240, 2920),
(24, 'INFLUENZA BOOSTER 1', 365, 23, 'B', 547, 99999),
(25, 'INFLUENZA BOOSTER 2	', 365, 24, 'B', 365, 99999),
(26, 'INFLUENZA BOOSTER 3', 365, 25, 'B', 365, 99999),
(27, 'INFLUENZA BOOSTER 4', 365, 26, 'B', 240, 99999),
(28, 'IPV-1', 60, 0, 'B', 45, 1825),
(29, 'IPV-2', 30, 28, 'B', 75, 1825),
(30, 'IPV-3', 30, 29, 'B', 98, 1825),
(31, 'IPV BOOSTER 1', 365, 30, 'B', 547, 1825),
(32, 'IPV BOOSTER 2', 912, 31, 'B', 45, 1825),
(33, 'MEASLES', 250, 0, 'B', 240, 365),
(34, 'MENIINGOCO MENINGITIS I', 730, 0, 'B', 730, 99999),
(35, 'MENINGOCO MENINGITIS II', 1095, 34, 'B', 730, 99999),
(36, 'MENINGOCO MENINGITIS III', 1095, 35, 'B', 730, 99999),
(37, 'MMR', 405, 0, 'B', 365, 99999),
(38, 'MMR BOOSTER', 1662, 0, 'B', 395, 99999),
(39, 'PNEUMOCOCCAL 1', 60, 0, 'B', 45, 1825),
(40, 'PNEUMOCOCCAL 2', 30, 39, 'B', 75, 1825),
(41, 'PNEUMOCOCCAL 3', 30, 40, 'B', 105, 1825),
(42, 'PNEUMOCOCCAL BOOSTER', 365, 41, 'B', 456, 1825),
(43, 'ROTARIX 1 DOSE', 60, 0, 'B', 42, 112),
(44, 'ROTARIX 2 DOSE', 30, 43, 'B', 72, 240),
(45, 'Tdap/ Td /TT', 3680, 0, 'B', 3650, 99999),
(46, 'TYPHOID', 737, 0, 'B', 730, 99999),
(47, 'TYPHOID BOOSTER 1', 1095, 46, 'B', 730, 99999),
(48, 'TYPHOID BOOSTER 2', 1095, 47, 'B', 730, 99999),
(49, 'TYPHOID BOOSTER 3', 1095, 48, 'B', 730, 99999),
(50, 'TYPHOID BOOSTER 4', 1095, 49, 'B', 730, 99999),
(51, 'TYPHOID BOOSTER 5', 1095, 50, 'B', 730, 99999),
(52, 'Td BOOSTER/ Tdap', 3650, 45, 'B', 3650, 99999);

-- --------------------------------------------------------

--
-- Table structure for table `vac_make`
--

CREATE TABLE IF NOT EXISTS `vac_make` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

--
-- Dumping data for table `vac_make`
--

INSERT INTO `vac_make` (`id`, `name`) VALUES
(5, 'BCG'),
(6, 'INFANRIX'),
(7, 'TRIPACEL'),
(8, 'DTwP'),
(9, 'HIBERIX'),
(10, 'HIBPRO'),
(11, 'ACT HIB'),
(12, 'IMOVAX'),
(13, 'POLPRO'),
(14, 'ROTARIX'),
(15, 'ROTATEQ'),
(16, 'EASY-4'),
(17, 'QUADRIVAX'),
(18, 'Q-VAC'),
(19, 'COMBIVAC'),
(20, 'PENTAVAC'),
(21, 'PREVENAR-13'),
(22, 'SYNFLORIX'),
(23, 'M-VAC'),
(24, 'TRESIVAC'),
(25, 'PRIORIX'),
(26, 'ENGERIX-P'),
(27, 'ENGERIX-Adult'),
(28, 'SHANVAC-B Ped'),
(29, 'SHANVAC -B Adult'),
(30, 'Td'),
(31, 'BOOSTRIX'),
(32, 'ADACEL'),
(33, 'TYPHIM Vi'),
(34, 'TYPBAR'),
(35, 'HAVRIX-720'),
(36, 'HAVRIX-1440'),
(37, 'AXAXIM-80'),
(38, 'OKAVAX'),
(39, 'VARILRIX'),
(40, 'VARIVAX'),
(41, 'VAXIGRIP Ped'),
(42, 'VAXIGRIP Adult'),
(43, 'FLURIX'),
(44, 'CERVARIX'),
(45, 'GARDACEL'),
(46, 'SHANCHOL'),
(47, 'MENCEVAC A&C'),
(48, 'MENINGO ACWY'),
(49, 'TT');

-- --------------------------------------------------------

--
-- Table structure for table `vac_schedule`
--

CREATE TABLE IF NOT EXISTS `vac_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL,
  `v_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `date_given` date DEFAULT NULL,
  `given` enum('Y','N') NOT NULL DEFAULT 'N',
  `make` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=536 ;

--
-- Dumping data for table `vac_schedule`
--

INSERT INTO `vac_schedule` (`id`, `p_id`, `v_id`, `date`, `date_given`, `given`, `make`) VALUES
(290, 19, 1, '1989-04-25', '0000-00-00', 'Y', 0),
(291, 19, 3, '1998-03-24', '0000-00-00', 'N', 0),
(292, 19, 4, '1998-04-23', '0000-00-00', 'N', 0),
(293, 19, 5, '1998-09-20', '0000-00-00', 'N', 0),
(294, 19, 6, '1990-04-25', '2002-02-09', 'Y', 39),
(295, 19, 7, '1993-03-25', '0000-00-00', 'N', 0),
(296, 19, 8, '1989-05-25', '0000-00-00', 'Y', 8),
(297, 19, 9, '1989-06-24', '0000-00-00', 'Y', 8),
(298, 19, 10, '1989-07-24', '0000-00-00', 'Y', 8),
(299, 19, 11, '1990-07-24', '0000-00-00', 'Y', 8),
(300, 19, 12, '1993-10-13', '0000-00-00', 'Y', 8),
(301, 19, 13, '1990-03-31', '0000-00-00', 'N', 0),
(302, 19, 14, '1990-09-27', '0000-00-00', 'N', 0),
(303, 19, 15, '1989-03-26', '1997-09-01', 'Y', 26),
(304, 19, 16, '1989-04-25', '1997-10-01', 'Y', 26),
(305, 19, 17, '1989-09-22', '1998-03-02', 'Y', 26),
(306, 19, 18, '1990-07-24', '0000-00-00', 'N', 0),
(307, 19, 19, '1989-05-25', '0000-00-00', 'N', 0),
(308, 19, 20, '1989-06-24', '0000-00-00', 'N', 0),
(309, 19, 21, '1989-07-24', '0000-00-00', 'N', 0),
(310, 19, 22, '1989-11-21', '0000-00-00', 'N', 0),
(311, 19, 23, '1989-12-21', '0000-00-00', 'N', 0),
(312, 19, 24, '1990-12-21', '0000-00-00', 'N', 0),
(313, 19, 25, '1991-12-21', '0000-00-00', 'N', 0),
(314, 19, 26, '1992-12-20', '0000-00-00', 'N', 0),
(315, 19, 27, '1993-12-20', '0000-00-00', 'N', 0),
(316, 19, 28, '1989-05-25', '0000-00-00', 'N', 0),
(317, 19, 29, '1989-06-24', '0000-00-00', 'N', 0),
(318, 19, 30, '1989-07-24', '0000-00-00', 'N', 0),
(319, 19, 31, '1990-07-24', '0000-00-00', 'N', 0),
(320, 19, 32, '1993-01-21', '0000-00-00', 'N', 0),
(321, 19, 33, '1989-11-21', '0000-00-00', 'Y', 23),
(322, 19, 34, '1991-03-26', '0000-00-00', 'N', 0),
(323, 19, 35, '1994-03-25', '0000-00-00', 'N', 0),
(324, 19, 36, '1997-03-24', '0000-00-00', 'N', 0),
(325, 19, 37, '1990-05-05', '0000-00-00', 'Y', 24),
(326, 19, 38, '1993-10-13', '0000-00-00', 'N', 0),
(327, 19, 39, '1989-05-25', '0000-00-00', 'N', 0),
(328, 19, 40, '1989-06-24', '0000-00-00', 'N', 0),
(329, 19, 41, '1989-07-24', '0000-00-00', 'N', 0),
(330, 19, 42, '1990-07-24', '0000-00-00', 'N', 0),
(331, 19, 43, '1989-05-07', '0000-00-00', 'N', 0),
(332, 19, 44, '1989-06-06', '0000-00-00', 'N', 0),
(333, 19, 45, '1998-10-12', '1999-05-03', 'Y', 49),
(334, 19, 46, '1991-03-26', '1997-10-01', 'Y', 0),
(335, 19, 47, '1994-03-25', '2000-12-30', 'Y', 0),
(336, 19, 48, '1997-03-24', '0000-00-00', 'N', 0),
(337, 19, 49, '2000-03-23', '0000-00-00', 'N', 0),
(338, 19, 50, '2003-03-23', '0000-00-00', 'N', 0),
(339, 19, 51, '2006-03-22', '0000-00-00', 'N', 0),
(340, 19, 52, '2008-10-09', '0000-00-00', 'N', 0),
(341, 20, 1, '1991-05-10', '0000-00-00', 'Y', 0),
(342, 20, 6, '1992-05-09', '2002-02-09', 'Y', 39),
(343, 20, 7, '1995-04-09', '0000-00-00', 'N', 0),
(344, 20, 8, '1991-06-09', '0000-00-00', 'Y', 8),
(345, 20, 9, '1991-07-09', '0000-00-00', 'Y', 8),
(346, 20, 10, '1991-08-08', '0000-00-00', 'Y', 8),
(347, 20, 11, '1992-08-07', '0000-00-00', 'Y', 8),
(348, 20, 12, '1995-10-28', '1997-09-01', 'Y', 8),
(349, 20, 13, '1992-04-14', '0000-00-00', 'N', 0),
(350, 20, 14, '1992-10-11', '0000-00-00', 'N', 0),
(351, 20, 15, '1991-04-10', '1997-09-02', 'Y', 26),
(352, 20, 16, '1991-05-10', '1997-10-01', 'Y', 26),
(353, 20, 17, '1991-10-07', '1998-03-02', 'Y', 26),
(354, 20, 18, '1992-08-07', '0000-00-00', 'N', 0),
(355, 20, 19, '1991-06-09', '0000-00-00', 'N', 0),
(356, 20, 20, '1991-07-09', '0000-00-00', 'N', 0),
(357, 20, 21, '1991-08-08', '0000-00-00', 'N', 0),
(358, 20, 22, '1991-12-06', '0000-00-00', 'N', 0),
(359, 20, 23, '1992-01-05', '0000-00-00', 'N', 0),
(360, 20, 24, '1993-01-04', '0000-00-00', 'N', 0),
(361, 20, 25, '1994-01-04', '0000-00-00', 'N', 0),
(362, 20, 26, '1995-01-04', '0000-00-00', 'N', 0),
(363, 20, 27, '1996-01-04', '0000-00-00', 'N', 0),
(364, 20, 28, '1991-06-09', '0000-00-00', 'N', 0),
(365, 20, 29, '1991-07-09', '0000-00-00', 'N', 0),
(366, 20, 30, '1991-08-08', '0000-00-00', 'N', 0),
(367, 20, 31, '1992-08-07', '0000-00-00', 'N', 0),
(368, 20, 32, '1995-02-05', '0000-00-00', 'N', 0),
(369, 20, 33, '1991-12-06', '0000-00-00', 'Y', 23),
(370, 20, 34, '1993-04-09', '0000-00-00', 'N', 0),
(371, 20, 35, '1996-04-08', '0000-00-00', 'N', 0),
(372, 20, 36, '1999-04-08', '0000-00-00', 'N', 0),
(373, 20, 37, '1992-05-19', '0000-00-00', 'Y', 0),
(374, 20, 38, '1995-10-28', '0000-00-00', 'N', 0),
(375, 20, 39, '1991-06-09', '0000-00-00', 'N', 0),
(376, 20, 40, '1991-07-09', '0000-00-00', 'N', 0),
(377, 20, 41, '1991-08-08', '0000-00-00', 'N', 0),
(378, 20, 42, '1992-08-07', '0000-00-00', 'N', 0),
(379, 20, 43, '1991-05-22', '0000-00-00', 'N', 0),
(380, 20, 44, '1991-06-21', '0000-00-00', 'N', 0),
(381, 20, 45, '2000-10-26', '2002-02-02', 'Y', 49),
(382, 20, 46, '1993-04-09', '1997-11-03', 'Y', 0),
(383, 20, 47, '1996-04-08', '2000-12-30', 'Y', 0),
(384, 20, 48, '1999-04-08', '0000-00-00', 'N', 0),
(385, 20, 49, '2002-04-07', '0000-00-00', 'N', 0),
(386, 20, 50, '2005-04-06', '0000-00-00', 'N', 0),
(387, 20, 51, '2008-04-05', '0000-00-00', 'N', 0),
(388, 20, 52, '2010-10-24', '0000-00-00', 'N', 0),
(389, 21, 1, '1990-07-27', NULL, 'Y', 0),
(390, 21, 34, '1992-06-26', NULL, 'N', 0),
(391, 21, 45, '2000-07-24', NULL, 'N', 0),
(392, 21, 19, '1990-08-26', NULL, 'N', 0),
(393, 21, 33, '1991-03-04', NULL, 'N', 0),
(394, 21, 22, '1991-02-02', NULL, 'N', 0),
(395, 21, 43, '1990-08-26', NULL, 'N', 0),
(396, 21, 46, '1992-07-03', NULL, 'N', 0),
(397, 21, 39, '1990-08-26', NULL, 'N', 0),
(398, 21, 38, '1995-01-14', NULL, 'N', 0),
(399, 21, 37, '1991-08-06', NULL, 'N', 0),
(400, 21, 28, '1990-08-26', NULL, 'N', 0),
(401, 21, 15, '1990-06-27', '1997-10-07', 'Y', 26),
(402, 21, 6, '1991-09-25', NULL, 'N', 0),
(403, 21, 13, '1991-07-04', NULL, 'N', 0),
(404, 21, 7, '1994-06-26', NULL, 'N', 0),
(405, 21, 8, '1990-08-26', NULL, 'Y', 8),
(406, 21, 9, '1990-09-25', NULL, 'Y', 0),
(407, 21, 10, '1990-10-25', NULL, 'Y', 0),
(408, 21, 11, '1991-10-25', NULL, 'Y', 0),
(409, 21, 12, '1994-10-09', '1997-10-07', 'Y', 0),
(410, 21, 14, '1992-02-09', NULL, 'N', 0),
(411, 21, 16, '1990-07-27', '1997-11-11', 'Y', 26),
(412, 21, 17, '1990-12-24', '1998-04-13', 'Y', 26),
(413, 21, 20, '1990-09-25', NULL, 'N', 0),
(414, 21, 21, '1990-10-25', NULL, 'N', 0),
(415, 21, 18, '1991-10-25', NULL, 'N', 0),
(416, 21, 23, '1991-03-04', NULL, 'N', 0),
(417, 21, 24, '1992-03-03', NULL, 'N', 0),
(418, 21, 25, '1993-03-03', NULL, 'N', 0),
(419, 21, 26, '1994-03-03', NULL, 'N', 0),
(420, 21, 27, '1995-03-03', NULL, 'N', 0),
(421, 21, 29, '1990-09-25', NULL, 'N', 0),
(422, 21, 30, '1990-10-25', NULL, 'N', 0),
(423, 21, 31, '1991-10-25', NULL, 'N', 0),
(424, 21, 32, '1994-04-24', NULL, 'N', 0),
(425, 21, 35, '1995-06-26', NULL, 'N', 0),
(426, 21, 36, '1998-06-25', NULL, 'N', 0),
(427, 21, 40, '1990-09-25', NULL, 'N', 0),
(428, 21, 41, '1990-10-25', NULL, 'N', 0),
(429, 21, 42, '1991-10-25', NULL, 'N', 0),
(430, 21, 44, '1990-09-25', NULL, 'N', 0),
(431, 21, 52, '2010-07-22', NULL, 'N', 0),
(432, 21, 47, '1995-07-03', '1997-10-07', 'Y', 33),
(433, 21, 48, '1998-07-02', NULL, 'N', 0),
(434, 21, 49, '2001-07-01', NULL, 'N', 0),
(435, 21, 50, '2004-06-30', NULL, 'N', 0),
(436, 21, 51, '2007-06-30', NULL, 'N', 0),
(437, 22, 1, '1985-06-19', NULL, 'N', 0),
(438, 22, 34, '1987-05-20', NULL, 'N', 0),
(439, 22, 45, '1995-06-17', '1997-11-08', 'Y', 49),
(440, 22, 19, '1985-07-19', NULL, 'N', 0),
(441, 22, 33, '1986-01-25', NULL, 'N', 0),
(442, 22, 22, '1985-12-26', NULL, 'N', 0),
(443, 22, 43, '1985-07-19', NULL, 'N', 0),
(444, 22, 46, '1987-05-27', NULL, 'N', 0),
(445, 22, 39, '1985-07-19', NULL, 'N', 0),
(446, 22, 38, '1989-12-07', NULL, 'N', 0),
(447, 22, 37, '1986-06-29', NULL, 'N', 0),
(448, 22, 28, '1985-07-19', NULL, 'N', 0),
(449, 22, 15, '1985-05-20', '1997-11-08', 'Y', 27),
(450, 22, 6, '1986-08-18', NULL, 'N', 0),
(451, 22, 13, '1986-05-27', NULL, 'N', 0),
(452, 22, 3, '1994-05-18', NULL, 'N', 0),
(453, 22, 7, '1989-05-19', NULL, 'N', 0),
(454, 22, 8, '1985-07-19', NULL, 'N', 0),
(455, 22, 4, '1994-06-17', NULL, 'N', 0),
(456, 22, 5, '1994-11-14', NULL, 'N', 0),
(457, 22, 9, '1985-08-18', NULL, 'N', 0),
(458, 22, 10, '1985-09-17', NULL, 'N', 0),
(459, 22, 11, '1986-09-17', NULL, 'N', 0),
(460, 22, 12, '1989-09-01', NULL, 'N', 0),
(461, 22, 14, '1987-01-02', NULL, 'N', 0),
(462, 22, 16, '1985-06-19', '1997-12-13', 'Y', 27),
(463, 22, 17, '1985-11-16', '1998-05-16', 'Y', 27),
(464, 22, 20, '1985-08-18', NULL, 'N', 0),
(465, 22, 21, '1985-09-17', NULL, 'N', 0),
(466, 22, 18, '1986-09-17', NULL, 'N', 0),
(467, 22, 23, '1986-01-25', NULL, 'N', 0),
(468, 22, 24, '1987-01-25', NULL, 'N', 0),
(469, 22, 25, '1988-01-25', NULL, 'N', 0),
(470, 22, 26, '1989-01-24', NULL, 'N', 0),
(471, 22, 27, '1990-01-24', NULL, 'N', 0),
(472, 22, 29, '1985-08-18', NULL, 'N', 0),
(473, 22, 30, '1985-09-17', NULL, 'N', 0),
(474, 22, 31, '1986-09-17', NULL, 'N', 0),
(475, 22, 32, '1989-03-17', NULL, 'N', 0),
(476, 22, 35, '1990-05-19', NULL, 'N', 0),
(477, 22, 36, '1993-05-18', NULL, 'N', 0),
(478, 22, 40, '1985-08-18', NULL, 'N', 0),
(479, 22, 41, '1985-09-17', NULL, 'N', 0),
(480, 22, 42, '1986-09-17', NULL, 'N', 0),
(481, 22, 44, '1985-08-18', NULL, 'N', 0),
(482, 22, 52, '2005-06-14', NULL, 'N', 0),
(483, 22, 47, '1990-05-26', NULL, 'N', 0),
(484, 22, 48, '1993-05-25', NULL, 'N', 0),
(485, 22, 49, '1996-05-24', NULL, 'N', 0),
(486, 22, 50, '1999-05-24', NULL, 'N', 0),
(487, 22, 51, '2002-05-23', NULL, 'N', 0),
(488, 23, 1, '1992-07-20', NULL, 'Y', 0),
(489, 23, 34, '1994-06-20', NULL, 'N', 0),
(490, 23, 45, '2002-07-18', NULL, 'N', 0),
(491, 23, 19, '1992-08-19', NULL, 'N', 0),
(492, 23, 33, '1993-02-25', NULL, 'Y', 0),
(493, 23, 22, '1993-01-26', NULL, 'N', 0),
(494, 23, 43, '1992-08-19', NULL, 'N', 0),
(495, 23, 46, '1994-06-27', NULL, 'Y', 0),
(496, 23, 39, '1992-08-19', NULL, 'N', 0),
(497, 23, 38, '1997-01-07', '1998-11-23', 'Y', 0),
(498, 23, 37, '1993-07-30', NULL, 'Y', 0),
(499, 23, 28, '1992-08-19', NULL, 'N', 0),
(500, 23, 15, '1992-06-20', '1996-07-26', 'Y', 26),
(501, 23, 6, '1993-09-18', NULL, 'N', 0),
(502, 23, 13, '1993-06-27', NULL, 'N', 0),
(503, 23, 7, '1996-06-19', NULL, 'N', 0),
(504, 23, 8, '1992-08-19', NULL, 'Y', 0),
(505, 23, 9, '1992-09-18', NULL, 'Y', 0),
(506, 23, 10, '1992-10-18', NULL, 'Y', 0),
(507, 23, 11, '1993-10-18', '1997-11-15', 'Y', 8),
(508, 23, 12, '1996-10-02', '1997-11-15', 'Y', 0),
(509, 23, 14, '1994-02-02', NULL, 'N', 0),
(510, 23, 16, '1992-07-20', '1996-08-29', 'Y', 26),
(511, 23, 17, '1992-12-17', '1997-05-10', 'Y', 26),
(512, 23, 20, '1992-09-18', NULL, 'N', 0),
(513, 23, 21, '1992-10-18', NULL, 'N', 0),
(514, 23, 18, '1993-10-18', NULL, 'N', 0),
(515, 23, 23, '1993-02-25', NULL, 'N', 0),
(516, 23, 24, '1994-02-25', NULL, 'N', 0),
(517, 23, 25, '1995-02-25', NULL, 'N', 0),
(518, 23, 26, '1996-02-25', NULL, 'N', 0),
(519, 23, 27, '1997-02-24', NULL, 'N', 0),
(520, 23, 29, '1992-09-18', NULL, 'N', 0),
(521, 23, 30, '1992-10-18', NULL, 'N', 0),
(522, 23, 31, '1993-10-18', NULL, 'N', 0),
(523, 23, 32, '1996-04-17', NULL, 'N', 0),
(524, 23, 35, '1997-06-19', NULL, 'N', 0),
(525, 23, 36, '2000-06-18', NULL, 'N', 0),
(526, 23, 40, '1992-09-18', NULL, 'N', 0),
(527, 23, 41, '1992-10-18', NULL, 'N', 0),
(528, 23, 42, '1993-10-18', NULL, 'N', 0),
(529, 23, 44, '1992-09-18', NULL, 'N', 0),
(530, 23, 52, '2012-07-15', NULL, 'N', 0),
(531, 23, 47, '1997-06-26', '1997-11-15', 'Y', 0),
(532, 23, 48, '2000-06-25', '2000-11-17', 'Y', 0),
(533, 23, 49, '2003-06-25', NULL, 'N', 0),
(534, 23, 50, '2006-06-24', NULL, 'N', 0),
(535, 23, 51, '2009-06-23', NULL, 'N', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
