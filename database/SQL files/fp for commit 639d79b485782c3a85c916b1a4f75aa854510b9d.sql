-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1:3307
-- 產生時間： 
-- 伺服器版本： 10.3.14-MariaDB
-- PHP 版本： 7.1.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `test1`
--

-- --------------------------------------------------------

--
-- 資料表結構 `fp`
--

DROP TABLE IF EXISTS `fp`;
CREATE TABLE IF NOT EXISTS `fp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fp` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `userAgent` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `webdriver` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `colorDepth` int(11) NOT NULL,
  `deviceMemory` int(11) NOT NULL,
  `hardwareConcurrency` int(11) NOT NULL,
  `screenResolution` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `availableScreenResolution` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `timezoneOffset` int(11) NOT NULL,
  `timezone` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sessionStorage` tinyint(1) NOT NULL,
  `localStorage` tinyint(1) NOT NULL,
  `indexedDb` tinyint(1) NOT NULL,
  `addBehavior` tinyint(1) NOT NULL,
  `openDatabase` tinyint(1) NOT NULL,
  `cpuClass` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `platform` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `plugins` varchar(5000) COLLATE utf8_unicode_ci NOT NULL,
  `canvas` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `webgl` varchar(5000) COLLATE utf8_unicode_ci NOT NULL,
  `webglVendorAndRenderer` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `adBlock` tinyint(1) NOT NULL,
  `hasLiedLanguages` tinyint(1) NOT NULL,
  `hasLiedResolution` tinyint(1) NOT NULL,
  `hasLiedOs` tinyint(1) NOT NULL,
  `hasLiedBrowser` tinyint(1) NOT NULL,
  `touchSupport` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `fonts` varchar(1500) COLLATE utf8_unicode_ci NOT NULL,
  `audio` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
