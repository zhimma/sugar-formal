ALTER TABLE fingerprint2
add `mac_address` varchar(20) NOT NULL DEFAULT '' COMMENT 'MAC ADDRESS' after batterylevel,
add `uniqueVisitorId` varchar(20) NOT NULL DEFAULT '' COMMENT 'COOKIE UUID' after mac_address;
