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
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `username` VARCHAR(256) NOT NULL,
  `email` VARCHAR(1024) NOT NULL,
  `bbsuid` INTEGER NULL DEFAULT NULL,
  `realname` VARCHAR(256) NOT NULL,
  `zjuid` CHAR(12) NOT NULL,
  `avatar_url` VARCHAR(1024) NULL DEFAULT NULL,
  `is_staff` TINYINT(1) NOT NULL DEFAULT 0,
  `is_admin` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`username`),
  UNIQUE KEY (`email`),
  UNIQUE KEY (`zjuid`),
) CHARACTER SET utf8mb4;

-- ---
-- Table 'apps'
--
-- ---

DROP TABLE IF EXISTS `apps`;

CREATE TABLE `apps` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `name` VARCHAR(1024) NOT NULL,
  `vendor` VARCHAR(256) NULL DEFAUL NULL,
  `secret` CHAR(256) NOT NULL,
  `redirect_uri` VARCHAR(1024) NOT NULL,
  `homepage_uri` VARCHAR(1024) NULL DEFAULT NULL,
  `logo_uri` VARCHAR(1024) NULL DEFAULT NULL,
  `description` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`name`),
  UNIQUE KEY (`secret`),
  UNIQUE KEY (`redirect_uri`),
) CHARACTER SET utf8mb4;

-- ---
-- Table 'authorization'
--
-- ---

DROP TABLE IF EXISTS `authorization`;

CREATE TABLE `authorization` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `user_id` INTEGER NULL DEFAULT NULL,
  `app_id` INTEGER NULL DEFAULT NULL,
  `refresh_token` CHAR(128) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `scope` CHAR(128) NULL DEFAULT NULL,
  `update_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`user_id`, `app_id`, `scope`),
) CHARACTER SET utf8mb4;

-- ---
-- Foreign Keys
-- ---

ALTER TABLE `authorization` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
ALTER TABLE `authorization` ADD FOREIGN KEY (app_id) REFERENCES `apps` (`id`);


-- ---
-- Test Data
-- ---

-- INSERT INTO `users` (`id`,`username`,`email`,`bbsuid`,`realname`,`zjuid`,`avatar_url`,`is_staff`,`is_admin`) VALUES
-- ('','','','','','','','','');
-- INSERT INTO `apps` (`id`,`name`,`publisher`,`secret`,`redirect_uri`,`homepage_uri`,`logo_uri`) VALUES
-- ('','','','','','','');
-- INSERT INTO `authorization` (`id`,`user_id`,`app_id`,`refresh_token`,`create_time`,`scope`,`update_time`) VALUES
-- ('','','','','','','');
