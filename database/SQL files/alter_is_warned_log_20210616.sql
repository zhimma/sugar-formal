ALTER TABLE is_warned_log
ADD column `reason` varchar(255) DEFAULT NULL AFTER `user_id`;