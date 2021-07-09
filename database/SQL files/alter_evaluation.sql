ALTER TABLE `evaluation` ADD `admin_comment` TEXT NULL AFTER `is_check`;
ALTER TABLE `evaluation` ADD `deleted_at` TIMESTAMP NULL AFTER `updated_at`;