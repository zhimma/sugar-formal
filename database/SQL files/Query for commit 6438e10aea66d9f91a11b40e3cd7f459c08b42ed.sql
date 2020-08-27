ALTER TABLE user_meta ADD COLUMN isWarnedRead int(1) NOT NULL default 0 after isWarned;
ALTER TABLE warned_users ADD COLUMN isAdminWarnedRead int(1) NOT NULL default 0 after expire_date;