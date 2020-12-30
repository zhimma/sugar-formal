-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- 主機： localhost:8889
-- 產生時間： 2020 年 12 月 29 日 10:45
-- 伺服器版本： 5.7.26
-- PHP 版本： 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 資料庫： `sugar_formal`
--

-- --------------------------------------------------------

--
-- 資料表結構 `log_user_login`
--

CREATE TABLE `log_user_login` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `userAgent` varchar(200) NOT NULL,
  `ip` varchar(32) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `log_user_login`
--
ALTER TABLE `log_user_login`
  ADD UNIQUE KEY `id` (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `log_user_login`
--
ALTER TABLE `log_user_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
