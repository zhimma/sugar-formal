ALTER TABLE user_meta
ADD column `blurryAvatar` char(20) COLLATE utf8_unicode_ci DEFAULT 'general,' AFTER `isAvatarHidden`,
ADD column `blurryLifePhoto` char(20) COLLATE utf8_unicode_ci DEFAULT 'general,' AFTER `isAvatarHidden`;