SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `ttweibo` ;
CREATE SCHEMA IF NOT EXISTS `ttweibo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `ttweibo` ;

-- -----------------------------------------------------
-- Table `ttweibo`.`tt_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ttweibo`.`tt_user` ;

CREATE TABLE IF NOT EXISTS `ttweibo`.`tt_user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `account` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '账号',
  `password` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '密码',
  `registime` INT(10) NOT NULL COMMENT '注册时间',
  `lock` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '用户是否锁定(是 1 否 0)',
  PRIMARY KEY (`id`),
  INDEX `account` (`account` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = '用户基本信息表';


-- -----------------------------------------------------
-- Table `ttweibo`.`tt_userinfo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ttweibo`.`tt_userinfo` ;

CREATE TABLE IF NOT EXISTS `ttweibo`.`tt_userinfo` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '用户昵称',
  `truename` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '用户真实姓名',
  `sex` ENUM('男','女') CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '男' COMMENT '性别',
  `location` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '所在地',
  `mobile` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '手机号码',
  `email` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '用户注册邮箱 用于找回密码',
  `constellation` VARCHAR(10) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '星座',
  `intro` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '用户自我简介',
  `face50` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '50*50头像',
  `face80` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '80*80头像',
  `face180` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '180*180头像',
  `follow` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '关注数',
  `fans` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '粉丝数',
  `weibo` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '微博数',
  `uid` INT NOT NULL COMMENT '所属用户id',
  PRIMARY KEY (`id`),
  INDEX `uid` (`uid` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_icelandic_ci
COMMENT = '用户详细信息表';


-- -----------------------------------------------------
-- Table `ttweibo`.`tt_article`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ttweibo`.`tt_article` ;

CREATE TABLE IF NOT EXISTS `ttweibo`.`tt_article` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `content` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '微博内容',
  `isturn` INT NOT NULL DEFAULT 0 COMMENT '微博类型 是否是转发( 转发 1  原创 0)',
  `postime` INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发表时间',
  `turn` INT NOT NULL DEFAULT 0 COMMENT '转发数',
  `keep` INT NOT NULL DEFAULT 0 COMMENT '收藏数',
  `comment` INT NOT NULL DEFAULT 0 COMMENT '评论次数',
  `uid` INT NOT NULL COMMENT '发表用户id',
  PRIMARY KEY (`id`),
  INDEX `uid` (`uid` ASC))
ENGINE = MyISAM
COMMENT = '博客文章表';


-- -----------------------------------------------------
-- Table `ttweibo`.`tt_comment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ttweibo`.`tt_comment` ;

CREATE TABLE IF NOT EXISTS `ttweibo`.`tt_comment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `content` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '评论内容',
  `postime` INT(11) UNSIGNED NOT NULL COMMENT '发表时间',
  `uid` INT UNSIGNED NOT NULL COMMENT '用户id',
  `aid` INT NOT NULL COMMENT '对应微博id',
  PRIMARY KEY (`id`),
  INDEX `aid` (`aid` ASC),
  INDEX `uid` (`uid` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = '微博评论表';


-- -----------------------------------------------------
-- Table `ttweibo`.`tt_file`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ttweibo`.`tt_file` ;

CREATE TABLE IF NOT EXISTS `ttweibo`.`tt_file` (
  `id` INT NOT NULL,
  `type` ENUM('0','1','2') CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '0' COMMENT '媒体文件类型  （ 图片 0  音频 1  视频 2）  ',
  `picture50` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '50*50的图片的url',
  `picture80` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '80*80的图片url',
  `picture180` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '180*180的图片url',
  `audio` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '音频的url',
  `video` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '视频的url',
  `aid` INT NOT NULL COMMENT '对应微博id',
  PRIMARY KEY (`id`),
  INDEX `aid` (`aid` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = '微博配的媒体文件表';


-- -----------------------------------------------------
-- Table `ttweibo`.`tt_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ttweibo`.`tt_group` ;

CREATE TABLE IF NOT EXISTS `ttweibo`.`tt_group` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' COMMENT '分组名称',
  `uid` INT NOT NULL COMMENT '对应用户id',
  PRIMARY KEY (`id`),
  INDEX `uid` (`uid` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = '用户分组表';


-- -----------------------------------------------------
-- Table `ttweibo`.`tt_follow`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ttweibo`.`tt_follow` ;

CREATE TABLE IF NOT EXISTS `ttweibo`.`tt_follow` (
  `follow` INT NOT NULL AUTO_INCREMENT,
  `fans` INT NOT NULL COMMENT '粉丝',
  `tt_group_id` INT NOT NULL,
  PRIMARY KEY (`follow`),
  INDEX `fk_tt_follow_tt_group1_idx` (`tt_group_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = '用户关注表';


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
