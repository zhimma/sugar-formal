ALTER TABLE `users` ADD `prohibit_posts` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '禁止發言' AFTER `accountStatus`, ADD `access_posts` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '封鎖進入討論區' AFTER `prohibit_posts`;
