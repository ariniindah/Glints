-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.18-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.3.0.6369
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for glints
CREATE DATABASE IF NOT EXISTS `glints` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `glints`;

-- Dumping structure for procedure glints.GetAllProducts
DELIMITER //
CREATE PROCEDURE `GetAllProducts`()
BEGIN
	SELECT *  FROM restaurant_master LIMIT 1;
END//
DELIMITER ;

-- Dumping structure for table glints.keys
CREATE TABLE IF NOT EXISTS `keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT 0,
  `is_private_key` tinyint(1) NOT NULL DEFAULT 0,
  `ip_addresses` text DEFAULT NULL,
  `date_created` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

-- Dumping structure for procedure glints.ProcessUserPurchasing
DELIMITER //
CREATE PROCEDURE `ProcessUserPurchasing`(
	IN userid bigint
)
BEGIN
	SELECT * 
 	FROM purchasehistory
	WHERE id_user = userid;
END//
DELIMITER ;

-- Dumping structure for procedure glints.ProcessUserPurchasing2
DELIMITER //
CREATE PROCEDURE `ProcessUserPurchasing2`(
	IN userid bigint
)
BEGIN
	SELECT * 
 	FROM purchasehistory
	WHERE id_user = userid;
END//
DELIMITER ;

-- Dumping structure for procedure glints.ProcessUserPurchasing3
DELIMITER //
CREATE PROCEDURE `ProcessUserPurchasing3`(
	IN userid bigint
)
BEGIN

SELECT @sumtrxamt := SUM(transactionAmount)
 	FROM purchasehistory
	WHERE id_user = userid;
SELECT @sumtrxamt;

END//
DELIMITER ;

-- Dumping structure for procedure glints.ProcessUserPurchasing4
DELIMITER //
CREATE PROCEDURE `ProcessUserPurchasing4`(
	IN userid bigint
)
BEGIN

SELECT @sumtrxamt := SUM(transactionAmount)
 	FROM purchasehistory
	WHERE id_user = userid;
-- SELECT @sumtrxamt;

UPDATE user_master SET cashBalance=cashBalance-@sumtrxamt WHERE id_user = userid; 

END//
DELIMITER ;

-- Dumping structure for procedure glints.ProcessUserPurchasing5
DELIMITER //
CREATE PROCEDURE `ProcessUserPurchasing5`(
	IN userid bigint
)
BEGIN

/*calculate total purchase amount per user given*/
SELECT @sumtrxamt := SUM(transactionAmount)
 	FROM purchasehistory
	WHERE id_user = userid;

/*deduct cashBalance for user id given*/ 
UPDATE user_master SET cashBalance=cashBalance-@sumtrxamt WHERE id_user = userid; 

/*update flag isprocessed to all transaction from user id given */ 
UPDATE purchasehistory SET isprocessed=TRUE WHERE id_user = userid;

/*add restaurant's cash balance */
update restaurant_master b
RIGHT OUTER JOIN purchasehistory a ON a.restaurantName=b.restaurantName
set b.cashBalance=b.cashBalance-a.transactionAmount
WHERE a.id_user=userid;

END//
DELIMITER ;

-- Dumping structure for table glints.purchasehistory
CREATE TABLE IF NOT EXISTS `purchasehistory` (
  `id_user` int(11) DEFAULT NULL,
  `dishName` varchar(250) NOT NULL DEFAULT '',
  `restaurantName` varchar(250) NOT NULL DEFAULT '',
  `transactionAmount` decimal(20,6) NOT NULL DEFAULT 0.000000,
  `transactionDate` varchar(50) NOT NULL DEFAULT '',
  `isprocessed` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

-- Dumping structure for table glints.restaurant_master
CREATE TABLE IF NOT EXISTS `restaurant_master` (
  `restaurant_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `restaurantName` varchar(500) DEFAULT '0',
  `cashBalance` decimal(20,6) DEFAULT 0.000000,
  `openingHours` varchar(500) DEFAULT '0',
  PRIMARY KEY (`restaurant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2204 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table glints.restaurant_menu
CREATE TABLE IF NOT EXISTS `restaurant_menu` (
  `restaurant_id` bigint(20) DEFAULT NULL,
  `dishName` varchar(500) DEFAULT '0',
  `price` decimal(20,6) DEFAULT 0.000000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

-- Dumping structure for table glints.user_master
CREATE TABLE IF NOT EXISTS `user_master` (
  `id_user` int(11) NOT NULL,
  `name` varchar(250) NOT NULL DEFAULT '',
  `cashBalance` decimal(20,6) NOT NULL DEFAULT 0.000000,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
