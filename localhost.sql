-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 12, 2012 at 05:13 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `drmahima_com`
--
CREATE DATABASE `drmahima_com` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `drmahima_com`;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE IF NOT EXISTS `doctors` (
  `username` varchar(255) NOT NULL,
  `name` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`username`, `name`, `password`) VALUES
('mahima', 'Mahima Anurag', '227dd828170f456f4fb2ac146846470b'),
('demo', 'Demo User', '5f4dcc3b5aa765d61d8327deb882cf99');

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
  `last_name` varchar(25) DEFAULT NULL,
  `father_name` varchar(25) DEFAULT NULL,
  `father_occ` varchar(25) DEFAULT NULL,
  `mother_name` varchar(25) DEFAULT NULL,
  `mother_occ` varchar(25) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `sibling` varchar(20) NOT NULL DEFAULT '0',
  `pin` varchar(6) DEFAULT NULL,
  `res_phone` varchar(10) DEFAULT NULL,
  `office_phone` varchar(10) DEFAULT NULL,
  `dob` date NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `phone2` varchar(15) DEFAULT NULL,
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
  `head_circum` varchar(10) DEFAULT NULL,
  `length` varchar(10) DEFAULT NULL,
  `gestation` enum('FT','PT','LPT') NOT NULL DEFAULT 'FT',
  `date_of_registration` date NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `obstetrician` varchar(25) DEFAULT NULL,
  `place_of_birth` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1104 ;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `id_dob`, `name`, `first_name`, `middle_name`, `last_name`, `father_name`, `father_occ`, `mother_name`, `mother_occ`, `address`, `sibling`, `pin`, `res_phone`, `office_phone`, `dob`, `email`, `phone`, `phone2`, `sex`, `born_at`, `mode_of_delivery`, `birth_weight`, `comp_preg`, `comp_deli`, `cry_birth`, `admit_hospital`, `injury_child`, `prev_surgery`, `allergy_child`, `other_info`, `diabetes`, `asthma`, `cardiac`, `hypertension`, `tuberculosis`, `other_fam_history`, `breast_feed_month`, `solid_month`, `head_circum`, `length`, `gestation`, `date_of_registration`, `active`, `obstetrician`, `place_of_birth`) VALUES
(19, '', 'Richa Vats', 'Richa', NULL, 'Vats', 'Rakesh Vats', '', '', '', 'WZ-28, Naraina Village,New Delhi', '20', NULL, NULL, NULL, '1989-03-26', '', '09968262526', '', 'F', '', 'Normal', '', 'No', 'No', 'Yes', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', NULL, NULL, '', '', 'FT', '1989-03-26', 1, '', ''),
(20, '', 'Gaurav Vats', 'Gaurav', NULL, 'Vats', 'Rakesh Vats', '', '', '', 'WZ-28, Naraina Village,New Delhi', '19', NULL, NULL, NULL, '1991-04-10', '', '09968262526', '', 'M', '', 'Normal', '', 'No', 'No', 'Yes', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', NULL, NULL, '', '', 'FT', '1991-04-10', 1, '', ''),
(69, '', 'APOORV UMANG SAXENA', 'APOORV UMANG', NULL, 'SAXENA', 'Anurag Saxena', 'Doctor', 'Mahima Anurag', 'Doctor', 'B-40 A / 2, Naraina Vihar, New Delhi, 110028', '70', NULL, NULL, NULL, '1993-01-14', 'apoorvumang@gmail.com', '09811129950', '07891947877', 'M', '11:49 AM', 'Caesarean', '3000', 'No', 'No', 'Yes', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', NULL, NULL, '', '', 'FT', '1993-01-14', 1, '', ''),
(70, '', 'ARPIT TARANG SAXENA', 'ARPIT TARANG', NULL, 'SAXENA', 'ANURAG SAXENA', 'Doctor', 'Mahima Anurag', 'Doctor', 'B - 40 A / 2, Naraina Vihar, New Delhi,110028', '69', NULL, NULL, NULL, '1993-01-14', 'arpit.tarang@gmail.com', '09717585207', '07407650530', 'M', '11:50 AM', 'Caesarean', '2500', 'No', 'No', 'Yes', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', NULL, NULL, '', '', 'FT', '1993-01-14', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vac_make`
--

CREATE TABLE IF NOT EXISTS `vac_make` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=67 ;

--
-- Dumping data for table `vac_make`
--

INSERT INTO `vac_make` (`id`, `name`) VALUES
(5, 'TUBERVAC'),
(6, 'INFANRIX'),
(7, 'TRIPACEL'),
(8, 'Triple Antigen (DPT)'),
(9, 'HIBRIX'),
(10, 'HIBPRO'),
(11, 'ACT- HIB'),
(12, 'IMOVAX'),
(13, 'POLPROTEC'),
(14, 'ROTARIX'),
(15, 'ROTATEQ'),
(16, 'EASY-4'),
(17, 'QUADRAVAX'),
(18, 'Si -QVAC'),
(19, 'COMBIVAC - PFS'),
(20, 'PENTAVAC'),
(21, 'PREVENAR-13'),
(22, 'SYNFLORIX'),
(23, 'M-VAC'),
(24, 'TRESIVAC'),
(25, 'PRIORIX'),
(26, 'ENGIREX-Ped'),
(27, 'ENGIREX-Adult'),
(28, 'SHANVAC-B Ped'),
(29, 'SHANVAC -B Adult'),
(30, 'Si Tdvac'),
(31, 'BOOSTRIX'),
(32, 'ADACEL'),
(33, 'TYPHIM Vi'),
(34, 'TYPBAR'),
(35, 'HAVRIX-720'),
(36, 'HAVRIX-1440'),
(37, 'AXAXIM (PED)'),
(38, 'OKAVAX'),
(39, 'VARILRIX'),
(40, 'VARIVAX'),
(41, 'VAXIGRIP Ped'),
(42, 'VAXIGRIP Adult'),
(43, 'FLURIX'),
(44, 'CERVERIX'),
(45, 'GARDASIL'),
(46, 'SHANCHOL'),
(47, 'MENINGO A + C'),
(48, 'MENCEVAX ( ACWY)'),
(49, 'TT'),
(50, 'TWINRIX Adult'),
(51, 'AVAXIM 160'),
(52, 'HAVRIX  360'),
(53, 'EASY 5'),
(54, 'TYPBAR PFS'),
(55, 'TYPHRIX'),
(56, 'DT'),
(57, 'PHC'),
(59, 'HAVPUR'),
(60, 'TRITANRIX'),
(61, 'Q VAC'),
(62, 'PENTAXIM'),
(63, 'PREVENAR'),
(64, 'Tetra Act Hib'),
(65, 'Shan 4'),
(66, 'Td');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=66233 ;

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
(303, 19, 15, '1989-03-26', '1997-09-01', 'Y', 26),
(304, 19, 16, '1989-04-25', '1997-10-01', 'Y', 26),
(305, 19, 17, '1989-09-22', '1998-03-02', 'Y', 26),
(310, 19, 22, '1989-11-21', '0000-00-00', 'N', 0),
(312, 19, 24, '1990-12-21', '0000-00-00', 'N', 0),
(313, 19, 25, '1991-12-21', '0000-00-00', 'N', 0),
(314, 19, 26, '1992-12-20', '0000-00-00', 'N', 0),
(315, 19, 27, '1993-12-20', '0000-00-00', 'N', 0),
(321, 19, 33, '1989-11-21', '0000-00-00', 'Y', 23),
(322, 19, 34, '1991-03-26', '0000-00-00', 'N', 0),
(323, 19, 35, '1994-03-25', '0000-00-00', 'N', 0),
(324, 19, 36, '1997-03-24', '0000-00-00', 'N', 0),
(325, 19, 37, '1990-05-05', '0000-00-00', 'Y', 24),
(326, 19, 38, '1993-10-13', '0000-00-00', 'N', 0),
(333, 19, 45, '1998-10-12', '1999-05-03', 'Y', 49),
(334, 19, 46, '1991-03-26', '1997-10-01', 'Y', 0),
(335, 19, 47, '1994-03-25', '2000-12-30', 'Y', 0),
(336, 19, 48, '1997-03-24', '0000-00-00', 'N', 0),
(337, 19, 49, '2000-03-23', '0000-00-00', 'N', 0),
(338, 19, 50, '2003-03-23', '0000-00-00', 'N', 0),
(339, 19, 51, '2006-03-22', '0000-00-00', 'N', 0),
(340, 19, 52, '2009-04-30', '0000-00-00', 'N', 0),
(341, 20, 1, '1991-05-10', '0000-00-00', 'Y', 0),
(342, 20, 6, '1992-05-09', '2002-02-09', 'Y', 39),
(343, 20, 7, '1995-04-09', '0000-00-00', 'N', 0),
(344, 20, 8, '1991-06-09', '0000-00-00', 'Y', 8),
(345, 20, 9, '1991-07-09', '0000-00-00', 'Y', 8),
(346, 20, 10, '1991-08-08', '0000-00-00', 'Y', 8),
(347, 20, 11, '1992-08-07', '0000-00-00', 'Y', 8),
(348, 20, 12, '1995-10-28', '1997-09-01', 'Y', 8),
(349, 20, 13, '1992-04-14', '0000-00-00', 'N', 0),
(350, 20, 14, '1992-08-21', '0000-00-00', 'N', 0),
(351, 20, 15, '1991-04-10', '1997-09-02', 'Y', 26),
(352, 20, 16, '1991-05-10', '1997-10-01', 'Y', 26),
(353, 20, 17, '1991-10-07', '1998-03-02', 'Y', 26),
(358, 20, 22, '1991-12-06', '0000-00-00', 'N', 0),
(360, 20, 24, '1993-01-04', '0000-00-00', 'N', 0),
(361, 20, 25, '1994-01-04', '0000-00-00', 'N', 0),
(362, 20, 26, '1995-01-04', '0000-00-00', 'N', 0),
(363, 20, 27, '1996-01-04', '0000-00-00', 'N', 0),
(369, 20, 33, '1991-12-06', '0000-00-00', 'Y', 23),
(370, 20, 34, '1993-04-09', '0000-00-00', 'N', 0),
(371, 20, 35, '1996-04-08', '0000-00-00', 'N', 0),
(372, 20, 36, '1999-04-08', '0000-00-00', 'N', 0),
(373, 20, 37, '1992-05-19', '0000-00-00', 'Y', 0),
(374, 20, 38, '1995-10-28', '0000-00-00', 'N', 0),
(381, 20, 45, '2000-10-26', '2002-02-02', 'Y', 49),
(382, 20, 46, '1993-04-09', '1997-11-03', 'Y', 0),
(383, 20, 47, '1996-04-08', '2000-12-30', 'Y', 0),
(384, 20, 48, '1999-04-08', '0000-00-00', 'N', 0),
(385, 20, 49, '2002-04-07', '0000-00-00', 'N', 0),
(386, 20, 50, '2005-04-06', '0000-00-00', 'N', 0),
(387, 20, 51, '2008-04-05', '0000-00-00', 'N', 0),
(388, 20, 52, '2010-10-24', '0000-00-00', 'N', 0),
(1712, 19, 53, '1990-10-31', NULL, 'N', 0),
(1713, 20, 53, '0000-00-00', NULL, 'N', 0),
(2639, 19, 55, '2018-10-07', NULL, 'N', 0),
(2640, 20, 55, '2020-10-21', NULL, 'N', 0),
(64875, 69, 36, '2008-04-12', '2011-06-24', 'Y', 48),
(64874, 69, 35, '2000-06-23', '2005-04-13', 'Y', 47),
(64873, 69, 31, '1994-05-14', NULL, 'N', 0),
(64872, 69, 30, '1993-05-14', NULL, 'N', 0),
(64871, 69, 29, '1993-04-14', NULL, 'N', 0),
(64870, 69, 65, '2013-10-23', NULL, 'N', 0),
(64869, 69, 27, '2012-10-05', '2012-10-23', 'Y', 42),
(64868, 69, 26, '2011-10-24', '2011-10-06', 'Y', 43),
(64867, 69, 25, '2010-11-08', '2010-10-24', 'Y', 0),
(64866, 69, 24, '2009-12-18', '2009-11-08', 'Y', 0),
(64865, 69, 23, '2008-12-18', NULL, 'N', 0),
(64864, 69, 18, '1994-05-14', NULL, 'N', 0),
(64863, 69, 21, '1993-05-14', NULL, 'N', 0),
(64862, 69, 20, '1993-04-14', NULL, 'N', 0),
(64861, 69, 17, '1996-09-02', '1996-09-06', 'Y', 26),
(64860, 69, 16, '1996-04-01', '1996-04-01', 'Y', 26),
(64859, 69, 14, '1998-04-11', '1998-04-14', 'Y', 52),
(64858, 69, 53, '1998-10-19', '1998-09-14', 'Y', 52),
(64857, 69, 12, '1997-08-04', '1998-01-18', 'Y', 56),
(64856, 69, 11, '1994-07-11', '1994-08-20', 'Y', 0),
(64855, 69, 10, '1993-06-14', '1993-07-11', 'Y', 8),
(64854, 69, 9, '1993-04-26', '1993-05-14', 'Y', 8),
(64853, 69, 6, '1994-04-14', '2011-06-24', 'Y', 38),
(64852, 69, 7, '1997-01-13', '2011-08-14', 'Y', 38),
(64851, 69, 13, '1994-01-21', '1998-03-12', 'Y', 52),
(64850, 69, 8, '1993-03-15', '1993-03-27', 'Y', 8),
(64849, 69, 37, '1994-04-14', '1994-02-10', 'Y', 0),
(64848, 69, 15, '1993-01-14', '1996-03-01', 'Y', 26),
(64847, 69, 19, '1993-03-15', NULL, 'N', 0),
(64846, 69, 38, '1997-08-04', '2005-04-12', 'Y', 25),
(64845, 69, 34, '1995-01-14', '1997-06-24', 'Y', 47),
(64844, 69, 33, '1993-09-21', '1993-12-02', 'Y', 0),
(64843, 69, 43, '1993-03-15', NULL, 'N', 0),
(64842, 69, 45, '2003-02-11', '2003-03-23', 'Y', 49),
(64841, 69, 46, '1995-01-21', '1995-05-12', 'Y', 0),
(2996, 70, 1, '1993-02-13', '1993-02-20', 'Y', 0),
(2997, 70, 38, '1997-08-03', '2005-04-12', 'Y', 0),
(2998, 70, 22, '1993-08-22', '2008-11-18', 'Y', 0),
(3000, 70, 54, '1993-01-14', '1993-02-20', 'Y', 0),
(3001, 70, 46, '1995-01-21', '1995-05-12', 'Y', 0),
(3002, 70, 45, '2003-02-11', '2003-03-23', 'Y', 49),
(3003, 70, 33, '1993-09-21', '1993-12-02', 'Y', 0),
(3004, 70, 34, '1995-01-14', '1997-06-24', 'Y', 0),
(3007, 70, 37, '1994-02-23', '1994-02-10', 'Y', 0),
(3009, 70, 15, '1993-01-14', '1996-03-01', 'Y', 0),
(3010, 70, 8, '1993-03-15', '1993-03-27', 'Y', 0),
(3011, 70, 7, '1997-01-13', '2011-09-29', 'Y', 0),
(3012, 70, 6, '1994-04-14', '2011-11-24', 'Y', 0),
(3013, 70, 13, '1994-01-21', '1998-03-12', 'Y', 0),
(3014, 70, 9, '1993-04-26', '1993-05-14', 'Y', 0),
(3015, 70, 10, '1993-06-13', '1993-07-11', 'Y', 0),
(3016, 70, 11, '1994-07-11', '1994-08-20', 'Y', 0),
(3017, 70, 12, '1997-08-04', '1998-01-18', 'Y', 0),
(3018, 70, 53, '1994-08-29', '1998-09-14', 'Y', 0),
(3019, 70, 14, '1998-04-11', '1998-04-14', 'Y', 0),
(3020, 70, 16, '1996-03-31', '1996-04-01', 'Y', 0),
(3021, 70, 17, '1996-08-29', '1996-09-06', 'Y', 0),
(3026, 70, 24, '2009-12-18', '2009-11-08', 'Y', 0),
(3027, 70, 25, '2010-11-08', '2010-10-24', 'Y', 43),
(3028, 70, 26, '2011-10-24', '2011-09-29', 'Y', 43),
(3029, 70, 27, '2012-09-28', '2012-10-23', 'Y', 42),
(3034, 70, 35, '2000-06-23', '2005-04-13', 'Y', 0),
(3035, 70, 36, '2008-04-12', '2011-06-24', 'Y', 48),
(3040, 70, 52, '2013-03-20', '2008-03-28', 'Y', 31),
(3041, 70, 47, '1998-05-11', '1998-05-17', 'Y', 0),
(3042, 70, 48, '2001-05-16', '2001-05-11', 'Y', 0),
(3043, 70, 49, '2004-05-10', '2005-05-28', 'Y', 0),
(3044, 70, 50, '2008-05-27', '2008-05-17', 'Y', 0),
(3045, 70, 51, '2011-05-17', '2011-06-24', 'Y', 33),
(3046, 70, 55, '2018-03-26', NULL, 'N', 0),
(4364, 19, 56, '2028-10-04', NULL, 'N', 0),
(4365, 20, 56, '2030-10-19', NULL, 'N', 0),
(64840, 69, 54, '1993-01-14', '1993-02-20', 'Y', 0),
(4415, 70, 56, '2028-03-23', NULL, 'N', 0),
(12388, 19, 59, '1991-04-10', NULL, 'N', 0),
(12389, 20, 59, '1993-04-24', NULL, 'N', 0),
(12650, 70, 60, '2011-07-09', '2011-07-09', 'Y', 46),
(64839, 69, 28, '1993-03-15', NULL, 'N', 0),
(12600, 20, 60, '1993-05-09', NULL, 'N', 0),
(12599, 19, 60, '1991-04-25', NULL, 'N', 0),
(12439, 70, 59, '1995-01-29', '2011-06-24', 'Y', 46),
(64838, 69, 59, '1995-01-30', '2011-06-24', 'Y', 46),
(54674, 70, 64, '2014-05-16', NULL, 'N', 0),
(64837, 69, 39, '1993-03-15', NULL, 'N', 0),
(54624, 20, 64, '2011-04-05', NULL, 'N', 0),
(54623, 19, 64, '2009-03-21', NULL, 'N', 0),
(53641, 19, 63, '1970-01-31', NULL, 'N', 0),
(53642, 20, 63, '1970-01-31', NULL, 'N', 0),
(55547, 19, 65, '1994-12-20', NULL, 'N', 0),
(55548, 20, 65, '1997-01-03', NULL, 'N', 0),
(64836, 69, 22, '1993-08-23', '2008-11-18', 'Y', 0),
(55598, 70, 65, '2013-10-23', NULL, 'N', 0),
(56471, 19, 66, '1995-12-20', NULL, 'N', 0),
(56472, 20, 66, '1998-01-03', NULL, 'N', 0),
(64835, 69, 1, '1993-02-13', '1993-02-20', 'Y', 0),
(56522, 70, 66, '2014-10-23', NULL, 'N', 0),
(64878, 69, 42, '1994-05-14', NULL, 'N', 0),
(64877, 69, 41, '1993-05-14', NULL, 'N', 0),
(64876, 69, 40, '1993-04-14', NULL, 'N', 0),
(64891, 69, 66, '2014-10-23', NULL, 'N', 0),
(64890, 69, 60, '2011-07-09', '2011-07-09', 'Y', 46),
(64889, 69, 56, '2028-03-23', NULL, 'N', 0),
(64888, 69, 55, '2018-03-26', NULL, 'N', 0),
(64887, 69, 64, '2014-06-23', NULL, 'N', 0),
(64886, 69, 51, '2011-05-17', '2011-06-24', 'Y', 33),
(64885, 69, 50, '2008-05-27', '2008-05-17', 'Y', 0),
(64884, 69, 49, '2004-05-10', '2005-05-28', 'Y', 0),
(64883, 69, 48, '2001-05-16', '2001-05-11', 'Y', 0),
(64882, 69, 47, '1998-05-11', '1998-05-17', 'Y', 0),
(64881, 69, 52, '2013-03-20', '2008-03-28', 'Y', 31),
(64880, 69, 63, '1993-05-14', NULL, 'N', 0),
(64879, 69, 44, '1993-04-14', NULL, 'N', 0);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=67 ;

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
(10, 'DTwP/ DTaP & Oral Polio - 3', 30, 9, 'B', 98, 730),
(11, 'DTwP/ DTaP & Oral Polio (BOOSTER 1)', 365, 10, 'B', 456, 730),
(12, 'DTwP/ DTaP & Oral Polio (BOOSTER 2)', 1080, 11, 'B', 1460, 2190),
(13, 'HEPATITIS-A 1', 372, 0, 'B', 365, 99999),
(14, 'HEPATITIS-A 2 (Optional)', 30, 13, 'B', 395, 99999),
(15, 'HEPATITIS-B - 1', 0, 0, 'B', 0, 99999),
(16, 'HEPATITIS-B - 2', 30, 15, 'B', 30, 999999),
(17, 'HEPATITIS-B - 3', 153, 16, 'B', 180, 99999),
(18, 'HIB BOOSTER', 365, 21, 'B', 456, 1825),
(19, 'HIB I', 60, 0, 'B', 45, 182),
(20, 'HIB II', 30, 19, 'B', 75, 366),
(21, 'HIB III', 30, 20, 'B', 98, 455),
(22, 'INFLUENZA I DOSE', 220, 0, 'B', 180, 99999),
(23, 'INFLUENZA II DOSE', 30, 22, 'B', 240, 2920),
(24, 'INFLUENZA BOOSTER 1', 365, 23, 'B', 547, 99999),
(25, 'INFLUENZA BOOSTER 2	', 365, 24, 'B', 912, 99999),
(26, 'INFLUENZA BOOSTER 3', 365, 25, 'B', 1277, 99999),
(27, 'INFLUENZA BOOSTER 4', 365, 26, 'B', 1642, 99999),
(28, 'IPV-1', 60, 0, 'B', 45, 1825),
(29, 'IPV-2', 30, 28, 'B', 75, 1825),
(30, 'IPV-3', 30, 29, 'B', 98, 1825),
(31, 'IPV BOOSTER 1', 365, 30, 'B', 547, 1825),
(64, 'TYPHOID BOOSTER 6', 1095, 51, 'B', 730, 99999),
(33, 'MEASLES', 250, 0, 'B', 240, 365),
(34, 'MENIINGOCO MENINGITIS I', 730, 0, 'B', 730, 99999),
(35, 'MENINGOCO MENINGITIS II', 1095, 34, 'B', 730, 99999),
(36, 'MENINGOCO MENINGITIS III', 1095, 35, 'B', 730, 99999),
(37, 'MMR', 455, 0, 'B', 365, 99999),
(38, 'MMR BOOSTER', 1662, 0, 'B', 395, 99999),
(39, 'PNEUMOCOCCAL 1', 60, 0, 'B', 45, 182),
(40, 'PNEUMOCOCCAL 2', 30, 39, 'B', 75, 365),
(41, 'PNEUMOCOCCAL 3', 30, 40, 'B', 105, 729),
(42, 'PNEUMOCOCCAL BOOSTER', 365, 41, 'B', 456, 1825),
(43, 'ROTAVIRUS ( RV 1)/ (RV 5)  DOSE 1', 60, 0, 'B', 42, 112),
(44, 'ROTAVIRUS ( RVI)/ (RV 5)  DOSE 2', 30, 43, 'B', 72, 240),
(45, 'Tdap/ Td /TT', 3680, 0, 'B', 3650, 99999),
(46, 'TYPHOID', 737, 0, 'B', 730, 99999),
(47, 'TYPHOID BOOSTER 1', 1095, 46, 'B', 730, 99999),
(48, 'TYPHOID BOOSTER 2', 1095, 47, 'B', 730, 99999),
(49, 'TYPHOID BOOSTER 3', 1095, 48, 'B', 730, 99999),
(50, 'TYPHOID BOOSTER 4', 1095, 49, 'B', 730, 99999),
(51, 'TYPHOID BOOSTER 5', 1095, 50, 'B', 730, 99999),
(52, 'Td / Tdap BOOSTER 1 ', 3650, 45, 'B', 3650, 99999),
(53, 'HEPATITIS-A BOOSTER', 220, 13, 'B', 548, 99999),
(54, 'OPV zero', 0, 0, 'B', 0, 0),
(55, 'Td / Tdap BOOSTER 2', 3650, 52, 'B', 3650, 999999),
(56, 'Td / Tdap BOOSTER 3', 3650, 55, 'B', 3650, 99999),
(60, 'CHOLERA  2', 15, 59, 'B', 760, 99999),
(59, 'CHOLERA 1', 745, 0, 'B', 745, 99999),
(63, 'ROTAVIRUS  (RV 5)  DOSE 3', 30, 44, 'B', 102, 240),
(65, 'INFLUENZA BOOSTER 5', 365, 27, 'B', 2007, 99999),
(66, 'INFLUENZA BOOSTER 6', 365, 65, 'B', 2372, 99999);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
