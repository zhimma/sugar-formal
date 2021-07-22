ALTER TABLE `users` ADD `account_status_admin` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '站方關閉帳號 0:關閉 1:開啟(預設)' AFTER `accountStatus`
