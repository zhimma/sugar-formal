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

-- 傾印  表格 sguser_sugarg.short_message 結構
CREATE TABLE IF NOT EXISTS `short_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `checkcode` int(11) NOT NULL,
  `createdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 正在傾印表格  sguser_sugarg.short_message 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `short_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `short_message` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
