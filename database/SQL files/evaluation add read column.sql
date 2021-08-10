ALTER TABLE `evaluation` 
ADD COLUMN `read` TINYINT(1) NULL DEFAULT 0 AFTER `re_content`;