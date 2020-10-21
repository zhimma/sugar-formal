ALTER TABLE account_gender_change
ADD column `before_change_gender` tinyint(1) NULL DEFAULT 0 after change_gender,
ADD column `reason` varchar(255) NULL DEFAULT NULL after before_change_gender;