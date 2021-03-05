ALTER TABLE users
ADD column `line_notify_auth_code` varchar (255) NULL DEFAULT NULL,
ADD column `line_notify_token` varchar (255) NULL DEFAULT NULL;