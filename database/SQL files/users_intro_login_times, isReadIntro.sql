ALTER TABLE `users` 
ADD COLUMN `intro_login_times` INT(11) NOT NULL DEFAULT 0 AFTER `login_times`,
ADD COLUMN `isReadIntro` INT(1) NOT NULL DEFAULT 0 AFTER `isReadManual`;