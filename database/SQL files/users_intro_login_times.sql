ALTER TABLE `sugar_garden`.`users` 
ADD COLUMN `intro_login_times` INT(11) NOT NULL DEFAULT 0 AFTER `login_times`;