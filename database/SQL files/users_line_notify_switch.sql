ALTER TABLE `users` ADD `line_notify_switch` TINYINT(1) NOT NULL DEFAULT '1' AFTER `line_notify_token`;