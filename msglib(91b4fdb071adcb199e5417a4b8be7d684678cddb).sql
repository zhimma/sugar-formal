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

-- 傾印  表格 sguser_sugarg.msglib 結構
CREATE TABLE IF NOT EXISTS `msglib` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `msg` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `kind` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 正在傾印表格  sguser_sugarg.msglib 的資料：~1 rows (大約)
/*!40000 ALTER TABLE `msglib` DISABLE KEYS */;
REPLACE INTO `msglib` (`id`, `title`, `msg`, `kind`) VALUES
	(1, '檢舉確認', '|$report|檢舉|$reported|，經站長判別沒有問題。', 'MSG');
/*!40000 ALTER TABLE `msglib` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
