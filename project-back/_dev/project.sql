/*
 Navicat Premium Data Transfer

 Source Server         : # LOCALHOST #
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : localhost:3306
 Source Schema         : Project

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 26/11/2024 09:56:28
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for clients
-- ----------------------------
DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'the client id',
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Full name',
  `email` varchar(320) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Email address',
  `password` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Encrypted password',
  `emailCheck` enum('y','n') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'n' COMMENT 'if the email was confirmed: (y) yes, (n) no.',
  `status` enum('a','i','p','x') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Status: (a) active, (i) inactive, (p) pending, (x) excluded.',
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Registration date.',
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp of the most recent update to any record in this table.',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id_UNIQUE`(`id` ASC) USING BTREE,
  UNIQUE INDEX `email_UNIQUE`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = 'client basic informations' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for contacts
-- ----------------------------
DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'the main id',
  `client` int UNSIGNED NOT NULL COMMENT 'Client id from clients table.',
  `phone` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'The phone number of the client.',
  `status` enum('a','i','x') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'a' COMMENT 'Contact status: (a) active, (i) inactive, (x) excluded.',
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Registration date.',
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp of the most recent update to any record in this table.',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id_UNIQUE`(`id` ASC) USING BTREE,
  INDEX `contacts_client_fk_idx`(`client` ASC) USING BTREE,
  INDEX `contacts_status_idx`(`status` ASC) USING BTREE,
  CONSTRAINT `contacts_clients_fk` FOREIGN KEY (`client`) REFERENCES `clients` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = 'The client contacts' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Procedure structure for ClientCreate
-- ----------------------------
DROP PROCEDURE IF EXISTS `ClientCreate`;
delimiter ;;
CREATE PROCEDURE `ClientCreate`(IN clientName VARCHAR(100),
    IN clientEmail VARCHAR(320),
    IN clientHashedPassword VARCHAR(250),
    IN clientEmailCheck CHAR(1),
    IN clientStatus CHAR(1),
    OUT lastInsertId INT)
BEGIN
    INSERT INTO clients (name, email, password, emailCheck, status, createdAt, updatedAt)
    VALUES (clientName, clientEmail, clientHashedPassword, clientEmailCheck, clientStatus, NOW(), NOW());

    SET lastInsertId = LAST_INSERT_ID();
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for ContactCreate
-- ----------------------------
DROP PROCEDURE IF EXISTS `ContactCreate`;
delimiter ;;
CREATE PROCEDURE `ContactCreate`(IN clientId INT,
    IN clientPhone VARCHAR(20),
    IN clientStatus CHAR(1))
BEGIN
    INSERT INTO contacts (client, phone, status, createdAt, updatedAt)
    VALUES (clientId, clientPhone, clientStatus, NOW(), NOW());
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
