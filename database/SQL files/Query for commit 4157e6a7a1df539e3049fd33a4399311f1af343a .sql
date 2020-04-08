ALTER TABLE `fingerprint2` CHANGE `fp` `fp` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '指紋';
ALTER TABLE `banned_users_implicitly` ADD `fp` VARCHAR(100) NOT NULL AFTER `id`;
CREATE TABLE `expected_banning_users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fp` VARCHAR(100) NOT NULL ,
  `user_id` INT NOT NULL,
  `target` INT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`));