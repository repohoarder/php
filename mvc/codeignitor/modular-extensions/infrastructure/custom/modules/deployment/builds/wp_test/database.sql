-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 15, 2014 at 12:32 PM
-- Server version: 5.5.37-log
-- PHP Version: 5.4.23

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `freesola_receptive`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dropoff_id` int(11) NOT NULL,
  `bill_date` varchar(255) DEFAULT '01',
  `last_billed` datetime DEFAULT '0000-00-00 00:00:00',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dropoff_id_unique` (`dropoff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dropoffs`
--

DROP TABLE IF EXISTS `dropoffs`;
CREATE TABLE IF NOT EXISTS `dropoffs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partials_id` int(11) NOT NULL,
  `first` varchar(255) NOT NULL,
  `last` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `cc_number` varchar(255) DEFAULT NULL,
  `cc_exp_month` varchar(255) DEFAULT NULL,
  `cc_exp_year` varchar(255) NOT NULL,
  `cc_cvv` varchar(255) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `partial_id_unique` (`partials_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `dropoffs`
--

INSERT INTO `dropoffs` (`id`, `partials_id`, `first`, `last`, `address`, `city`, `state`, `zip`, `country`, `phone`, `cc_number`, `cc_exp_month`, `cc_exp_year`, `cc_cvv`, `date_added`) VALUES
(1, 1, 'Simple', 'Testing', '123 tester ave', 'canton', 'OH', '44714', 'United States', '3305556666', '4111111111111111', '06', '14', '456', '2014-02-24 18:04:08'),
(3, 2, '', '', '', '', '', '', '', '', '', '', '', '', '2014-03-12 17:12:17');

-- --------------------------------------------------------

--
-- Table structure for table `dropoffs_products`
--

DROP TABLE IF EXISTS `dropoffs_products`;
CREATE TABLE IF NOT EXISTS `dropoffs_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dropoff_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `fulfilled` tinyint(1) NOT NULL DEFAULT '0',
  `last_billed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dropoff_product` (`dropoff_id`,`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `dropoffs_products`
--

INSERT INTO `dropoffs_products` (`id`, `dropoff_id`, `product_id`, `fulfilled`, `last_billed`, `date_added`) VALUES
(1, 1, 1, 0, '0000-00-00 00:00:00', '2014-02-24 18:04:08'),
(2, 0, 1, 0, '0000-00-00 00:00:00', '2014-02-24 18:04:19'),
(3, 3, 1, 0, '0000-00-00 00:00:00', '2014-03-12 17:12:17');

-- --------------------------------------------------------

--
-- Table structure for table `partials`
--

DROP TABLE IF EXISTS `partials`;
CREATE TABLE IF NOT EXISTS `partials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitor_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `first` varchar(255) NOT NULL,
  `last` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `partials`
--

INSERT INTO `partials` (`id`, `visitor_id`, `name`, `first`, `last`, `email`, `zip`, `phone`, `ip`, `date_added`) VALUES
(1, 2, 'myname', '', '', 'simple@testing.com', NULL, NULL, '74.218.103.238', '2014-02-24 18:03:08'),
(2, 3, '', '', '', '', NULL, NULL, '74.218.103.238', '2014-03-12 17:12:12'),
(3, 7, 'test', '', '', 'test@testes.com', NULL, NULL, '74.218.103.238', '2014-03-13 16:00:18'),
(4, 9, 'Your Name', '', '', 'Your Email', NULL, NULL, '99.51.213.9', '2014-03-14 03:37:52'),
(5, 27, '', 'first', 'last', 'email@address.com', NULL, '3305555555', '107.142.236.170', '2014-05-08 02:19:53'),
(6, 27, '', 'first', 'last', 'email@address.com', NULL, '3305555555', '107.142.236.170', '2014-05-08 02:21:14'),
(7, 27, '', 'First', 'Debit', 'test@test.com', NULL, '3305556666', '107.142.236.170', '2014-05-08 04:22:55');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `infusionsoft` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `price` float(6,2) NOT NULL,
  `term` int(2) NOT NULL DEFAULT '0',
  `type` enum('initial','upsell') NOT NULL DEFAULT 'initial',
  `fulfillment` varchar(255) DEFAULT 'none',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `infusionsoft`, `name`, `description`, `price`, `term`, `type`, `fulfillment`, `date_added`) VALUES
(1, 294, 'Product Number 1', NULL, 97.00, 0, 'initial', 'none', '2014-02-24 18:11:49'),
(2, 0, 'Chuck Hughes''', NULL, 39.95, 0, 'initial', 'none', '2014-05-13 15:33:34'),
(3, 0, 'Chuck Hughes'' Special Offer', NULL, 97.00, 0, 'initial', 'none', '2014-05-13 17:00:04');

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

DROP TABLE IF EXISTS `refunds`;
CREATE TABLE IF NOT EXISTS `refunds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `amount` float(6,2) DEFAULT NULL,
  `type` enum('full','partial') DEFAULT 'full',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dropoff_id` int(11) NOT NULL,
  `account` varchar(255) NOT NULL DEFAULT 'business',
  `amount` float(6,2) DEFAULT NULL,
  `response_code` varchar(255) DEFAULT NULL,
  `response_text` varchar(255) DEFAULT NULL,
  `order_id` varchar(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `type` enum('initial','rebill') NOT NULL DEFAULT 'initial',
  `success` tinyint(1) DEFAULT NULL,
  `date_submitted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `dropoff_id`, `account`, `amount`, `response_code`, `response_text`, `order_id`, `transaction_id`, `type`, `success`, `date_submitted`) VALUES
(1, 0, 'infusionsoft', 97.00, 'SKIPPED', 'There was no balance to charge', 'None', 'None', 'initial', 0, '2014-02-24 18:15:56'),
(2, 0, 'infusionsoft', 97.00, 'SKIPPED', 'There was no balance to charge', 'None', 'None', 'initial', 0, '2014-02-24 18:18:11'),
(3, 0, 'infusionsoft', 97.00, 'DECLINED', 'This transaction has been declined. (2)', '5957485032', '5957485032', 'initial', 0, '2014-02-24 18:19:47'),
(4, 3, 'infusionsoft', 97.00, 'ERROR', 'Credit card number is required. (33)', '0', '0', 'initial', 0, '2014-03-12 17:12:18'),
(5, 0, 'infusionsoft', 97.00, '', '', '', '', 'initial', 0, '2014-05-08 01:46:10'),
(6, 0, 'infusionsoft', 97.00, '', '', '', '', 'initial', 0, '2014-05-08 03:23:36'),
(7, 0, 'infusionsoft', 97.00, '', '', '', '', 'initial', 0, '2014-05-08 03:30:56'),
(8, 0, 'infusionsoft', 97.00, '', '', '', '', 'initial', 0, '2014-05-09 17:53:33'),
(9, 0, 'infusionsoft', 97.00, '', '', '', '', 'initial', 0, '2014-05-10 12:50:12'),
(10, 0, 'infusionsoft', 97.00, '', '', '', '', 'initial', 0, '2014-05-10 12:50:25'),
(11, 0, 'infusionsoft', 97.00, '', '', '', '', 'initial', 0, '2014-05-13 16:56:23');

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

DROP TABLE IF EXISTS `visitors`;
CREATE TABLE IF NOT EXISTS `visitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) DEFAULT NULL,
  `browser` varchar(255) NOT NULL,
  `browser_version` varchar(255) NOT NULL,
  `operating_system` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_unique` (`session_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`id`, `session_id`, `browser`, `browser_version`, `operating_system`, `ip`, `date_added`) VALUES
(1, '530b75d8da76a', 'Google', '3.0', 'Windows', '74.218.103.238', '2014-02-24 16:39:52'),
(2, '530b77e0286fe', 'Google', '3.0', 'Windows', '74.218.103.238', '2014-02-24 16:48:32'),
(3, '532095640a1e1', 'Google', '3.0', 'Windows', '74.218.103.238', '2014-03-12 17:12:08'),
(4, '5321224114d68', 'Google', '3.0', 'Windows', '99.51.213.9', '2014-03-13 03:30:06'),
(5, '5321349430a53', 'Google', '3.0', 'Windows', '99.51.213.9', '2014-03-13 04:32:30'),
(6, '53214009773df', 'Google', '3.0', 'Windows', '99.51.213.9', '2014-03-13 05:20:09'),
(7, '5321be393acbd', 'Google', '3.0', 'Windows', '74.218.103.238', '2014-03-13 14:18:33'),
(8, '532275822e59e', 'Google', '3.0', 'Windows', '99.51.213.9', '2014-03-14 03:20:34'),
(9, '532279062a9c7', 'Google', '3.0', 'Windows', '99.51.213.9', '2014-03-14 03:35:38'),
(10, '53230989011a7', 'Google', '3.0', 'Windows', '74.218.103.238', '2014-03-14 13:52:13'),
(11, '532320c23d190', 'Google', '3.0', 'Windows', '74.218.103.238', '2014-03-14 15:31:14'),
(12, '532337a378712', 'Google', '3.0', 'Windows', '50.243.8.121', '2014-03-14 17:08:51'),
(13, '53245db6ded98', 'Google', '3.0', 'Windows', '99.51.213.9', '2014-03-15 14:03:34'),
(14, '5327167cbd92c', 'Google', '3.0', 'Windows', '74.218.103.238', '2014-03-17 15:36:28'),
(15, '534de116f2081', 'Google', '3.0', 'Windows', '107.142.236.170', '2014-04-16 01:47:03'),
(16, '534de169a75d9', 'Google', '3.0', 'Windows', '71.202.4.6', '2014-04-16 01:48:25'),
(17, '534eb27e2fe1a', 'Google', '3.0', 'Windows', '50.243.8.121', '2014-04-16 16:40:30'),
(18, '53551a1bde273', 'Google', '3.0', 'Windows', '8.22.12.114', '2014-04-21 13:16:11'),
(19, '535542e7b07c5', 'Google', '3.0', 'Windows', '50.243.8.121', '2014-04-21 16:10:15'),
(20, '5356f8d8a902c', 'Google', '3.0', 'Windows', '50.243.8.121', '2014-04-22 23:18:48'),
(21, '53575e88f2fb5', 'Google', '3.0', 'Windows', '71.202.4.6', '2014-04-23 06:32:41'),
(22, '5357e54992c95', 'Google', '3.0', 'Windows', '50.243.8.121', '2014-04-23 16:07:37'),
(23, '535e6b94620f4', 'Google', '3.0', 'Windows', '50.243.8.121', '2014-04-28 14:54:12'),
(24, '535ea4efa47bf', 'Google', '3.0', 'Windows', '50.243.8.121', '2014-04-28 18:58:55'),
(25, '53671c114a061', 'Google', '3.0', 'Windows', '71.199.11.25', '2014-05-05 05:05:21'),
(26, '536a46c61094c', 'Google', '3.0', 'Windows', '74.218.103.238', '2014-05-07 14:44:22'),
(27, '536ad9021f8d5', 'Google', '3.0', 'Windows', '107.142.236.170', '2014-05-08 01:08:18'),
(28, '536b07626525b', 'Google', '3.0', 'Windows', '71.199.11.25', '2014-05-08 04:26:10'),
(29, '536b94af239bd', 'Google', '3.0', 'Windows', '74.218.103.238', '2014-05-08 14:29:03'),
(30, '536d161d6abf8', 'Google', '3.0', 'Windows', '71.199.11.25', '2014-05-09 17:53:33'),
(31, '536e208480495', 'Google', '3.0', 'Windows', '107.142.236.170', '2014-05-10 12:50:12'),
(32, '5371dcaf4e059', 'Google', '3.0', 'Windows', '71.199.11.25', '2014-05-13 08:49:51'),
(33, '5372256b3d4e2', 'Google', '3.0', 'Windows', '74.218.103.238', '2014-05-13 14:00:11'),
(34, '53724d5327f93', 'Google', '3.0', 'Windows', '166.137.209.30', '2014-05-13 16:50:27'),
(35, '53746fefde853', 'Google', '3.0', 'Windows', '71.199.11.25', '2014-05-15 07:42:39'),
(36, '537470d6dd08b', 'Google', '3.0', 'Windows', '71.199.11.25', '2014-05-15 07:46:30'),
(37, '5374c1de61ae5', 'Google', '3.0', 'Windows', '74.218.103.238', '2014-05-15 13:32:14'),
(38, '5374c30cb083f', 'Google', '3.0', 'Windows', '74.218.103.238', '2014-05-15 13:37:16'),
(39, '5374e45852a10', 'Google', '3.0', 'Windows', '74.218.103.238', '2014-05-15 15:59:20'),
(40, '5374e45aa148b', 'Google', '3.0', 'Windows', '74.218.103.238', '2014-05-15 15:59:22'),
(41, '5374e45aa147b', 'Google', '3.0', 'Windows', '74.218.103.238', '2014-05-15 15:59:22'),
(42, '5374e45b4dc15', 'Google', '3.0', 'Windows', '74.218.103.238', '2014-05-15 15:59:23');

-- --------------------------------------------------------

--
-- Table structure for table `visitors_hits`
--

DROP TABLE IF EXISTS `visitors_hits`;
CREATE TABLE IF NOT EXISTS `visitors_hits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitor_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `page` varchar(255) DEFAULT NULL,
  `params` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `visitor_uri` (`visitor_id`,`page`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=444 ;

--
-- Dumping data for table `visitors_hits`
--

INSERT INTO `visitors_hits` (`id`, `visitor_id`, `title`, `url`, `page`, `params`, `date_added`) VALUES
(1, 1, '', 'receptivedata.com/test', 'test', '[]', '2014-02-24 16:39:52'),
(3, 2, '', 'receptivedata.com/test', 'test', '[]', '2014-02-24 16:48:32'),
(12, 2, '', 'receptivedata.com/test', 'squeeze', '[]', '2014-02-24 18:02:57'),
(14, 2, '', 'receptivedata.com/test', 'offer', '[]', '2014-02-24 18:03:09'),
(15, 2, '', 'receptivedata.com/test', 'billing', '[]', '2014-02-24 18:03:11'),
(18, 2, '', 'receptivedata.com/test', 'processing', '[]', '2014-02-24 18:04:20'),
(20, 2, '', 'receptivedata.com/test', 'declined', '[]', '2014-02-24 18:15:57'),
(27, 3, '', 'receptivedata.com/', 'squeeze', '[]', '2014-03-12 17:12:08'),
(29, 3, '', 'receptivedata.com/', 'offer', '[]', '2014-03-12 17:12:12'),
(30, 3, '', 'receptivedata.com/', 'billing', '[]', '2014-03-12 17:12:15'),
(32, 3, '', 'receptivedata.com/', 'processing', '[]', '2014-03-12 17:12:17'),
(33, 3, '', 'receptivedata.com/', 'declined', '[]', '2014-03-12 17:12:19'),
(34, 4, '', 'receptivedata.com/financial', 'squeeze', '[]', '2014-03-13 03:30:06'),
(76, 5, '', 'receptivedata.com/', 'squeeze', '[]', '2014-03-13 04:32:30'),
(104, 6, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-03-13 05:20:09'),
(106, 7, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-03-13 14:18:33'),
(204, 8, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-03-14 03:20:34'),
(209, 9, '', 'receptivedata.com/', 'squeeze', '[]', '2014-03-14 03:35:38'),
(216, 10, '', 'absolutehomeprofits.com/', 'squeeze', '[]', '2014-03-14 13:52:13'),
(220, 11, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-03-14 15:31:14'),
(221, 12, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-03-14 17:08:51'),
(222, 13, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-03-15 14:03:34'),
(223, 14, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-03-17 15:36:28'),
(224, 15, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-04-16 01:47:03'),
(225, 16, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-04-16 01:48:25'),
(229, 17, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-04-16 16:40:30'),
(230, 18, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-04-21 13:16:11'),
(231, 19, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-04-21 16:10:15'),
(232, 20, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-04-22 23:18:48'),
(233, 21, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-04-23 06:32:41'),
(234, 22, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-04-23 16:07:37'),
(235, 23, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-04-28 14:54:12'),
(237, 24, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-04-28 18:58:55'),
(239, 25, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-05-05 05:05:21'),
(240, 26, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-05-07 14:44:22'),
(241, 27, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-05-08 01:08:18'),
(242, 27, '', 'receptivedata.com/squeeze', 'discount', '[]', '2014-05-08 01:40:05'),
(245, 27, '', 'receptivedata.com/squeeze', 'processing', '[]', '2014-05-08 01:46:10'),
(258, 27, '', 'receptivedata.com/squeeze', 'searching', '[]', '2014-05-08 02:26:50'),
(259, 27, '', 'receptivedata.com/squeeze', 'offer', '[]', '2014-05-08 02:26:50'),
(279, 27, '', 'receptivedata.com/squeeze', 'billing', '[]', '2014-05-08 02:46:38'),
(326, 28, '', 'receptivedata.com/discount', 'discount', '[]', '2014-05-08 04:26:10'),
(327, 29, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-05-08 14:29:03'),
(328, 29, '', 'receptivedata.com/squeeze', 'searching', '[]', '2014-05-08 14:29:11'),
(329, 29, '', 'receptivedata.com/squeeze', 'offer', '[]', '2014-05-08 15:06:25'),
(330, 30, '', 'receptivedata.com/processing', 'processing', '[]', '2014-05-09 17:53:33'),
(331, 30, '', 'receptivedata.com/processing', 'declined', '[]', '2014-05-09 17:53:35'),
(332, 31, '', 'receptivedata.com/processing', 'processing', '[]', '2014-05-10 12:50:12'),
(333, 31, '', 'receptivedata.com/processing', 'declined', '[]', '2014-05-10 12:50:13'),
(336, 32, '', 'receptivedata.com/offer', 'offer', '[]', '2014-05-13 08:49:51'),
(337, 33, '', 'receptivedata.com/offer', 'offer', '[]', '2014-05-13 14:00:11'),
(380, 33, '', 'receptivedata.com/offer', 'billing', '[]', '2014-05-13 15:00:27'),
(381, 33, '', 'receptivedata.com/offer', 'discount', '[]', '2014-05-13 15:00:37'),
(389, 34, '', 'www.receptivedata.com/offer', 'offer', '[]', '2014-05-13 16:50:27'),
(391, 33, '', 'receptivedata.com/offer', 'processing', '[]', '2014-05-13 16:56:23'),
(392, 33, '', 'receptivedata.com/offer', 'declined', '[]', '2014-05-13 16:56:24'),
(393, 35, '', 'www.receptivedata.com/offer', 'offer', '[]', '2014-05-15 07:42:39'),
(395, 36, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-05-15 07:46:30'),
(397, 36, '', 'receptivedata.com/squeeze', 'searching', '[]', '2014-05-15 08:01:09'),
(398, 36, '', 'receptivedata.com/squeeze', 'offer', '[]', '2014-05-15 08:01:14'),
(400, 37, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-05-15 13:32:14'),
(402, 37, '', 'receptivedata.com/squeeze', 'searching', '[]', '2014-05-15 13:36:12'),
(403, 37, '', 'receptivedata.com/squeeze', 'offer', '[]', '2014-05-15 13:36:17'),
(414, 38, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-05-15 13:37:16'),
(424, 38, '', 'receptivedata.com/squeeze', 'offer', '[]', '2014-05-15 13:47:31'),
(438, 39, '', 'receptivedata.com/offer', 'offer', '[]', '2014-05-15 15:59:20'),
(439, 40, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-05-15 15:59:22'),
(440, 41, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-05-15 15:59:22'),
(442, 42, '', 'receptivedata.com/squeeze', 'squeeze', '[]', '2014-05-15 15:59:23');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
