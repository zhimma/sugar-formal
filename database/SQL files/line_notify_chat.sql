-- phpMyAdmin SQL Dump
-- version 5.0.0-alpha1
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2021 年 04 月 13 日 05:03
-- 伺服器版本： 5.5.64-MariaDB
-- PHP 版本： 7.4.0RC2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `admin_sgtest`
--

-- --------------------------------------------------------

--
-- 資料表結構 `line_notify_chat`
--

CREATE TABLE `line_notify_chat` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` tinyint(4) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `order` tinyint(4) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `line_notify_chat`
--

INSERT INTO `line_notify_chat` (`id`, `name`, `gender`, `active`, `order`) VALUES
(1, '長期為主', 1, 1, 1),
(2, '長短皆可', 1, 1, 2),
(3, '單次為主', 1, 1, 3),
(4, 'VVIP', 2, 0, 1),
(5, 'VIP', 2, 1, 2),
(6, '普通會員', 2, 1, 3),
(7, '警示會員', 0, 1, 99);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `line_notify_chat`
--
ALTER TABLE `line_notify_chat`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `line_notify_chat`
--
ALTER TABLE `line_notify_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

