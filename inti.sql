-- MySQL dump 10.13  Distrib 5.6.13, for Win32 (x86)
--
-- Host: localhost    Database: fgc
-- ------------------------------------------------------
-- Server version	5.6.17

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
-- Table structure for table `avatar`
--

DROP TABLE IF EXISTS `avatar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `avatar` (
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '帳號',
  `game` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '遊戲名稱',
  `id` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`game`,`id`),
  KEY `username` (`username`),
  KEY `id` (`id`),
  KEY `game` (`game`),
  CONSTRAINT `avatar_game` FOREIGN KEY (`game`) REFERENCES `game` (`game`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `avatar_username` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='化身';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avatar`
--

LOCK TABLES `avatar` WRITE;
/*!40000 ALTER TABLE `avatar` DISABLE KEYS */;
/*!40000 ALTER TABLE `avatar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bulletin`
--

DROP TABLE IF EXISTS `bulletin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bulletin` (
  `bid` int(11) NOT NULL AUTO_INCREMENT COMMENT '公告流水號',
  `game` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '所屬遊戲',
  `title` varchar(45) COLLATE utf8_unicode_ci NOT NULL COMMENT '公告標題',
  `msg` text COLLATE utf8_unicode_ci COMMENT '公告內容',
  `date` datetime DEFAULT NULL COMMENT '發佈日期',
  `deadline` datetime DEFAULT NULL COMMENT '期限',
  PRIMARY KEY (`bid`),
  KEY `game_idx` (`game`),
  CONSTRAINT `bulletin_game` FOREIGN KEY (`game`) REFERENCES `game` (`game`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='公告';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bulletin`
--

LOCK TABLES `bulletin` WRITE;
/*!40000 ALTER TABLE `bulletin` DISABLE KEYS */;
/*!40000 ALTER TABLE `bulletin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game`
--

DROP TABLE IF EXISTS `game`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game` (
  `game` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '遊戲名稱',
  `gameName` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '遊戲中文名稱',
  `shortInfo` text COLLATE utf8_unicode_ci COMMENT '簡介',
  `information` text COLLATE utf8_unicode_ci COMMENT '詳細資訊',
  `downloadLink` text COLLATE utf8_unicode_ci COMMENT '下載網址',
  `hide` tinyint(1) DEFAULT '0' COMMENT '隱藏遊戲',
  PRIMARY KEY (`game`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='遊戲';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game`
--

LOCK TABLES `game` WRITE;
/*!40000 ALTER TABLE `game` DISABLE KEYS */;
/*!40000 ALTER TABLE `game` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_record`
--

DROP TABLE IF EXISTS `game_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_record` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '遊戲記錄編號',
  `game` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '遊戲名稱',
  `id1` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '玩家1 ID',
  `id2` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '玩家2 ID',
  `startTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `endTime` timestamp NULL DEFAULT NULL,
  `record` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`rid`),
  KEY `id1` (`id1`),
  KEY `id2` (`id2`),
  KEY `status_game_idx` (`game`),
  CONSTRAINT `status_game` FOREIGN KEY (`game`) REFERENCES `game` (`game`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='遊戲狀態';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_record`
--

LOCK TABLES `game_record` WRITE;
/*!40000 ALTER TABLE `game_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `game_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gm`
--

DROP TABLE IF EXISTS `gm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gm` (
  `game` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '遊戲名稱',
  `uid` int(11) NOT NULL COMMENT '帳號',
  PRIMARY KEY (`game`,`uid`),
  KEY `uid_idx` (`uid`),
  CONSTRAINT `game` FOREIGN KEY (`game`) REFERENCES `game` (`game`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `uid` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='遊戲管理員';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gm`
--

LOCK TABLES `gm` WRITE;
/*!40000 ALTER TABLE `gm` DISABLE KEYS */;
/*!40000 ALTER TABLE `gm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group`
--

DROP TABLE IF EXISTS `group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group` (
  `group` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT '群組名稱',
  `groupName` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '群組中文名稱',
  `permAdmincp` tinyint(1) DEFAULT NULL COMMENT '權限（範例）',
  `editNews` tinyint(1) DEFAULT NULL,
  `editGame` tinyint(1) DEFAULT NULL,
  `editProfile` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`group`),
  KEY `group` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='群組';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group`
--

LOCK TABLES `group` WRITE;
/*!40000 ALTER TABLE `group` DISABLE KEYS */;
INSERT INTO `group` VALUES ('admin','管理員',1,1,1,1),('guest','遊客',0,NULL,NULL,NULL),('unverified','未驗證',0,NULL,NULL,NULL),('user','一般會員',0,NULL,NULL,NULL);
/*!40000 ALTER TABLE `group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `queue`
--

DROP TABLE IF EXISTS `queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `queue` (
  `qid` int(11) NOT NULL AUTO_INCREMENT COMMENT '排隊流水號',
  `id` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色ID',
  `game` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '遊戲名稱',
  `joinTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '加入排隊時間',
  `lock` tinyint(1) DEFAULT NULL COMMENT '鎖定',
  PRIMARY KEY (`qid`),
  KEY `id` (`id`),
  KEY `game` (`game`),
  KEY `id_idx` (`id`,`game`),
  CONSTRAINT `queue_game` FOREIGN KEY (`game`) REFERENCES `game` (`game`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `queue_id` FOREIGN KEY (`id`) REFERENCES `avatar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='排隊序列';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `queue`
--

LOCK TABLES `queue` WRITE;
/*!40000 ALTER TABLE `queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stats`
--

DROP TABLE IF EXISTS `stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stats` (
  `id` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色ID',
  `game` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '遊戲名稱',
  `time` int(11) NOT NULL DEFAULT '0' COMMENT '遊玩次數',
  `winTime` int(11) NOT NULL DEFAULT '0' COMMENT '勝利次數',
  `loseTime` int(11) NOT NULL DEFAULT '0' COMMENT '敗北次數',
  PRIMARY KEY (`id`,`game`),
  KEY `id` (`id`),
  KEY `game` (`game`),
  CONSTRAINT `stats_game` FOREIGN KEY (`game`) REFERENCES `game` (`game`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `stats_id` FOREIGN KEY (`id`) REFERENCES `avatar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='戰績';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stats`
--

LOCK TABLES `stats` WRITE;
/*!40000 ALTER TABLE `stats` DISABLE KEYS */;
/*!40000 ALTER TABLE `stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test`
--

DROP TABLE IF EXISTS `test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test` (
  `idtest` int(11) NOT NULL,
  PRIMARY KEY (`idtest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test`
--

LOCK TABLES `test` WRITE;
/*!40000 ALTER TABLE `test` DISABLE KEYS */;
INSERT INTO `test` VALUES (0);
/*!40000 ALTER TABLE `test` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '帳號',
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '密碼',
  `nickname` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'token',
  `tokenDeadline` datetime DEFAULT NULL COMMENT 'token期限',
  `loginType` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '登入方式',
  `verifyCode` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '驗證代碼',
  `group` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT '會員群組',
  `findPwdCode` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '找回密碼驗證碼',
  `findPwdTime` datetime DEFAULT NULL COMMENT '找回密碼驗證碼生成時間',
  PRIMARY KEY (`uid`),
  KEY `username` (`username`),
  KEY `group` (`group`),
  CONSTRAINT `user_group` FOREIGN KEY (`group`) REFERENCES `group` (`group`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用戶';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-06-02 20:13:20
