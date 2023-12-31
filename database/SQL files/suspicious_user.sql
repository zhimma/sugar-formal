--
-- 資料表結構 `suspicious_user`
--

CREATE TABLE `suspicious_user` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `suspicious_user`
  ADD PRIMARY KEY (`id`);
  
ALTER TABLE `suspicious_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;