-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2020 年 06 月 01 日 02:06
-- 伺服器版本： 10.3.15-MariaDB
-- PHP 版本： 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `sugar_formal`
--

-- --------------------------------------------------------

--
-- 資料表結構 `log_cancel_vip`
--

CREATE TABLE `log_cancel_vip` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 資料表結構 `log_cancel_vip_failed`
--

CREATE TABLE `log_cancel_vip_failed` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `log_chatpay_infos`
--

CREATE TABLE `log_chatpay_infos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 資料表結構 `log_chat_pay`
--

CREATE TABLE `log_chat_pay` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `to_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 資料表結構 `log_dat_file`
--

CREATE TABLE `log_dat_file` (
  `id` int(10) NOT NULL,
  `upload_check` int(2) NOT NULL,
  `local_file` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `remote_file` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `content` varchar(500) CHARACTER SET utf8mb4 DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `log_upgraded_infos`
--

CREATE TABLE `log_upgraded_infos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` varchar(1000) CHARACTER SET utf8mb4 NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 資料表結構 `log_upgraded_infos_when_giving_permission`
--

CREATE TABLE `log_upgraded_infos_when_giving_permission` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` varchar(1000) CHARACTER SET utf8mb4 NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 資料表結構 `log_upgrade_click`
--

CREATE TABLE `log_upgrade_click` (
  `id` int(10) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 資料表結構 `log_vip_crontab`
--

CREATE TABLE `log_vip_crontab` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` varchar(1000) CHARACTER SET utf8mb4 NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 資料表結構 `log_mobilepay_infos`
--

CREATE TABLE `log_mobilepay_infos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `log_cancel_vip`
--
ALTER TABLE `log_cancel_vip`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `log_cancel_vip_failed`
--
ALTER TABLE `log_cancel_vip_failed`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `log_chatpay_infos`
--
ALTER TABLE `log_chatpay_infos`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `log_chat_pay`
--
ALTER TABLE `log_chat_pay`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `log_dat_file`
--
ALTER TABLE `log_dat_file`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `log_upgraded_infos`
--
ALTER TABLE `log_upgraded_infos`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `log_upgraded_infos_when_giving_permission`
--
ALTER TABLE `log_upgraded_infos_when_giving_permission`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `log_upgrade_click`
--
ALTER TABLE `log_upgrade_click`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `log_vip_crontab`
--
ALTER TABLE `log_vip_crontab`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `log_mobilepay_infos`
--
ALTER TABLE `log_mobilepay_infos`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `log_cancel_vip`
--
ALTER TABLE `log_cancel_vip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `log_cancel_vip_failed`
--
ALTER TABLE `log_cancel_vip_failed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `log_chatpay_infos`
--
ALTER TABLE `log_chatpay_infos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `log_chat_pay`
--
ALTER TABLE `log_chat_pay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `log_dat_file`
--
ALTER TABLE `log_dat_file`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `log_upgraded_infos`
--
ALTER TABLE `log_upgraded_infos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `log_upgraded_infos_when_giving_permission`
--
ALTER TABLE `log_upgraded_infos_when_giving_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `log_upgrade_click`
--
ALTER TABLE `log_upgrade_click`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `log_vip_crontab`
--
ALTER TABLE `log_vip_crontab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `log_mobilepay_infos`
--
ALTER TABLE `log_mobilepay_infos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
