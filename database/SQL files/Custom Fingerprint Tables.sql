CREATE TABLE `custom_fingerprint` ( 
    `id` INT(11) NOT NULL AUTO_INCREMENT , 
    `hash` VARCHAR(255) NOT NULL , 
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP , 
    `updated_at` TIMESTAMP NULL DEFAULT NULL , 
    PRIMARY KEY (`id`)
    ) ENGINE = InnoDB;

CREATE TABLE `cfp_user` ( 
    `id` INT(11) NOT NULL AUTO_INCREMENT , 
    `cfp_id` INT(11) NOT NULL , 
    `user_id` INT(11) NOT NULL , 
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP , 
    `updated_at` TIMESTAMP NULL DEFAULT NULL , PRIMARY KEY (`id`)
    ) ENGINE = InnoDB;