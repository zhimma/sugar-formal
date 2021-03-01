ALTER TABLE user_meta
ADD column `blurryAvatar` char(20) COLLATE utf8_unicode_ci DEFAULT NULL AFTER `isAvatarHidden`,
ADD column `blurryLifePhoto` char(20) COLLATE utf8_unicode_ci DEFAULT NULL AFTER `isAvatarHidden`;