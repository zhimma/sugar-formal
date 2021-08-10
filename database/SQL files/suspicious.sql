/*
 Navicat Premium Data Transfer

 Source Server         : chen.test-tw.icu
 Source Server Type    : MySQL
 Source Server Version : 50564
 Source Host           : chen.test-tw.icu:3306
 Source Schema         : admin_sgtest

 Target Server Type    : MySQL
 Target Server Version : 50564
 File Encoding         : 65001

 Date: 22/04/2021 11:22:49
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for suspicious
-- ----------------------------
DROP TABLE IF EXISTS `suspicious`;
CREATE TABLE `suspicious` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `account_text` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
