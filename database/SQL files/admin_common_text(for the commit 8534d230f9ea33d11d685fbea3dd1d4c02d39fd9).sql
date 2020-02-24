/*
Navicat MySQL Data Transfer

Source Server         : SG
Source Server Version : 50564
Source Host           : 139.162.121.102:3306
Source Database       : admin_sgtest

Target Server Type    : MYSQL
Target Server Version : 50564
File Encoding         : 65001

Date: 2020-02-18 09:13:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `admin_common_text`
-- ----------------------------
DROP TABLE IF EXISTS `admin_common_text`;
CREATE TABLE `admin_common_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(3) NOT NULL DEFAULT '1',
  `title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `content` varchar(500) CHARACTER SET utf8 NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of admin_common_text
-- ----------------------------
INSERT INTO `admin_common_text` VALUES ('1', '1', '男-車馬費信件', '系統通知: 車馬費邀請<br>您已經向 NAME 發動車馬費邀請。<br>流程如下<br>1:網站上進行車馬費邀請<br>2:網站上訊息約見(重要，站方判斷約見時間地點，以網站留存訊息為準)<br>3:雙方見面<br><br>如果雙方在第二步就約見失敗。<br>將扣除手續費 288 元後，1500匯入您指定的帳戶。也可以用現金袋或者西聯匯款方式進行。<br>(聯繫我們有站方聯絡方式)<br><br>若雙方有見面意願，被女方放鴿子。<br>站方會參照女方提出的證據，判斷是否將尾款交付女方。', '2020-02-18 19:47:00', '2020-02-17 22:13:03');
INSERT INTO `admin_common_text` VALUES ('2', '1', '女-車馬費信件', '系統通知: 車馬費邀請<br> NAME 已經向 您 發動車馬費邀請。<br>流程如下<br>1:網站上進行車馬費邀請<br>2:網站上訊息約見(重要，站方判斷約見時間地點，以網站留存訊息為準)<br>3:雙方見面(建議約在知名連鎖店丹堤星巴克或者麥當勞之類)<br><br>若成功見面男方沒有提出異議，那站方會在發動後 7~14 個工作天<br>將 1500 匯入您指定的帳戶。若您不想提供銀行帳戶。<br>也可以用現金袋或者西聯匯款方式進行。<br>(聯繫我們有站方聯絡方式)<br><br>若男方提出當天女方未到場的爭議。請您提出當天消費的發票證明之。<br>所以請約在知名連鎖店以利站方驗證。', '2020-02-18 19:47:00', '2020-02-17 16:40:38');
INSERT INTO `admin_common_text` VALUES ('3', '1', '車馬費說明popup', '<span>這筆費用是用來向女方表達見面的誠意<br></span><span><br>●若約見順利<br>站方在扣除 288 手續費，交付 1500 與女方。<br></span><span><br>●若有爭議(例如放鴿子)<br>站方將依女方提供的證明資料，決定是否交付款項與女方。<br></span><span><br>●爭議處理<br>若女方提出證明文件，則交付款項予女方。<br>若女方於於約見日五日內未提出相關證明文件。<br>將扣除手續費後匯回男方指定帳戶。<br></span><span><br>注意：此費用一經匯出，即全權交由本站裁決處置。<br>本人絕無異議，若不同意請按取消鍵返回。</span>', '2020-02-18 05:37:29', '2020-02-17 16:55:45');
INSERT INTO `admin_common_text` VALUES ('4', '1', '取消VIP', '您已成功取消VIP付款，下個月起將不再繼續扣款，目前的VIP權限可以維持到 DATE', '2020-02-18 07:33:08', '2020-02-15 13:38:16');
INSERT INTO `admin_common_text` VALUES ('5', '1', '封鎖說明popup', '<img src=\"/new/images/iconff_03.png\"><span>對方不會知道您封鎖他 </span>\r\n<img src=\"/new/images/iconff_06.png\"><span>會將對方顯示為退會的用戶</span>\r\n<img src=\"/new/images/iconff_08.png\"><span>可從設定頁面的[已封鎖用戶名單]中解除</span>', '2020-02-18 09:25:11', '2020-02-15 15:28:55');
INSERT INTO `admin_common_text` VALUES ('6', '0', '車馬費說明popup', '<span>這筆費用是用來向女方表達見面的誠意<br></span>\r\n<span><br>●若約見順利\r\n<br>站方在扣除 288 手續費，交付 1500 與女方。<br></span><span>\r\n<br>●若有爭議(例如放鴿子)\r\n<br>站方將依女方提供的證明資料，決定是否交付款項與女方。<br></span><span>\r\n<br>●爭議處理\r\n<br>若女方提出證明文件，則交付款項予女方。\r\n<br>若女方於於約見日五日內未提出相關證明文件。\r\n<br>將扣除手續費後匯回男方指定帳戶。<br></span>\r\n<span><br>注意：此費用一經匯出，即全權交由本站裁決處置。\r\n<br>本人絕無異議，若不同意請按取消鍵返回。</span>', '2020-02-13 08:03:19', '2020-02-13 16:03:40');
INSERT INTO `admin_common_text` VALUES ('7', '0', '封鎖說明', '<img src=\"/new/images/iconff_03.png\"><span>對方不會知道您封鎖他 </span>\r\n<img src=\"/new/images/iconff_06.png\"><span>會將對方顯示為退會的用戶</span>\r\n<img src=\"/new/images/iconff_08.png\"><span>可從設定頁面的[已封鎖用戶名單]中解除</span>', '2020-02-13 08:03:57', '2020-02-13 16:04:04');
INSERT INTO `admin_common_text` VALUES ('8', '0', '男-車馬費信件', '系統通知: 車馬費邀請\r\n您已經向 NAME 發動車馬費邀請。\r\n流程如下\r\n1:網站上進行車馬費邀請\r\n2:網站上訊息約見(重要，站方判斷約見時間地點，以網站留存訊息為準)\r\n3:雙方見面\r\n\r\n如果雙方在第二步就約見失敗。\r\n將扣除手續費 288 元後，1500匯入您指定的帳戶。也可以用現金袋或者西聯匯款方式進行。\r\n(聯繫我們有站方聯絡方式)\r\n\r\n若雙方有見面意願，被女方放鴿子。\r\n站方會參照女方提出的證據，判斷是否將尾款交付女方。', '2020-02-14 09:29:57', '2020-02-14 17:31:33');
INSERT INTO `admin_common_text` VALUES ('9', '0', '女-車馬費信件', '系統通知: 車馬費邀請\r\n NAME 已經向 您 發動車馬費邀請。\r\n流程如下\r\n1:網站上進行車馬費邀請\r\n2:網站上訊息約見(重要，站方判斷約見時間地點，以網站留存訊息為準)\r\n3:雙方見面(建議約在知名連鎖店丹堤星巴克或者麥當勞之類)\r\n\r\n若成功見面男方沒有提出異議，那站方會在發動後 7~14 個工作天\r\n將 1500 匯入您指定的帳戶。若您不想提供銀行帳戶。\r\n也可以用現金袋或者西聯匯款方式進行。\r\n(聯繫我們有站方聯絡方式)\r\n\r\n若男方提出當天女方未到場的爭議。請您提出當天消費的發票證明之。\r\n所以請約在知名連鎖店以利站方驗證。', '2020-02-14 09:30:25', '2020-02-14 17:31:35');
INSERT INTO `admin_common_text` VALUES ('10', '0', '取消VIP', '您已成功取消VIP付款，下個月起將不再繼續扣款，目前的VIP權限可以維持到 DATE', '2020-02-14 09:30:47', '2020-02-14 17:31:37');
