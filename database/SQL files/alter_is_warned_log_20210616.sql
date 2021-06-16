ALTER TABLE is_warned_log
ADD column `reason` VARCHAR(255) NULL DEFAULT NULL AFTER `user_id`;
