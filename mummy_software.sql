-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 13, 2012 at 02:43 PM
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
  `phone` varchar(10) DEFAULT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `id_dob`, `name`, `first_name`, `middle_name`, `last_name`, `father_name`, `father_occ`, `mother_name`, `mother_occ`, `address`, `sibling`, `pin`, `res_phone`, `office_phone`, `dob`, `email`, `phone`, `sex`, `born_at`, `mode_of_delivery`, `birth_weight`, `comp_preg`, `comp_deli`, `cry_birth`, `admit_hospital`, `injury_child`, `prev_surgery`, `allergy_child`, `other_info`, `diabetes`, `asthma`, `cardiac`, `hypertension`, `tuberculosis`, `other_fam_history`, `breast_feed_month`, `solid_month`) VALUES
(19, '', 'Richa Vats', 'Richa', NULL, 'Vats', 'Rakesh Vats', '', '', '', 'WZ-28, Naraina Village,New Delhi', 20, NULL, NULL, NULL, '1989-03-26', '', '9968262526', 'F', NULL, 'Normal', NULL, 'No', 'No', 'Yes', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', NULL, NULL),
(20, '', 'Gaurav Vats', 'Gaurav', NULL, 'Vats', 'Rakesh Vats', '', '', '', 'WZ-28, Naraina Village,New Delhi', 19, NULL, NULL, NULL, '1991-04-10', '', '9968262526', 'M', NULL, 'Normal', NULL, 'No', 'No', 'Yes', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', NULL, NULL);

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
(45, 'Tdap/ Td', 3680, 0, 'B', 3650, 99999),
(46, 'TYPHOID', 737, 0, 'B', 730, 99999),
(47, 'TYPHOID BOOSTER 1', 1095, 46, 'B', 730, 99999),
(48, 'TYPHOID BOOSTER 2', 1095, 47, 'B', 730, 99999),
(49, 'TYPHOID BOOSTER 3', 1095, 48, 'B', 730, 99999),
(50, 'TYPHOID BOOSTER 4', 1095, 49, 'B', 730, 99999),
(51, 'TYPHOID BOOSTER 5', 1095, 50, 'B', 730, 99999),
(52, 'Td BOOSTER/ Tdap', 3650, 45, 'B', 3650, 99999);

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=389 ;

--
-- Dumping data for table `vac_schedule`
--

INSERT INTO `vac_schedule` (`id`, `p_id`, `v_id`, `date`, `date_given`, `given`) VALUES
(290, 19, 1, '1989-04-25', '0000-00-00', 'Y'),
(291, 19, 3, '1998-03-24', '0000-00-00', 'N'),
(292, 19, 4, '1998-04-23', '0000-00-00', 'N'),
(293, 19, 5, '1998-09-20', '0000-00-00', 'N'),
(294, 19, 6, '1990-04-25', '2002-02-09', 'Y'),
(295, 19, 7, '1993-03-25', '0000-00-00', 'N'),
(296, 19, 8, '1989-05-25', '0000-00-00', 'Y'),
(297, 19, 9, '1989-06-24', '0000-00-00', 'Y'),
(298, 19, 10, '1989-07-24', '0000-00-00', 'Y'),
(299, 19, 11, '1990-07-24', '0000-00-00', 'Y'),
(300, 19, 12, '1993-10-13', '0000-00-00', 'Y'),
(301, 19, 13, '1990-03-31', '0000-00-00', 'N'),
(302, 19, 14, '1990-09-27', '0000-00-00', 'N'),
(303, 19, 15, '1989-03-26', '1997-09-01', 'Y'),
(304, 19, 16, '1989-04-25', '1997-10-01', 'Y'),
(305, 19, 17, '1989-09-22', '1998-03-02', 'Y'),
(306, 19, 18, '1990-07-24', '0000-00-00', 'N'),
(307, 19, 19, '1989-05-25', '0000-00-00', 'N'),
(308, 19, 20, '1989-06-24', '0000-00-00', 'N'),
(309, 19, 21, '1989-07-24', '0000-00-00', 'N'),
(310, 19, 22, '1989-11-21', '0000-00-00', 'N'),
(311, 19, 23, '1989-12-21', '0000-00-00', 'N'),
(312, 19, 24, '1990-12-21', '0000-00-00', 'N'),
(313, 19, 25, '1991-12-21', '0000-00-00', 'N'),
(314, 19, 26, '1992-12-20', '0000-00-00', 'N'),
(315, 19, 27, '1993-12-20', '0000-00-00', 'N'),
(316, 19, 28, '1989-05-25', '0000-00-00', 'N'),
(317, 19, 29, '1989-06-24', '0000-00-00', 'N'),
(318, 19, 30, '1989-07-24', '0000-00-00', 'N'),
(319, 19, 31, '1990-07-24', '0000-00-00', 'N'),
(320, 19, 32, '1993-01-21', '0000-00-00', 'N'),
(321, 19, 33, '1989-11-21', '0000-00-00', 'Y'),
(322, 19, 34, '1991-03-26', '0000-00-00', 'N'),
(323, 19, 35, '1994-03-25', '0000-00-00', 'N'),
(324, 19, 36, '1997-03-24', '0000-00-00', 'N'),
(325, 19, 37, '1990-05-05', '0000-00-00', 'Y'),
(326, 19, 38, '1993-10-13', '0000-00-00', 'N'),
(327, 19, 39, '1989-05-25', '0000-00-00', 'N'),
(328, 19, 40, '1989-06-24', '0000-00-00', 'N'),
(329, 19, 41, '1989-07-24', '0000-00-00', 'N'),
(330, 19, 42, '1990-07-24', '0000-00-00', 'N'),
(331, 19, 43, '1989-05-07', '0000-00-00', 'N'),
(332, 19, 44, '1989-06-06', '0000-00-00', 'N'),
(333, 19, 45, '1998-10-12', '1999-05-03', 'Y'),
(334, 19, 46, '1991-03-26', '1997-10-01', 'Y'),
(335, 19, 47, '1994-03-25', '2000-12-30', 'Y'),
(336, 19, 48, '1997-03-24', '0000-00-00', 'N'),
(337, 19, 49, '2000-03-23', '0000-00-00', 'N'),
(338, 19, 50, '2003-03-23', '0000-00-00', 'N'),
(339, 19, 51, '2006-03-22', '0000-00-00', 'N'),
(340, 19, 52, '2008-10-09', '0000-00-00', 'N'),
(341, 20, 1, '1991-05-10', '0000-00-00', 'Y'),
(342, 20, 6, '1992-05-09', '2002-02-09', 'Y'),
(343, 20, 7, '1995-04-09', '0000-00-00', 'N'),
(344, 20, 8, '1991-06-09', '0000-00-00', 'Y'),
(345, 20, 9, '1991-07-09', '0000-00-00', 'Y'),
(346, 20, 10, '1991-08-08', '0000-00-00', 'Y'),
(347, 20, 11, '1992-08-07', '0000-00-00', 'Y'),
(348, 20, 12, '1995-10-28', '1997-09-01', 'Y'),
(349, 20, 13, '1992-04-14', '0000-00-00', 'N'),
(350, 20, 14, '1992-10-11', '0000-00-00', 'N'),
(351, 20, 15, '1991-04-10', '1997-09-02', 'Y'),
(352, 20, 16, '1991-05-10', '1997-10-01', 'Y'),
(353, 20, 17, '1991-10-07', '1998-03-02', 'Y'),
(354, 20, 18, '1992-08-07', '0000-00-00', 'N'),
(355, 20, 19, '1991-06-09', '0000-00-00', 'N'),
(356, 20, 20, '1991-07-09', '0000-00-00', 'N'),
(357, 20, 21, '1991-08-08', '0000-00-00', 'N'),
(358, 20, 22, '1991-12-06', '0000-00-00', 'N'),
(359, 20, 23, '1992-01-05', '0000-00-00', 'N'),
(360, 20, 24, '1993-01-04', '0000-00-00', 'N'),
(361, 20, 25, '1994-01-04', '0000-00-00', 'N'),
(362, 20, 26, '1995-01-04', '0000-00-00', 'N'),
(363, 20, 27, '1996-01-04', '0000-00-00', 'N'),
(364, 20, 28, '1991-06-09', '0000-00-00', 'N'),
(365, 20, 29, '1991-07-09', '0000-00-00', 'N'),
(366, 20, 30, '1991-08-08', '0000-00-00', 'N'),
(367, 20, 31, '1992-08-07', '0000-00-00', 'N'),
(368, 20, 32, '1995-02-05', '0000-00-00', 'N'),
(369, 20, 33, '1991-12-06', '0000-00-00', 'Y'),
(370, 20, 34, '1993-04-09', '0000-00-00', 'N'),
(371, 20, 35, '1996-04-08', '0000-00-00', 'N'),
(372, 20, 36, '1999-04-08', '0000-00-00', 'N'),
(373, 20, 37, '1992-05-19', '0000-00-00', 'Y'),
(374, 20, 38, '1995-10-28', '0000-00-00', 'N'),
(375, 20, 39, '1991-06-09', '0000-00-00', 'N'),
(376, 20, 40, '1991-07-09', '0000-00-00', 'N'),
(377, 20, 41, '1991-08-08', '0000-00-00', 'N'),
(378, 20, 42, '1992-08-07', '0000-00-00', 'N'),
(379, 20, 43, '1991-05-22', '0000-00-00', 'N'),
(380, 20, 44, '1991-06-21', '0000-00-00', 'N'),
(381, 20, 45, '2000-10-26', '2002-02-02', 'N'),
(382, 20, 46, '1993-04-09', '1997-11-03', 'N'),
(383, 20, 47, '1996-04-08', '2000-12-30', 'Y'),
(384, 20, 48, '1999-04-08', '0000-00-00', 'N'),
(385, 20, 49, '2002-04-07', '0000-00-00', 'N'),
(386, 20, 50, '2005-04-06', '0000-00-00', 'N'),
(387, 20, 51, '2008-04-05', '0000-00-00', 'N'),
(388, 20, 52, '2010-10-24', '0000-00-00', 'N');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
