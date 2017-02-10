-- ---
-- Globals
-- ---

-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- SET FOREIGN_KEY_CHECKS=0;

-- ---
-- Table 'users'
--
-- ---

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `userid` INTEGER NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `zjuid` CHAR(12) NULL,
  `avatar_url` VARCHAR(2100) NULL DEFAULT NULL,
  `phone_long` CHAR(12) NOT NULL,
  `phone_short` CHAR(7) NULL DEFAULT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY (`username`),
  UNIQUE KEY (`email`),
  UNIQUE KEY (`zjuid`)
) CHARACTER SET utf8mb4;


-- ---
-- Table 'apps'
--
-- ---

DROP TABLE IF EXISTS `apps`;

CREATE TABLE `apps` (
  `appid` INTEGER NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  `publisher` VARCHAR(256) NOT NULL,
  `secret` CHAR(256) NOT NULL,
  `homepage_uri` VARCHAR(2100) NULL DEFAULT NULL,
  `logo_uri` VARCHAR(2100) NULL DEFAULT NULL,
  PRIMARY KEY (`appid`),
  UNIQUE KEY (`name`, `publisher`)
) CHARACTER SET utf8mb4;


-- ---
-- Table 'authorization'
--
-- ---

DROP TABLE IF EXISTS `authorization`;

CREATE TABLE `authorization` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `userid` INTEGER NOT NULL,
  `appid` INTEGER NOT NULL,
  `refresh_token` CHAR(128) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scopeid` INTEGER NOT NULL,
  PRIMARY KEY (`id`)
) CHARACTER SET utf8mb4;

-- ---
-- Table 'staff'
--
-- ---

DROP TABLE IF EXISTS `staff`;

-- only admin can edit the staff table

CREATE TABLE `staff` (
  `zjuid` CHAR(12) NOT NULL,
  `name` VARCHAR(128) NOT NULL,
  `position` VARCHAR(128) NULL DEFAULT NULL,
  `department` VARCHAR(128) NULL DEFAULT NULL,
  `retired` TINYINT(1) NOT NULL DEFAULT 0,
  `bbs_uid` INTEGER NULL DEFAULT NULL,
  PRIMARY KEY (`zjuid`)
) CHARACTER SET utf8mb4;

-- ---
-- Table 'scopes'
--
-- ---

DROP TABLE IF EXISTS `scopes`;

CREATE TABLE `scopes` (
  `scopeid` INTEGER NOT NULL AUTO_INCREMENT,
  `name` CHAR(64) NOT NULL,
  `description` VARCHAR(1024) NOT NULL,
  PRIMARY KEY (`scopeid`),
  UNIQUE KEY (`name`)
) CHARACTER SET utf8mb4;

-- ---
-- Table 'redirect_uris'
--
-- ---

DROP TABLE IF EXISTS `redirections`;

CREATE TABLE `redirect_uris` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `appid` INTEGER NOT NULL,
  `redirect_uri` VARCHAR(2100) NOT NULL,
  PRIMARY KEY (`id`)
) CHARACTER SET utf8mb4;

-- ---
-- Foreign Keys
-- ---

ALTER TABLE `users` ADD FOREIGN KEY (zjuid) REFERENCES `staff` (`zjuid`);
ALTER TABLE `authorization` ADD FOREIGN KEY (userid) REFERENCES `users` (`userid`);
ALTER TABLE `authorization` ADD FOREIGN KEY (appid) REFERENCES `apps` (`appid`);
ALTER TABLE `authorization` ADD FOREIGN KEY (scopeid) REFERENCES `scopes` (`scopeid`);
ALTER TABLE `redirections` ADD FOREIGN KEY (appid) REFERENCES `apps` (`appid`);

-- ---
-- Test Data
-- ---

-- INSERT INTO `users` (`userid`,`username`,`email`,`password`,`zjuid`,`avatar_url`,`phone_long`,`phone_short`) VALUES
-- ('','','','','','','','');
-- INSERT INTO `apps` (`appid`,`name`,`publisher`,`secret`,`homepage_uri`,`logo_uri`) VALUES
-- ('','','','','','');
-- INSERT INTO `authorization` (`id`,`userid`,`appid`,`refresh_token`,`create_time`,`update_time`,`scopeid`) VALUES
-- ('','','','','','','');
-- INSERT INTO `staff` (`zjuid`,`name`,`position`,`department`,`retired`,`bbs_uid`) VALUES
-- ('','','','','','');
-- INSERT INTO `scopes` (`scopeid`,`name`,`description`) VALUES
-- ('','','');
-- INSERT INTO `redirect_uris` (`id`,`appid`,`redirect_uri`) VALUES
-- ('','','');
