SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `skeleton` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;

CREATE TABLE IF NOT EXISTS `skeleton`.`settings` (
  `id_settings` INT(11) NOT NULL,
  `home_title` VARCHAR(69) NOT NULL,
  `home_description` VARCHAR(156) NOT NULL,
  `home_keywords` VARCHAR(255) NOT NULL,
  `title` VARCHAR(69) NOT NULL,
  `description` VARCHAR(156) NOT NULL,
  `keywords` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id_settings`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`photo` (
  `id_photo` CHAR(32) NOT NULL,
  `active` TINYINT(4) NOT NULL,
  `title` VARCHAR(130) NOT NULL,
  `date` DATE NOT NULL,
  `is_del` TINYINT(4) NOT NULL,
  `del_date` DATETIME NULL DEFAULT NULL,
  `inc_date` DATETIME NOT NULL,
  `uri` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id_photo`),
  INDEX `titulo_idx` (`title` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`log` (
  `id_log` INT(10) NOT NULL AUTO_INCREMENT,
  `admin` VARCHAR(255) NOT NULL,
  `tbl` VARCHAR(100) NOT NULL,
  `inc_date` DATETIME NOT NULL,
  `action` CHAR(1) NOT NULL,
  `description` TEXT NOT NULL,
  `content` MEDIUMTEXT NOT NULL,
  PRIMARY KEY (`id_log`))
ENGINE = InnoDB
AUTO_INCREMENT = 341
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`news` (
  `id_news` CHAR(32) NOT NULL,
  `id_news_cat` CHAR(32) NOT NULL,
  `active` TINYINT(4) NOT NULL,
  `title` VARCHAR(130) NOT NULL,
  `date` DATE NOT NULL,
  `head` VARCHAR(380) NOT NULL,
  `body` MEDIUMTEXT NULL DEFAULT NULL,
  `inc_date` DATETIME NOT NULL,
  `del_date` DATETIME NULL DEFAULT NULL,
  `is_del` TINYINT(1) NOT NULL DEFAULT '0',
  `cover` VARCHAR(255) NULL DEFAULT NULL,
  `sequence` INT(11) NULL DEFAULT NULL,
  `uri` VARCHAR(255) NOT NULL,
  `has_tweet` TINYINT(4) NULL DEFAULT NULL,
  `has_facepost` TINYINT(4) NULL DEFAULT NULL,
  PRIMARY KEY (`id_news`),
  INDEX `fk_noticia_noticia_cat_idx` (`id_news_cat` ASC),
  CONSTRAINT `fk_news_news_cat`
    FOREIGN KEY (`id_news_cat`)
    REFERENCES `skeleton`.`news_cat` (`id_news_cat`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`news_cat` (
  `id_news_cat` CHAR(32) NOT NULL,
  `active` TINYINT(4) NOT NULL,
  `title` VARCHAR(130) NOT NULL,
  `inc_date` DATETIME NOT NULL,
  `del_date` DATETIME NULL DEFAULT NULL,
  `is_del` TINYINT(1) NOT NULL DEFAULT '0',
  `cover` VARCHAR(255) NULL DEFAULT NULL,
  `sequence` SMALLINT(6) NULL DEFAULT NULL,
  `is_home` TINYINT(4) NULL DEFAULT NULL,
  `uri` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id_news_cat`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`page` (
  `id_page` CHAR(32) NOT NULL,
  `id_page_cat` CHAR(32) NOT NULL,
  `id_parent` CHAR(32) NULL DEFAULT NULL,
  `active` TINYINT(4) NOT NULL,
  `title` VARCHAR(130) NOT NULL,
  `cover` VARCHAR(255) NULL DEFAULT NULL,
  `content` MEDIUMTEXT NULL DEFAULT NULL,
  `description` VARCHAR(156) NULL DEFAULT NULL,
  `keywords` VARCHAR(255) NULL DEFAULT NULL,
  `inc_date` DATETIME NOT NULL,
  `del_date` DATETIME NULL DEFAULT NULL,
  `is_del` TINYINT(1) NOT NULL DEFAULT '0',
  `sequence` INT(11) NULL DEFAULT NULL,
  `uri` VARCHAR(255) NOT NULL,
  `url` VARCHAR(255) NULL DEFAULT NULL,
  `target` ENUM('_self','_blank') NOT NULL,
  PRIMARY KEY (`id_page`),
  INDEX `fk_pagina_pagina_cat_idx` (`id_page_cat` ASC),
  INDEX `fk_pagina_pagina_idx` (`id_parent` ASC),
  CONSTRAINT `fk_page_page_cat`
    FOREIGN KEY (`id_page_cat`)
    REFERENCES `skeleton`.`page_cat` (`id_page_cat`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `fk_page_page`
    FOREIGN KEY (`id_parent`)
    REFERENCES `skeleton`.`page` (`id_page`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`page_cat` (
  `id_page_cat` CHAR(32) NOT NULL,
  `active` TINYINT(4) NOT NULL,
  `title` VARCHAR(130) NOT NULL,
  `cover` VARCHAR(255) NULL DEFAULT NULL,
  `content` MEDIUMTEXT NOT NULL,
  `description` VARCHAR(156) NOT NULL,
  `keywords` VARCHAR(255) NOT NULL,
  `inc_date` DATETIME NOT NULL,
  `del_date` DATETIME NULL DEFAULT NULL,
  `is_del` TINYINT(1) NOT NULL DEFAULT '0',
  `sequence` INT(11) NOT NULL,
  `uri` VARCHAR(255) NOT NULL,
  `url` VARCHAR(255) NULL DEFAULT NULL,
  `target` ENUM('_self','_blank') NOT NULL,
  PRIMARY KEY (`id_page_cat`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`admin` (
  `id_admin` CHAR(32) NOT NULL,
  `active` TINYINT(4) NOT NULL,
  `name` VARCHAR(70) NOT NULL,
  `email` VARCHAR(70) NOT NULL,
  `password` CHAR(32) NOT NULL,
  `avatar` VARCHAR(255) NULL DEFAULT NULL,
  `inc_date` DATETIME NOT NULL,
  `password_change_date` DATE NULL DEFAULT NULL,
  `permission` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_admin`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`video` (
  `id_video` CHAR(32) NOT NULL,
  `active` TINYINT(4) NOT NULL,
  `title` VARCHAR(130) NOT NULL,
  `date` DATE NOT NULL,
  `description` VARCHAR(156) NOT NULL,
  `link_youtube` VARCHAR(255) NULL DEFAULT NULL,
  `link_vimeo` VARCHAR(255) NULL DEFAULT NULL,
  `inc_date` DATETIME NOT NULL,
  `del_date` DATETIME NULL DEFAULT NULL,
  `is_del` TINYINT(1) NOT NULL DEFAULT '0',
  `uri` VARCHAR(255) NOT NULL,
  `short_link` VARCHAR(255) NULL DEFAULT NULL,
  `file` VARCHAR(200) NULL DEFAULT NULL,
  `id_youtube` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id_video`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`r_news_photo` (
  `id_r_news_photo` INT(11) NOT NULL AUTO_INCREMENT,
  `id_news` CHAR(32) NOT NULL,
  `id_photo` CHAR(32) NOT NULL,
  `sequence` INT(11) NOT NULL,
  PRIMARY KEY (`id_r_news_photo`),
  INDEX `fk_r_noticia_idx` (`id_news` ASC),
  INDEX `fk_r_foto_idx` (`id_photo` ASC),
  CONSTRAINT `fk_r_photo_news`
    FOREIGN KEY (`id_news`)
    REFERENCES `skeleton`.`news` (`id_news`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_r_photo_photo`
    FOREIGN KEY (`id_photo`)
    REFERENCES `skeleton`.`photo` (`id_photo`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`r_news_video` (
  `id_r_news_video` INT(11) NOT NULL AUTO_INCREMENT,
  `id_news` CHAR(32) NOT NULL,
  `id_video` CHAR(32) NOT NULL,
  `sequence` INT(11) NOT NULL,
  PRIMARY KEY (`id_r_news_video`),
  INDEX `fk_r_noticia_idx` (`id_news` ASC),
  INDEX `fk_r_video_idx` (`id_video` ASC),
  CONSTRAINT `fk_r_news`
    FOREIGN KEY (`id_news`)
    REFERENCES `skeleton`.`news` (`id_news`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_r_video`
    FOREIGN KEY (`id_video`)
    REFERENCES `skeleton`.`video` (`id_video`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`photo_item` (
  `id_photo_item` CHAR(32) NOT NULL,
  `id_photo` CHAR(32) NOT NULL,
  `label` VARCHAR(255) NULL DEFAULT NULL,
  `credit` VARCHAR(255) NULL DEFAULT NULL,
  `file` VARCHAR(255) NOT NULL,
  `sequence` INT(11) NOT NULL,
  PRIMARY KEY (`id_photo_item`),
  INDEX `fk_foto_item_foto_idx` (`id_photo` ASC),
  CONSTRAINT `photo_item_ibfk_1`
    FOREIGN KEY (`id_photo`)
    REFERENCES `skeleton`.`photo` (`id_photo`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`poll` (
  `id_poll` CHAR(32) NOT NULL,
  `question` VARCHAR(130) NOT NULL,
  `active` TINYINT(4) NOT NULL,
  `is_del` TINYINT(4) NOT NULL,
  `del_date` DATETIME NULL DEFAULT NULL,
  `inc_date` DATETIME NOT NULL,
  `uri` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id_poll`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`poll_option` (
  `id_poll_option` CHAR(32) NOT NULL,
  `id_poll` CHAR(32) NOT NULL,
  `option` VARCHAR(130) NOT NULL,
  `sequence` TINYINT(4) NOT NULL,
  PRIMARY KEY (`id_poll_option`),
  INDEX `fk_survey_option_survey_idx` (`id_poll` ASC),
  CONSTRAINT `fk_poll_option_poll`
    FOREIGN KEY (`id_poll`)
    REFERENCES `skeleton`.`poll` (`id_poll`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`poll_answer` (
  `id_poll_answer` CHAR(32) NOT NULL,
  `id_poll_option` CHAR(32) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_poll_answer`),
  INDEX `fk_survey_answer_survey_option_idx` (`id_poll_option` ASC),
  CONSTRAINT `fk_poll_answer_poll_option`
    FOREIGN KEY (`id_poll_option`)
    REFERENCES `skeleton`.`poll_option` (`id_poll_option`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`survey` (
  `id_survey` CHAR(32) NOT NULL,
  `title` VARCHAR(130) NOT NULL,
  `active` TINYINT(4) NOT NULL,
  `inc_date` DATETIME NOT NULL,
  `is_del` TINYINT(4) NOT NULL,
  `uri` VARCHAR(255) NOT NULL,
  `del_date` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id_survey`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`survey_question` (
  `id_survey_question` CHAR(32) NOT NULL,
  `id_survey` CHAR(32) NOT NULL,
  `question` VARCHAR(130) NOT NULL,
  `sequence` TINYINT(4) NOT NULL,
  PRIMARY KEY (`id_survey_question`),
  INDEX `fk_survey_option_survey_idx` (`id_survey` ASC),
  CONSTRAINT `fk_survey_question_survey`
    FOREIGN KEY (`id_survey`)
    REFERENCES `skeleton`.`survey` (`id_survey`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`survey_customer` (
  `id_survey_answer` CHAR(32) NOT NULL,
  `id_survey` CHAR(32) NOT NULL,
  `inc_date` DATETIME NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_survey_answer`),
  INDEX `fk_survey_answer_survey_option_idx` (`id_survey` ASC),
  CONSTRAINT `fk_survey_c_survey`
    FOREIGN KEY (`id_survey`)
    REFERENCES `skeleton`.`survey` (`id_survey`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`survey_customer_answer` (
  `id_survey_customer_answer` CHAR(32) NOT NULL,
  `id_survey_question` CHAR(32) NOT NULL,
  `id_survey_customer` CHAR(32) NOT NULL,
  `answer` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id_survey_customer_answer`),
  INDEX `fk_survey_answer_option_survey_option_idx` (`id_survey_question` ASC),
  INDEX `fk_survey_ca_survey_customer_idx` (`id_survey_customer` ASC),
  CONSTRAINT `fk_survey_ca_survey_question`
    FOREIGN KEY (`id_survey_question`)
    REFERENCES `skeleton`.`survey_question` (`id_survey_question`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `fk_survey_ca_survey_customer`
    FOREIGN KEY (`id_survey_customer`)
    REFERENCES `skeleton`.`survey_customer` (`id_survey_answer`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`tag` (
  `id_tag` CHAR(32) NOT NULL,
  `active` TINYINT(4) NOT NULL,
  `title` VARCHAR(130) NOT NULL,
  `inc_date` DATETIME NOT NULL,
  `del_date` DATETIME NULL DEFAULT NULL,
  `is_del` TINYINT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_tag`))
ENGINE = InnoDB
AUTO_INCREMENT = 341
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`r_video_tag` (
  `id_r_video_tag` INT(11) NOT NULL AUTO_INCREMENT,
  `id_video` CHAR(32) NOT NULL,
  `id_tag` CHAR(32) NOT NULL,
  `sequence` INT(11) NOT NULL,
  PRIMARY KEY (`id_r_video_tag`),
  INDEX `r_video_tag_video_idx` (`id_video` ASC),
  INDEX `r_video_tag_tag_idx` (`id_tag` ASC),
  CONSTRAINT `r_video_tag_video`
    FOREIGN KEY (`id_video`)
    REFERENCES `skeleton`.`video` (`id_video`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `r_video_tag_tag`
    FOREIGN KEY (`id_tag`)
    REFERENCES `skeleton`.`tag` (`id_tag`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`publication` (
  `id_publication` CHAR(32) NOT NULL,
  `id_issuu` CHAR(32) NULL DEFAULT NULL,
  `title` VARCHAR(130) NOT NULL,
  `file` VARCHAR(255) NULL DEFAULT NULL,
  `inc_date` DATETIME NOT NULL,
  `del_date` DATETIME NULL DEFAULT NULL,
  `is_del` TINYINT(4) NOT NULL,
  `active` TINYINT(4) NOT NULL,
  `uri` VARCHAR(255) NOT NULL,
  `has_issuu` TINYINT(4) NULL DEFAULT NULL,
  PRIMARY KEY (`id_publication`),
  INDEX `fk_publication_1_idx` (`id_issuu` ASC),
  CONSTRAINT `fk_publication_1`
    FOREIGN KEY (`id_issuu`)
    REFERENCES `skeleton`.`issuu` (`id_issuu`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`mailing_group` (
  `id_mailing_group` CHAR(32) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_mailing_group`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`mailing` (
  `id_mailing` CHAR(32) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `active` TINYINT(4) NOT NULL,
  `inc_date` DATETIME NOT NULL,
  PRIMARY KEY (`id_mailing`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`r_mailing_mailing_group` (
  `id_mailing_group` CHAR(32) NOT NULL,
  `id_mailing` CHAR(32) NOT NULL,
  `sequence` INT(11) NOT NULL,
  PRIMARY KEY (`id_mailing_group`, `id_mailing`),
  INDEX `fk_r_mg_m_idx` (`id_mailing` ASC),
  CONSTRAINT `fk_r_mg_mg`
    FOREIGN KEY (`id_mailing_group`)
    REFERENCES `skeleton`.`mailing_group` (`id_mailing_group`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_r_mg_m`
    FOREIGN KEY (`id_mailing`)
    REFERENCES `skeleton`.`mailing` (`id_mailing`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`customer` (
  `id_customer` CHAR(32) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `business_name` VARCHAR(100) NULL DEFAULT NULL,
  `document` CHAR(19) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `address_postcode` CHAR(9) NOT NULL,
  `address_street` VARCHAR(150) NOT NULL,
  `address_area` VARCHAR(30) NOT NULL,
  `address_number` VARCHAR(10) NOT NULL,
  `address_complement` VARCHAR(30) NULL DEFAULT NULL,
  `address_state` CHAR(2) NOT NULL,
  `address_city` VARCHAR(30) NOT NULL,
  `phone_ddd` CHAR(2) NOT NULL,
  `phone_number` CHAR(9) NOT NULL,
  `inc_date` DATETIME NOT NULL,
  PRIMARY KEY (`id_customer`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`tweet` (
  `id_tweet` CHAR(32) NOT NULL,
  `date` DATETIME NOT NULL,
  `msg` VARCHAR(140) NOT NULL,
  PRIMARY KEY (`id_tweet`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`r_news_tweet` (
  `id_news` CHAR(32) NOT NULL,
  `id_tweet` CHAR(32) NOT NULL,
  PRIMARY KEY (`id_news`, `id_tweet`),
  INDEX `fk_nt_tweets_idx` (`id_tweet` ASC),
  CONSTRAINT `fk_nt_news`
    FOREIGN KEY (`id_news`)
    REFERENCES `skeleton`.`news` (`id_news`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_nt_tweet`
    FOREIGN KEY (`id_tweet`)
    REFERENCES `skeleton`.`tweet` (`id_tweet`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`facepost` (
  `id_facepost` CHAR(32) NOT NULL,
  `date` DATETIME NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `link` VARCHAR(255) NOT NULL,
  `picture` VARCHAR(255) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `message` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id_facepost`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`socialmedia_credentials` (
  `id_socialmedia_credentials` INT(11) NOT NULL AUTO_INCREMENT,
  `fb_app_id` VARCHAR(255) NULL DEFAULT NULL,
  `fb_app_secret` VARCHAR(255) NULL DEFAULT NULL,
  `fb_access_token` VARCHAR(500) NULL DEFAULT NULL,
  `fb_page` VARCHAR(255) NULL DEFAULT NULL,
  `tw_consumer_key` VARCHAR(255) NULL DEFAULT NULL,
  `tw_consumer_secret` VARCHAR(255) NULL DEFAULT NULL,
  `tw_access_token` VARCHAR(255) NULL DEFAULT NULL,
  `tw_access_secret` VARCHAR(255) NULL DEFAULT NULL,
  `issuu_key` VARCHAR(255) NULL DEFAULT NULL,
  `issuu_secret` VARCHAR(255) NULL DEFAULT NULL,
  `sc_client_id` VARCHAR(255) NULL DEFAULT NULL,
  `sc_client_secret` VARCHAR(255) NULL DEFAULT NULL,
  `sc_token` VARCHAR(255) NULL DEFAULT NULL,
  `youtube_id` VARCHAR(255) NULL DEFAULT NULL,
  `youtube_secret` VARCHAR(255) NULL DEFAULT NULL,
  `youtube_token` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_socialmedia_credentials`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`r_news_facepost` (
  `id_news` CHAR(32) NOT NULL,
  `id_facepost` CHAR(32) NOT NULL,
  PRIMARY KEY (`id_news`, `id_facepost`),
  INDEX `fk_nf_facepost_idx` (`id_facepost` ASC),
  CONSTRAINT `fk_nf_news`
    FOREIGN KEY (`id_news`)
    REFERENCES `skeleton`.`news` (`id_news`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_nf_facepost`
    FOREIGN KEY (`id_facepost`)
    REFERENCES `skeleton`.`facepost` (`id_facepost`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`issuu` (
  `id_issuu` CHAR(32) NOT NULL,
  `link` VARCHAR(255) NOT NULL,
  `document_id` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id_issuu`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`audio` (
  `id_audio` CHAR(32) NOT NULL,
  `id_soundcloud` CHAR(32) NULL DEFAULT NULL,
  `active` TINYINT(1) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `date` DATETIME NOT NULL,
  `description` TEXT NOT NULL,
  `file` VARCHAR(100) NULL DEFAULT NULL,
  `inc_date` DATETIME NOT NULL,
  `del_date` DATETIME NULL DEFAULT NULL,
  `is_del` TINYINT(1) NOT NULL DEFAULT 0,
  `uri` VARCHAR(255) NOT NULL,
  `has_sc` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_audio`),
  INDEX `fk_audio_soundcloud_idx` (`id_soundcloud` ASC),
  CONSTRAINT `fk_audio_soundcloud`
    FOREIGN KEY (`id_soundcloud`)
    REFERENCES `skeleton`.`soundcloud` (`id_soundcloud`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `skeleton`.`soundcloud` (
  `id_soundcloud` CHAR(32) NOT NULL,
  `track_id` VARCHAR(250) NOT NULL,
  `track_permalink` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id_soundcloud`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
