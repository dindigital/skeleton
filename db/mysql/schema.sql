-- MySQL dump 10.13  Distrib 5.5.35, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: skeleton
-- ------------------------------------------------------
-- Server version	5.5.35-0ubuntu0.12.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id_admin` char(32) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `name` varchar(70) NOT NULL,
  `email` varchar(70) NOT NULL,
  `password` char(32) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `inc_date` datetime NOT NULL,
  `password_change_date` date DEFAULT NULL,
  `permission` text,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES ('b9211c2ba990c3efab12df5e71e4a359',1,'Super Administrador','suporte@dindigital.com','879138abe0bb3979bbb7aa7d861faa86','/system/uploads/admin/1/avatar/dindigital-logotipo-jpg.jpg','2014-01-24 17:47:22',NULL,NULL);
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audio`
--

DROP TABLE IF EXISTS `audio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audio` (
  `id_audio` char(32) NOT NULL,
  `id_soundcloud` char(32) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `description` text NOT NULL,
  `file` varchar(100) DEFAULT NULL,
  `inc_date` datetime NOT NULL,
  `del_date` datetime DEFAULT NULL,
  `is_del` tinyint(1) NOT NULL DEFAULT '0',
  `uri` varchar(255) NOT NULL,
  `has_sc` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_audio`),
  KEY `fk_audio_soundcloud_idx` (`id_soundcloud`),
  CONSTRAINT `fk_audio_soundcloud` FOREIGN KEY (`id_soundcloud`) REFERENCES `soundcloud` (`id_soundcloud`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audio`
--

LOCK TABLES `audio` WRITE;
/*!40000 ALTER TABLE `audio` DISABLE KEYS */;
/*!40000 ALTER TABLE `audio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer` (
  `id_customer` char(32) NOT NULL,
  `name` varchar(100) NOT NULL,
  `business_name` varchar(100) DEFAULT NULL,
  `document` char(19) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address_postcode` char(9) NOT NULL,
  `address_street` varchar(150) NOT NULL,
  `address_area` varchar(30) NOT NULL,
  `address_number` varchar(10) NOT NULL,
  `address_complement` varchar(30) DEFAULT NULL,
  `address_state` char(2) NOT NULL,
  `address_city` varchar(30) NOT NULL,
  `phone_ddd` char(2) NOT NULL,
  `phone_number` char(9) NOT NULL,
  `inc_date` datetime NOT NULL,
  PRIMARY KEY (`id_customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facepost`
--

DROP TABLE IF EXISTS `facepost`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facepost` (
  `id_facepost` char(32) NOT NULL,
  `date` datetime NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`id_facepost`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facepost`
--

LOCK TABLES `facepost` WRITE;
/*!40000 ALTER TABLE `facepost` DISABLE KEYS */;
/*!40000 ALTER TABLE `facepost` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `issuu`
--

DROP TABLE IF EXISTS `issuu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `issuu` (
  `id_issuu` char(32) NOT NULL,
  `link` varchar(255) NOT NULL,
  `document_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id_issuu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `issuu`
--

LOCK TABLES `issuu` WRITE;
/*!40000 ALTER TABLE `issuu` DISABLE KEYS */;
/*!40000 ALTER TABLE `issuu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id_log` int(10) NOT NULL AUTO_INCREMENT,
  `admin` varchar(255) NOT NULL,
  `tbl` varchar(100) NOT NULL,
  `inc_date` datetime NOT NULL,
  `action` char(1) NOT NULL,
  `description` text NOT NULL,
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`id_log`)
) ENGINE=InnoDB AUTO_INCREMENT=341 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log`
--

LOCK TABLES `log` WRITE;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
/*!40000 ALTER TABLE `log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mailing`
--

DROP TABLE IF EXISTS `mailing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mailing` (
  `id_mailing` char(32) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `inc_date` datetime NOT NULL,
  PRIMARY KEY (`id_mailing`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailing`
--

LOCK TABLES `mailing` WRITE;
/*!40000 ALTER TABLE `mailing` DISABLE KEYS */;
/*!40000 ALTER TABLE `mailing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mailing_group`
--

DROP TABLE IF EXISTS `mailing_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mailing_group` (
  `id_mailing_group` char(32) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id_mailing_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailing_group`
--

LOCK TABLES `mailing_group` WRITE;
/*!40000 ALTER TABLE `mailing_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `mailing_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id_news` char(32) NOT NULL,
  `id_news_cat` char(32) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `title` varchar(130) NOT NULL,
  `date` date NOT NULL,
  `head` varchar(380) NOT NULL,
  `body` mediumtext,
  `inc_date` datetime NOT NULL,
  `del_date` datetime DEFAULT NULL,
  `is_del` tinyint(1) NOT NULL DEFAULT '0',
  `cover` varchar(255) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `uri` varchar(255) NOT NULL,
  `has_tweet` tinyint(4) DEFAULT NULL,
  `has_facepost` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id_news`),
  KEY `fk_noticia_noticia_cat_idx` (`id_news_cat`),
  CONSTRAINT `fk_news_news_cat` FOREIGN KEY (`id_news_cat`) REFERENCES `news_cat` (`id_news_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_cat`
--

DROP TABLE IF EXISTS `news_cat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_cat` (
  `id_news_cat` char(32) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `title` varchar(130) NOT NULL,
  `inc_date` datetime NOT NULL,
  `del_date` datetime DEFAULT NULL,
  `is_del` tinyint(1) NOT NULL DEFAULT '0',
  `cover` varchar(255) DEFAULT NULL,
  `sequence` smallint(6) DEFAULT NULL,
  `is_home` tinyint(4) DEFAULT NULL,
  `uri` varchar(255) NOT NULL,
  PRIMARY KEY (`id_news_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_cat`
--

LOCK TABLES `news_cat` WRITE;
/*!40000 ALTER TABLE `news_cat` DISABLE KEYS */;
/*!40000 ALTER TABLE `news_cat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page` (
  `id_page` char(32) NOT NULL,
  `id_page_cat` char(32) NOT NULL,
  `id_parent` char(32) DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL,
  `title` varchar(130) NOT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `content` mediumtext,
  `description` varchar(156) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `inc_date` datetime NOT NULL,
  `del_date` datetime DEFAULT NULL,
  `is_del` tinyint(1) NOT NULL DEFAULT '0',
  `sequence` int(11) DEFAULT NULL,
  `uri` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `target` enum('_self','_blank') NOT NULL,
  PRIMARY KEY (`id_page`),
  KEY `fk_pagina_pagina_cat_idx` (`id_page_cat`),
  KEY `fk_pagina_pagina_idx` (`id_parent`),
  CONSTRAINT `fk_page_page_cat` FOREIGN KEY (`id_page_cat`) REFERENCES `page_cat` (`id_page_cat`),
  CONSTRAINT `fk_page_page` FOREIGN KEY (`id_parent`) REFERENCES `page` (`id_page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page`
--

LOCK TABLES `page` WRITE;
/*!40000 ALTER TABLE `page` DISABLE KEYS */;
/*!40000 ALTER TABLE `page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_cat`
--

DROP TABLE IF EXISTS `page_cat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_cat` (
  `id_page_cat` char(32) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `title` varchar(130) NOT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `content` mediumtext NOT NULL,
  `description` varchar(156) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `inc_date` datetime NOT NULL,
  `del_date` datetime DEFAULT NULL,
  `is_del` tinyint(1) NOT NULL DEFAULT '0',
  `sequence` int(11) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `target` enum('_self','_blank') NOT NULL,
  PRIMARY KEY (`id_page_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_cat`
--

LOCK TABLES `page_cat` WRITE;
/*!40000 ALTER TABLE `page_cat` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_cat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `photo`
--

DROP TABLE IF EXISTS `photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photo` (
  `id_photo` char(32) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `title` varchar(130) NOT NULL,
  `date` date NOT NULL,
  `is_del` tinyint(4) NOT NULL,
  `del_date` datetime DEFAULT NULL,
  `inc_date` datetime NOT NULL,
  `uri` varchar(255) NOT NULL,
  PRIMARY KEY (`id_photo`),
  KEY `titulo_idx` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `photo`
--

LOCK TABLES `photo` WRITE;
/*!40000 ALTER TABLE `photo` DISABLE KEYS */;
/*!40000 ALTER TABLE `photo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `photo_item`
--

DROP TABLE IF EXISTS `photo_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photo_item` (
  `id_photo_item` char(32) NOT NULL,
  `id_photo` char(32) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `credit` varchar(255) DEFAULT NULL,
  `file` varchar(255) NOT NULL,
  `sequence` int(11) NOT NULL,
  PRIMARY KEY (`id_photo_item`),
  KEY `fk_foto_item_foto_idx` (`id_photo`),
  CONSTRAINT `photo_item_ibfk_1` FOREIGN KEY (`id_photo`) REFERENCES `photo` (`id_photo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `photo_item`
--

LOCK TABLES `photo_item` WRITE;
/*!40000 ALTER TABLE `photo_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `photo_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `poll`
--

DROP TABLE IF EXISTS `poll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll` (
  `id_poll` char(32) NOT NULL,
  `question` varchar(130) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `is_del` tinyint(4) NOT NULL,
  `del_date` datetime DEFAULT NULL,
  `inc_date` datetime NOT NULL,
  `uri` varchar(255) NOT NULL,
  PRIMARY KEY (`id_poll`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `poll`
--

LOCK TABLES `poll` WRITE;
/*!40000 ALTER TABLE `poll` DISABLE KEYS */;
/*!40000 ALTER TABLE `poll` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `poll_answer`
--

DROP TABLE IF EXISTS `poll_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_answer` (
  `id_poll_answer` char(32) NOT NULL,
  `id_poll_option` char(32) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id_poll_answer`),
  KEY `fk_survey_answer_survey_option_idx` (`id_poll_option`),
  CONSTRAINT `fk_poll_answer_poll_option` FOREIGN KEY (`id_poll_option`) REFERENCES `poll_option` (`id_poll_option`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `poll_answer`
--

LOCK TABLES `poll_answer` WRITE;
/*!40000 ALTER TABLE `poll_answer` DISABLE KEYS */;
/*!40000 ALTER TABLE `poll_answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `poll_option`
--

DROP TABLE IF EXISTS `poll_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_option` (
  `id_poll_option` char(32) NOT NULL,
  `id_poll` char(32) NOT NULL,
  `option` varchar(130) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_poll_option`),
  KEY `fk_survey_option_survey_idx` (`id_poll`),
  CONSTRAINT `fk_poll_option_poll` FOREIGN KEY (`id_poll`) REFERENCES `poll` (`id_poll`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `poll_option`
--

LOCK TABLES `poll_option` WRITE;
/*!40000 ALTER TABLE `poll_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `poll_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publication`
--

DROP TABLE IF EXISTS `publication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publication` (
  `id_publication` char(32) NOT NULL,
  `id_issuu` char(32) DEFAULT NULL,
  `title` varchar(130) NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `inc_date` datetime NOT NULL,
  `del_date` datetime DEFAULT NULL,
  `is_del` tinyint(4) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `has_issuu` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id_publication`),
  KEY `fk_publication_1_idx` (`id_issuu`),
  CONSTRAINT `fk_publication_1` FOREIGN KEY (`id_issuu`) REFERENCES `issuu` (`id_issuu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publication`
--

LOCK TABLES `publication` WRITE;
/*!40000 ALTER TABLE `publication` DISABLE KEYS */;
/*!40000 ALTER TABLE `publication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `r_mailing_mailing_group`
--

DROP TABLE IF EXISTS `r_mailing_mailing_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `r_mailing_mailing_group` (
  `id_mailing_group` char(32) NOT NULL,
  `id_mailing` char(32) NOT NULL,
  `sequence` int(11) NOT NULL,
  PRIMARY KEY (`id_mailing_group`,`id_mailing`),
  KEY `fk_r_mg_m_idx` (`id_mailing`),
  CONSTRAINT `fk_r_mg_mg` FOREIGN KEY (`id_mailing_group`) REFERENCES `mailing_group` (`id_mailing_group`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_r_mg_m` FOREIGN KEY (`id_mailing`) REFERENCES `mailing` (`id_mailing`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r_mailing_mailing_group`
--

LOCK TABLES `r_mailing_mailing_group` WRITE;
/*!40000 ALTER TABLE `r_mailing_mailing_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `r_mailing_mailing_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `r_news_facepost`
--

DROP TABLE IF EXISTS `r_news_facepost`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `r_news_facepost` (
  `id_news` char(32) NOT NULL,
  `id_facepost` char(32) NOT NULL,
  PRIMARY KEY (`id_news`,`id_facepost`),
  KEY `fk_nf_facepost_idx` (`id_facepost`),
  CONSTRAINT `fk_nf_news` FOREIGN KEY (`id_news`) REFERENCES `news` (`id_news`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_nf_facepost` FOREIGN KEY (`id_facepost`) REFERENCES `facepost` (`id_facepost`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r_news_facepost`
--

LOCK TABLES `r_news_facepost` WRITE;
/*!40000 ALTER TABLE `r_news_facepost` DISABLE KEYS */;
/*!40000 ALTER TABLE `r_news_facepost` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `r_news_photo`
--

DROP TABLE IF EXISTS `r_news_photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `r_news_photo` (
  `id_r_news_photo` int(11) NOT NULL AUTO_INCREMENT,
  `id_news` char(32) NOT NULL,
  `id_photo` char(32) NOT NULL,
  `sequence` int(11) NOT NULL,
  PRIMARY KEY (`id_r_news_photo`),
  KEY `fk_r_noticia_idx` (`id_news`),
  KEY `fk_r_foto_idx` (`id_photo`),
  CONSTRAINT `fk_r_photo_news` FOREIGN KEY (`id_news`) REFERENCES `news` (`id_news`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_r_photo_photo` FOREIGN KEY (`id_photo`) REFERENCES `photo` (`id_photo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r_news_photo`
--

LOCK TABLES `r_news_photo` WRITE;
/*!40000 ALTER TABLE `r_news_photo` DISABLE KEYS */;
/*!40000 ALTER TABLE `r_news_photo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `r_news_tweet`
--

DROP TABLE IF EXISTS `r_news_tweet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `r_news_tweet` (
  `id_news` char(32) NOT NULL,
  `id_tweet` char(32) NOT NULL,
  PRIMARY KEY (`id_news`,`id_tweet`),
  KEY `fk_nt_tweets_idx` (`id_tweet`),
  CONSTRAINT `fk_nt_news` FOREIGN KEY (`id_news`) REFERENCES `news` (`id_news`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_nt_tweet` FOREIGN KEY (`id_tweet`) REFERENCES `tweet` (`id_tweet`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r_news_tweet`
--

LOCK TABLES `r_news_tweet` WRITE;
/*!40000 ALTER TABLE `r_news_tweet` DISABLE KEYS */;
/*!40000 ALTER TABLE `r_news_tweet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `r_news_video`
--

DROP TABLE IF EXISTS `r_news_video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `r_news_video` (
  `id_r_news_video` int(11) NOT NULL AUTO_INCREMENT,
  `id_news` char(32) NOT NULL,
  `id_video` char(32) NOT NULL,
  `sequence` int(11) NOT NULL,
  PRIMARY KEY (`id_r_news_video`),
  KEY `fk_r_noticia_idx` (`id_news`),
  KEY `fk_r_video_idx` (`id_video`),
  CONSTRAINT `fk_r_news` FOREIGN KEY (`id_news`) REFERENCES `news` (`id_news`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_r_video` FOREIGN KEY (`id_video`) REFERENCES `video` (`id_video`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r_news_video`
--

LOCK TABLES `r_news_video` WRITE;
/*!40000 ALTER TABLE `r_news_video` DISABLE KEYS */;
/*!40000 ALTER TABLE `r_news_video` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `r_video_tag`
--

DROP TABLE IF EXISTS `r_video_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `r_video_tag` (
  `id_r_video_tag` int(11) NOT NULL AUTO_INCREMENT,
  `id_video` char(32) NOT NULL,
  `id_tag` char(32) NOT NULL,
  `sequence` int(11) NOT NULL,
  PRIMARY KEY (`id_r_video_tag`),
  KEY `r_video_tag_video_idx` (`id_video`),
  KEY `r_video_tag_tag_idx` (`id_tag`),
  CONSTRAINT `r_video_tag_video` FOREIGN KEY (`id_video`) REFERENCES `video` (`id_video`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `r_video_tag_tag` FOREIGN KEY (`id_tag`) REFERENCES `tag` (`id_tag`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r_video_tag`
--

LOCK TABLES `r_video_tag` WRITE;
/*!40000 ALTER TABLE `r_video_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `r_video_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id_settings` int(11) NOT NULL,
  `home_title` varchar(69) NOT NULL,
  `home_description` varchar(156) NOT NULL,
  `home_keywords` varchar(255) NOT NULL,
  `title` varchar(69) NOT NULL,
  `description` varchar(156) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  PRIMARY KEY (`id_settings`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'1','1','1','1','1','1');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `socialmedia_credentials`
--

DROP TABLE IF EXISTS `socialmedia_credentials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `socialmedia_credentials` (
  `id_socialmedia_credentials` int(11) NOT NULL AUTO_INCREMENT,
  `fb_app_id` varchar(255) DEFAULT NULL,
  `fb_app_secret` varchar(255) DEFAULT NULL,
  `fb_access_token` varchar(500) DEFAULT NULL,
  `fb_page` varchar(255) DEFAULT NULL,
  `tw_consumer_key` varchar(255) DEFAULT NULL,
  `tw_consumer_secret` varchar(255) DEFAULT NULL,
  `tw_access_token` varchar(255) DEFAULT NULL,
  `tw_access_secret` varchar(255) DEFAULT NULL,
  `issuu_key` varchar(255) DEFAULT NULL,
  `issuu_secret` varchar(255) DEFAULT NULL,
  `sc_client_id` varchar(255) DEFAULT NULL,
  `sc_client_secret` varchar(255) DEFAULT NULL,
  `sc_token` varchar(255) DEFAULT NULL,
  `youtube_id` varchar(255) DEFAULT NULL,
  `youtube_secret` varchar(255) DEFAULT NULL,
  `youtube_token` text,
  PRIMARY KEY (`id_socialmedia_credentials`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `socialmedia_credentials`
--

LOCK TABLES `socialmedia_credentials` WRITE;
/*!40000 ALTER TABLE `socialmedia_credentials` DISABLE KEYS */;
INSERT INTO `socialmedia_credentials` VALUES (1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `socialmedia_credentials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `soundcloud`
--

DROP TABLE IF EXISTS `soundcloud`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `soundcloud` (
  `id_soundcloud` char(32) NOT NULL,
  `track_id` varchar(250) NOT NULL,
  `track_permalink` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_soundcloud`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `soundcloud`
--

LOCK TABLES `soundcloud` WRITE;
/*!40000 ALTER TABLE `soundcloud` DISABLE KEYS */;
/*!40000 ALTER TABLE `soundcloud` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `survey`
--

DROP TABLE IF EXISTS `survey`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `survey` (
  `id_survey` char(32) NOT NULL,
  `title` varchar(130) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `inc_date` datetime NOT NULL,
  `is_del` tinyint(4) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `del_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id_survey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `survey`
--

LOCK TABLES `survey` WRITE;
/*!40000 ALTER TABLE `survey` DISABLE KEYS */;
/*!40000 ALTER TABLE `survey` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `survey_customer`
--

DROP TABLE IF EXISTS `survey_customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `survey_customer` (
  `id_survey_answer` char(32) NOT NULL,
  `id_survey` char(32) NOT NULL,
  `inc_date` datetime NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id_survey_answer`),
  KEY `fk_survey_answer_survey_option_idx` (`id_survey`),
  CONSTRAINT `fk_survey_c_survey` FOREIGN KEY (`id_survey`) REFERENCES `survey` (`id_survey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `survey_customer`
--

LOCK TABLES `survey_customer` WRITE;
/*!40000 ALTER TABLE `survey_customer` DISABLE KEYS */;
/*!40000 ALTER TABLE `survey_customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `survey_customer_answer`
--

DROP TABLE IF EXISTS `survey_customer_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `survey_customer_answer` (
  `id_survey_customer_answer` char(32) NOT NULL,
  `id_survey_question` char(32) NOT NULL,
  `id_survey_customer` char(32) NOT NULL,
  `answer` varchar(255) NOT NULL,
  PRIMARY KEY (`id_survey_customer_answer`),
  KEY `fk_survey_answer_option_survey_option_idx` (`id_survey_question`),
  KEY `fk_survey_ca_survey_customer_idx` (`id_survey_customer`),
  CONSTRAINT `fk_survey_ca_survey_question` FOREIGN KEY (`id_survey_question`) REFERENCES `survey_question` (`id_survey_question`),
  CONSTRAINT `fk_survey_ca_survey_customer` FOREIGN KEY (`id_survey_customer`) REFERENCES `survey_customer` (`id_survey_answer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `survey_customer_answer`
--

LOCK TABLES `survey_customer_answer` WRITE;
/*!40000 ALTER TABLE `survey_customer_answer` DISABLE KEYS */;
/*!40000 ALTER TABLE `survey_customer_answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `survey_question`
--

DROP TABLE IF EXISTS `survey_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `survey_question` (
  `id_survey_question` char(32) NOT NULL,
  `id_survey` char(32) NOT NULL,
  `question` varchar(130) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_survey_question`),
  KEY `fk_survey_option_survey_idx` (`id_survey`),
  CONSTRAINT `fk_survey_question_survey` FOREIGN KEY (`id_survey`) REFERENCES `survey` (`id_survey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `survey_question`
--

LOCK TABLES `survey_question` WRITE;
/*!40000 ALTER TABLE `survey_question` DISABLE KEYS */;
/*!40000 ALTER TABLE `survey_question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag`
--

DROP TABLE IF EXISTS `tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tag` (
  `id_tag` char(32) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `title` varchar(130) NOT NULL,
  `inc_date` datetime NOT NULL,
  `del_date` datetime DEFAULT NULL,
  `is_del` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag`
--

LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tweet`
--

DROP TABLE IF EXISTS `tweet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tweet` (
  `id_tweet` char(32) NOT NULL,
  `date` datetime NOT NULL,
  `msg` varchar(140) NOT NULL,
  PRIMARY KEY (`id_tweet`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tweet`
--

LOCK TABLES `tweet` WRITE;
/*!40000 ALTER TABLE `tweet` DISABLE KEYS */;
/*!40000 ALTER TABLE `tweet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `video`
--

DROP TABLE IF EXISTS `video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video` (
  `id_video` char(32) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `title` varchar(130) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(156) NOT NULL,
  `link_youtube` varchar(255) DEFAULT NULL,
  `link_vimeo` varchar(255) DEFAULT NULL,
  `inc_date` datetime NOT NULL,
  `del_date` datetime DEFAULT NULL,
  `is_del` tinyint(1) NOT NULL DEFAULT '0',
  `uri` varchar(255) NOT NULL,
  `short_link` varchar(255) DEFAULT NULL,
  `file` varchar(200) DEFAULT NULL,
  `id_youtube` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_video`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `video`
--

LOCK TABLES `video` WRITE;
/*!40000 ALTER TABLE `video` DISABLE KEYS */;
/*!40000 ALTER TABLE `video` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-04-16 16:51:24
