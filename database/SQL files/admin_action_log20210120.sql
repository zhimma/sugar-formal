-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- 主機： localhost:8889
-- 產生時間： 2021 年 01 月 20 日 09:48
-- 伺服器版本： 5.7.26
-- PHP 版本： 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 資料庫： `sugar_formal`
--

-- --------------------------------------------------------

--
-- 資料表結構 `admin_action_log`
--

CREATE TABLE `admin_action_log` (
  `id` int(11) NOT NULL,
  `operator` int(11) NOT NULL,
  `target_id` int(11) NOT NULL,
  `act` varchar(20) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `admin_action_log`
--
ALTER TABLE `admin_action_log`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `admin_action_log`
--
ALTER TABLE `admin_action_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;