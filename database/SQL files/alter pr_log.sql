ALTER TABLE pr_log
ADD column `pr` varchar(30) NULL DEFAULT NULL AFTER `user_id`,
ADD column `active` tinyint(1) NULL DEFAULT 0 AFTER `pr_log`;