ALTER TABLE user_meta
ADD column `isWarnedTime` timestamp NULL DEFAULT NULL AFTER `isWarnedRead`;