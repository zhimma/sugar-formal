ALTER TABLE reported ADD COLUMN cancel tinyint(1) NOT NULL default 0 after content;
ALTER TABLE reported_pic ADD COLUMN cancel tinyint(1) NOT NULL default 0 after content;
ALTER TABLE reported_avatar ADD COLUMN cancel tinyint(1) NOT NULL default 0 after content;
ALTER TABLE message ADD COLUMN cancel tinyint(1) NOT NULL default 0 after isReported;