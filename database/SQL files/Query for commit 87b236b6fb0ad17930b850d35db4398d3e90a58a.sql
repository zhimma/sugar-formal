CREATE TABLE `banned_users_implicitly` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `target` INT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`));

CREATE TABLE `warning_users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `target` INT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`));

ALTER TABLE `fingerprint2` 
CHANGE COLUMN `canvas` `canvas` TEXT(50000) NOT NULL COMMENT 'canvas',
CHANGE COLUMN `webgl` `webgl` TEXT(20000) NOT NULL COMMENT 'webgl', 
CHANGE COLUMN `userAgent` `userAgent` VARCHAR(200) NULL DEFAULT NULL COMMENT '使用者代理' ;
