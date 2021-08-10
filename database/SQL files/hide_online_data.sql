--
-- 資料表結構 `hide_online_data`
--

CREATE TABLE `hide_online_data` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `register_time` datetime NOT NULL,
  `login_time` datetime NOT NULL,
  `login_times_per_week` smallint(6) NOT NULL DEFAULT 0,
  `be_fav_count` smallint(6) NOT NULL DEFAULT 0,
  `fav_count` smallint(6) NOT NULL DEFAULT 0,
  `tip_count` smallint(6) NOT NULL DEFAULT 0,
  `message_count` smallint(6) NOT NULL DEFAULT 0,
  `message_count_7` smallint(6) NOT NULL DEFAULT 0,
  `message_reply_count` smallint(6) NOT NULL DEFAULT 0,
  `message_reply_count_7` smallint(6) NOT NULL DEFAULT 0,
  `message_percent_7` varchar(6) DEFAULT NULL,
  `visit_other_count` smallint(6) NOT NULL DEFAULT 0,
  `visit_other_count_7` smallint(6) NOT NULL DEFAULT 0,
  `be_visit_other_count` smallint(6) NOT NULL DEFAULT 0,
  `be_visit_other_count_7` smallint(6) NOT NULL DEFAULT 0,
  `blocked_other_count` smallint(6) NOT NULL DEFAULT 0,
  `be_blocked_other_count` smallint(6) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `hide_online_data`
--
ALTER TABLE `hide_online_data`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `hide_online_data`
--
ALTER TABLE `hide_online_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;