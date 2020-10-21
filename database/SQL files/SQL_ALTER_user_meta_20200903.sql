ALTER TABLE user_meta
ADD column `isConsign` tinyint(1) NULL DEFAULT 0 COMMENT '0:default; 1:交付中',
ADD column `consign_expiry_date` timestamp NULL DEFAULT NULL,
ADD column `name_change` tinyint(1) NULL DEFAULT 0;