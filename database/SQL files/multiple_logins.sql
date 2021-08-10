CREATE TABLE `multiple_login` ( 
    `id` INT(11) NOT NULL AUTO_INCREMENT , 
    `original_id` INT(11) NOT NULL , 
    `new_id` INT(11) NOT NULL , 
    `created_at` DATETIME NULL DEFAULT NULL , 
    `updated_at` DATETIME NULL DEFAULT NULL , PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE `multiple_login` 
RENAME TO  `multiple_logins` ;