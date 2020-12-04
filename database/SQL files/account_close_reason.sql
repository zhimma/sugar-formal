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


ALTER TABLE `users` ADD `accountStatus` INT(1) NOT NULL DEFAULT '1' COMMENT '0:關閉 1:開啟(預設) ';

UPDATE `admin_common_text` SET `content` = '<h2>VIP功能</h2>\r\n <h3><span>●</span>解鎖信箱限制</h3>\r\n <h3><span>●</span>解鎖發訊限制</h3>\r\n\r\n<h3><span>●</span>解鎖開啟及關閉帳號限制</h3>\r\n <h3><span>●</span>解鎖足跡功能</h3>\r\n <h3><span>●</span>解鎖進階搜尋功能</h3>\r\n <h3><span>●</span>解鎖車馬費評價功能</h3>\r\n <h3><span>●</span>可以看進階資料</h3>\r\n <h3><span>●</span>可以看已讀未讀</h3>\r\n ' WHERE `admin_common_text`.`id` = 40;
UPDATE `admin_common_text` SET `content` = '●您選擇的是信用卡刷卡月付。金額是每月1388 元。\\n●加入VIP後將於每月於第一次刷卡日期自動扣款，至取消為止。 \\n●升級VIP之後，升級VIP的選項會變成取消VIP，取消後次月即停止扣款 \\n●訊息不會被過濾掉(會員可以設定拒接非VIP會員來信) \\n●不受限制的收發信件(失去 VIP 權限後普通會員收發信件總數將受限) \\n●不限次數開啟及關閉帳號 \\n●可以觀看進階統計資料 \\n●可以知道訊息是否已讀 \\n●可以知道對方是否封鎖自己 \\n●您申請每月自動扣款並完成繳費，經確認繳費程序完成且已成功開啟本站相關服務設定，即視同您已經開始使用所購買之本站服務。 \\n●最短 VIP 時間為一個月，若使用不足一個月，以一個月計算，不予退費。 \\n★取消 VIP 時間需要七個工作天，如下個月不續約請提前取消，以免權益受損！\\n如同意以上所有條款請點確認，不同意點取消。' WHERE (`id` = '61');
UPDATE `admin_common_text` SET `content` = '●您選擇的是信用卡刷卡季付。金額是每月988*3 元。\\n●加入VIP後將於每三個月於第一次刷卡日期自動扣款，至取消為止。 \\n●升級VIP之後，升級VIP的選項會變成取消VIP，取消後次期即停止扣款 \\n●訊息不會被過濾掉(會員可以設定拒接非VIP會員來信) \\n●不受限制的收發信件(失去 VIP 權限後普通會員收發信件總數將受限) \\n●不限次數開啟及關閉帳號 \\n●可以觀看進階統計資料 \\n●可以知道訊息是否已讀 \\n●可以知道對方是否封鎖自己 \\n●您申請每月自動扣款並完成繳費，經確認繳費程序完成且已成功開啟本站相關服務設定，即視同您已經開始使用所購買之本站服務。 \\n●本價格為促銷價格，最短 VIP 時間為三個月，一旦開啟不予退費。後續自動扣款亦同！ \\n★取消 VIP 時間需要七個工作天，如下個月不續約請提前取消，以免權益受損！\\n如同意以上所有條款請點確認，不同意點取消。' WHERE (`id` = '62');
UPDATE `admin_common_text` SET `content` = '●您選擇的是一次性支付。金額是每月1388 元。\\n●訊息不會被過濾掉(會員可以設定拒接非VIP會員來信) \\n●不受限制的收發信件(下個月起普通會員收發信件總數將受限) \\n●不限次數開啟及關閉帳號 \\n●可以觀看進階統計資料 \\n●可以知道訊息是否已讀 \\n●可以知道對方是否封鎖自己 \\n●您申請扣款並完成繳費，經確認繳費程序完成且已成功開啟本站相關服務設定，即視同您已經開始使用所購買之本站服務。\\n●最短 VIP 時間為一個月，若使用不足一個月，以一個月計算，不予退費。\\n如同意以上所有條款請點確認，不同意點取消。' WHERE (`id` = '63');
UPDATE `admin_common_text` SET `content` = '●您選擇的是一次性支付。金額是每月988*3 元。\\n●訊息不會被過濾掉(會員可以設定拒接非VIP會員來信) \\n●不受限制的收發信件(三個月後起普通會員收發信件總數將受限) \\n●不限次數開啟及關閉帳號 \\n●可以觀看進階統計資料 \\n●可以知道訊息是否已讀 \\n●可以知道對方是否封鎖自己 \\n●您申請扣款並完成繳費，經確認繳費程序完成且已成功開啟本站相關服務設定，即視同您已經開始使用所購買之本站服務。 \\n●本價格為促銷價格，VIP 時間為三個月，一旦開啟不予退費。\\n如同意以上所有條款請點確認，不同意點取消。' WHERE (`id` = '64');