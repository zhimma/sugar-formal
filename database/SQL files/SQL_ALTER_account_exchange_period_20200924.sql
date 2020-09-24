ALTER TABLE account_exchange_period
ADD column `before_exchange_period` tinyint(2) NULL DEFAULT 0 after exchange_period,
ADD column `reason` varchar(255) NULL DEFAULT NULL after before_exchange_period;