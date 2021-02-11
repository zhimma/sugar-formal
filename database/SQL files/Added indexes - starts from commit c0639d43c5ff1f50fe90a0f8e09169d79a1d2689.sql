ALTER TABLE `member_fav` ADD INDEX(`member_fav_id`);
ALTER TABLE `blocked` ADD INDEX(`blocked_id`);
ALTER TABLE `member_fav` DROP INDEX `member_id`;
ALTER TABLE `member_fav` ADD INDEX(`member_id`);
ALTER TABLE `user_meta` ADD INDEX(`isWarned`);
ALTER TABLE `short_message` ADD INDEX(`member_id`);