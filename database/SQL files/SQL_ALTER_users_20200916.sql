ALTER TABLE users
ADD column `exchange_period` tinyint(2) NOT NULL DEFAULT 2,
ADD column `login_times` int(11) NOT NULL DEFAULT 0;