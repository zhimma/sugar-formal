/*
Navicat MySQL Data Transfer

Source Server         : ming.test-tw.icu
Source Server Version : 50564
Source Host           : ming.test-tw.icu:3306
Source Database       : admin_sgtest

Target Server Type    : MYSQL
Target Server Version : 50564
File Encoding         : 65001

Date: 2020-02-24 13:42:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `reason_list`
-- ----------------------------
DROP TABLE IF EXISTS `reason_list`;
CREATE TABLE `reason_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(3) NOT NULL DEFAULT '1',
  `type` varchar(100) CHARACTER SET utf8 NOT NULL,
  `content` varchar(500) CHARACTER SET utf8 NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of reason_list
-- ----------------------------
INSERT INTO `reason_list` VALUES ('1', '1', 'ban', '廣告', '2020-02-18 15:48:31', '2020-02-18 15:48:33');
INSERT INTO `reason_list` VALUES ('2', '1', 'ban', '非徵求包養行為', '2020-02-18 07:48:51', '2020-02-18 15:49:09');
INSERT INTO `reason_list` VALUES ('3', '1', 'ban', '用詞不當', '2020-02-18 07:48:56', '2020-02-18 15:49:13');
INSERT INTO `reason_list` VALUES ('4', '1', 'ban', '照片不當', '2020-02-18 07:48:58', '2020-02-18 15:49:16');
INSERT INTO `reason_list` VALUES ('5', '1', 'pic', '非人物照片', '2020-02-18 07:50:13', '2020-02-18 15:50:37');
INSERT INTO `reason_list` VALUES ('6', '1', 'pic', '盜用圖片', '2020-02-18 07:50:19', '2020-02-18 15:50:40');
INSERT INTO `reason_list` VALUES ('7', '1', 'pic', '非本人', '2020-02-18 07:50:26', '2020-02-18 15:50:42');
INSERT INTO `reason_list` VALUES ('8', '1', 'pic', '不雅照', '2020-02-18 07:50:27', '2020-02-18 15:50:45');
