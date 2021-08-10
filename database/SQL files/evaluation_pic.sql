ALTER TABLE `evaluation` ADD `is_check` TINYINT(1) NOT NULL DEFAULT '0' AFTER `read`;


-- --------------------------------------------------------
--
-- 資料表結構 `evaluation_pic`
--
CREATE TABLE `evaluation_pic` (
  `id` int(10) UNSIGNED NOT NULL,
  `evaluation_id` int(10) DEFAULT NULL,
  `member_id` int(10) UNSIGNED NOT NULL,
  `pic` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- 已傾印資料表的索引
--
--
-- 資料表索引 `evaluation_pic`
--
ALTER TABLE `evaluation_pic`
  ADD PRIMARY KEY (`id`);
--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--
--
-- 使用資料表自動遞增(AUTO_INCREMENT) `evaluation_pic`
--
ALTER TABLE `evaluation_pic`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
