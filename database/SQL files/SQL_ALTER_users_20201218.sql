ALTER TABLE users
ADD column `is_hide_online` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0:關; 1:開',
ADD column `hide_online_time` timestamp NULL DEFAULT NULL;