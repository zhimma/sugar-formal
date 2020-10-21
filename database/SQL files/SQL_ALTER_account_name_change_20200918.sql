ALTER TABLE account_name_change
ADD column `before_change_name` varchar(255) NULL DEFAULT NULL after change_name,
ADD column `reason` varchar(255) NULL DEFAULT NULL after before_change_name;