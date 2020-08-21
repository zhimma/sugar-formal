/*
Navicat MySQL Data Transfer

Source Server         : ming.test-tw.icu
Source Server Version : 50564
Source Host           : ming.test-tw.icu:3306
Source Database       : admin_sgtest

Target Server Type    : MYSQL
Target Server Version : 50564
File Encoding         : 65001

Date: 2020-06-22 11:18:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `set_auto_ban`
-- ----------------------------
DROP TABLE IF EXISTS `set_auto_ban`;
CREATE TABLE `set_auto_ban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `content` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `set_ban` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of set_auto_ban
-- ----------------------------
INSERT INTO `set_auto_ban` VALUES ('1', 'email', 'shoyou123', '2');
INSERT INTO `set_auto_ban` VALUES ('2', 'name', '自動封鎖', '2');
INSERT INTO `set_auto_ban` VALUES ('3', 'title', '不良文字', '2');
INSERT INTO `set_auto_ban` VALUES ('4', 'about', '系統測試關於我22測試自動封鎖TEST', '2');
INSERT INTO `set_auto_ban` VALUES ('5', 'style', '不良文字2', '2');
INSERT INTO `set_auto_ban` VALUES ('8', 'email', 'test', '2');
INSERT INTO `set_auto_ban` VALUES ('15', 'style', '自動永久封鎖', '1');
INSERT INTO `set_auto_ban` VALUES ('16', 'email', '不良', '2');

ALTER TABLE `users` ADD `isReadManual` INT(1)   NOT NULL DEFAULT '0' AFTER `noticeRead`;
