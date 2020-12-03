-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- 主機： localhost:8889
-- 產生時間： 2020 年 12 月 03 日 13:28
-- 伺服器版本： 5.7.26
-- PHP 版本： 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 資料庫： `sugar_formal`
--

-- --------------------------------------------------------

--
-- 資料表結構 `account_status_log`
--

CREATE TABLE `account_status_log` (
  `id` int(11) NOT NULL,
  `user_id` int(10) NOT NULL,
  `reasonType` int(2) NOT NULL,
  `reported_id` varchar(30) DEFAULT NULL,
  `content` text,
  `remark1` text,
  `remark2` text,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `account_status_log`
--
ALTER TABLE `account_status_log`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `account_status_log`
--
ALTER TABLE `account_status_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `users` ADD `accountStatus1` INT(1) NOT NULL DEFAULT '1' COMMENT '0:關閉 1:開啟(預設) ';

UPDATE `admin_common_text` SET `content` = '<h2>VIP功能</h2>\r\n <h3><span>●</span>解鎖信箱限制</h3>\r\n <h3><span>●</span>解鎖發訊限制</h3>\r\n\r\n<h3><span>●</span>解鎖開啟及關閉帳號限制</h3>\r\n <h3><span>●</span>解鎖足跡功能</h3>\r\n <h3><span>●</span>解鎖進階搜尋功能</h3>\r\n <h3><span>●</span>解鎖車馬費評價功能</h3>\r\n <h3><span>●</span>可以看進階資料</h3>\r\n <h3><span>●</span>可以看已讀未讀</h3>\r\n ' WHERE `admin_common_text`.`id` = 40;
