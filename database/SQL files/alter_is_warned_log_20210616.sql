ALTER TABLE is_warned_log
ADD column `isWarnedTime` timestamp NULL DEFAULT NULL AFTER `isWarnedRead`;