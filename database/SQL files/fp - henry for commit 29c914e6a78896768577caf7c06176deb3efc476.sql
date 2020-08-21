-- --------------------------------------------------------
-- 主機:                           127.0.0.1
-- 伺服器版本:                        10.1.37-MariaDB - mariadb.org binary distribution
-- 伺服器操作系統:                      Win32
-- HeidiSQL 版本:                  9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 傾印  表格 sguser_sugarg.fingerprint 結構
CREATE TABLE IF NOT EXISTS `fingerprint2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `fp` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '指紋',
  `userAgent` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '使用者代理',
  `webdriver` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'webdriver',
  `language` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '語言',
  `colorDepth` int(11) NOT NULL COMMENT '顏色深度',
  `deviceMemory` int(11) NOT NULL COMMENT '裝置記憶體',
  `pixelRatio` int(11) NOT NULL COMMENT '像素比率',
  `hardwareConcurrency` int(11) NOT NULL COMMENT '硬體同時執行',
  `screenResolution` varchar(1000) COLLATE utf8_unicode_ci NOT NULL COMMENT '螢幕解析度',
  `availableScreenResolution` varchar(1000) COLLATE utf8_unicode_ci NOT NULL COMMENT '允許的螢幕解析度',
  `timezoneOffset` int(11) NOT NULL COMMENT '時區偏移',
  `timezone` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '時區',
  `sessionStorage` tinyint(1) NOT NULL COMMENT 'session儲存',
  `localStorage` tinyint(1) NOT NULL COMMENT 'local儲存',
  `indexedDb` tinyint(1) NOT NULL COMMENT '索引的資料庫',
  `addBehavior` tinyint(1) NOT NULL COMMENT '新增行為',
  `openDatabase` tinyint(1) NOT NULL COMMENT '開啟資料庫',
  `cpuClass` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'CPU等級',
  `platform` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '平台',
  `doNotTrack` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `plugins` varchar(5000) COLLATE utf8_unicode_ci NOT NULL COMMENT '外掛',
  `canvas` TEXT(50000) COLLATE utf8_unicode_ci NOT NULL COMMENT 'canvas',
  `webgl` TEXT(20000) COLLATE utf8_unicode_ci NOT NULL COMMENT 'webgl',
  `webglVendorAndRenderer` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'webgl庫&渲染器',
  `adBlock` tinyint(1) NOT NULL COMMENT '廣告阻擋器',
  `hasLiedLanguages` tinyint(1) NOT NULL,
  `hasLiedResolution` tinyint(1) NOT NULL,
  `hasLiedOs` tinyint(1) NOT NULL,
  `hasLiedBrowser` tinyint(1) NOT NULL,
  `touchSupport` varchar(300) COLLATE utf8_unicode_ci NOT NULL COMMENT '觸控支援',
  `fonts` varchar(1500) COLLATE utf8_unicode_ci NOT NULL COMMENT '字體',
  `fontsFlash` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '字體快閃',
  `audio` float NOT NULL COMMENT '音頻',
  `enumerateDevices` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '列舉設備',
  `batterylevel` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 正在傾印表格  sguser_sugarg.fingerprint 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `fingerprint2` DISABLE KEYS */;
/*!40000 ALTER TABLE `fingerprint2` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
