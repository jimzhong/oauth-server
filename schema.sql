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
  `userid` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `zjuid` CHAR(12) NOT NULL,    -- for integration with zjuam
  `username` VARCHAR(255) NULL DEFAULT NULL, -- bbs username. NULL for non staff users
  `password` VARCHAR(255) NULL DEFAULT NULL, -- not used for now
  `realname` VARCHAR(255) NULL DEFAULT NULL,
  `bbsuid` INTEGER NULL DEFAULT NULL,   -- NULL if is_staff is 0
  `email` VARCHAR(255) NULL DEFAULT NULL,
  `avatar_url` VARCHAR(2100) NULL DEFAULT NULL,
  `is_admin` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`username`),  -- MySQL can support unique key with NULL values
  UNIQUE KEY (`zjuid`),
) CHARACTER SET utf8mb4;

-- ---
-- Table 'apps'
--
-- ---

DROP TABLE IF EXISTS `apps`;

CREATE TABLE `apps` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `name` VARCHAR(512) NOT NULL,
  `vendor` VARCHAR(512) NOT NULL DEFAULT "ZJUBTV",
  `secret` VARCHAR(255) NOT NULL,
  `redirect_uri` VARCHAR(2100) NOT NULL,
  `homepage_uri` VARCHAR(2100) NULL DEFAULT NULL,
  `logo_uri` VARCHAR(2100) NULL DEFAULT NULL,
  `description` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`name`, `vendor`),
  UNIQUE KEY (`secret`),
) CHARACTER SET utf8mb4;

-- ---
-- Table 'authorization'
--
-- ---
--
-- DROP TABLE IF EXISTS `authorization`;
--
-- CREATE TABLE `authorization` (
--   `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
--   `user_id` INTEGER NULL DEFAULT NULL,
--   `app_id` INTEGER NULL DEFAULT NULL,
--   `refresh_token` CHAR(70) NOT NULL,
--   `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
--   `scope` CHAR(70) NULL DEFAULT NULL,
--   `update_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
--   PRIMARY KEY (`id`),
--   UNIQUE KEY (`user_id`, `app_id`, `scope`),
-- ) CHARACTER SET utf8mb4;


DROP TABLE IF EXISTS `scopes`;

CREATE TABLE `scopes` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `name` CHAR(255) NOT NULL,
  `description` VARCHAR(1024) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`name`),
) CHARACTER SET utf8mb4;

-- ---
-- Foreign Keys
-- ---
--
-- ALTER TABLE `authorization` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
-- ALTER TABLE `authorization` ADD FOREIGN KEY (app_id) REFERENCES `apps` (`id`);


-- ---
-- Test Data
-- ---

-- INSERT INTO `users` (`id`,`username`,`email`,`bbsuid`,`realname`,`zjuid`,`avatar_url`,`is_staff`,`is_admin`) VALUES
-- ('','','','','','','','','');
-- INSERT INTO `apps` (`id`,`name`,`publisher`,`secret`,`redirect_uri`,`homepage_uri`,`logo_uri`) VALUES
-- ('','','','','','','');
-- INSERT INTO `authorization` (`id`,`user_id`,`app_id`,`refresh_token`,`create_time`,`scope`,`update_time`) VALUES
-- ('','','','','','','');
