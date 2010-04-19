-- MySQL dump 10.13  Distrib 5.1.37, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: KIT
-- ------------------------------------------------------
-- Server version	5.1.37-1ubuntu5.1

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

SET NAMES utf8;

--
-- Table structure for table `MENUS`
--

DROP TABLE IF EXISTS `MENUS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MENUS` (
  `MENUS_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `MENUS_LEFT` int(10) unsigned NOT NULL,
  `MENUS_RIGHT` int(10) unsigned NOT NULL,
  `MENUS_LEVEL` smallint(5) unsigned NOT NULL DEFAULT '0',
  `MENUS_PARENTID` int(10) unsigned NOT NULL,
  `MENUS_TITLE` varchar(60) NOT NULL DEFAULT '',
  `MENUS_ALIAS` varchar(60) NOT NULL DEFAULT '',
  `MENUS_RESOURCEID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`MENUS_ID`),
  KEY `MENUS_RESOURCEID` (`MENUS_RESOURCEID`),
  KEY `tree_key` (`MENUS_LEFT`,`MENUS_RIGHT`,`MENUS_LEVEL`),
  CONSTRAINT `MENUS_ibfk_1` FOREIGN KEY (`MENUS_RESOURCEID`) REFERENCES `RESOURCES` (`RESOURCES_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MENUS`
--

LOCK TABLES `MENUS` WRITE;
/*!40000 ALTER TABLE `MENUS` DISABLE KEYS */;
INSERT INTO `MENUS` VALUES (1,1,10,0,0,'System','system',1),(2,2,3,1,1,'Menus','menus',4),(3,4,5,1,1,'Resources','resources',9),(4,6,7,1,1,'User Groups','usergroups',20),(5,10,11,2,1,'Users','users',14);
/*!40000 ALTER TABLE `MENUS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RESOURCES`
--

DROP TABLE IF EXISTS `RESOURCES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RESOURCES` (
  `RESOURCES_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `RESOURCES_MODULE` varchar(50) NOT NULL DEFAULT 'default',
  `RESOURCES_CONTROLLER` varchar(50) NOT NULL DEFAULT '',
  `RESOURCES_ACTION` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`RESOURCES_ID`),
  UNIQUE KEY `resource` (`RESOURCES_MODULE`,`RESOURCES_CONTROLLER`,`RESOURCES_ACTION`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RESOURCES`
--

LOCK TABLES `RESOURCES` WRITE;
/*!40000 ALTER TABLE `RESOURCES` DISABLE KEYS */;
INSERT INTO `RESOURCES` VALUES (2,'default','auth','login'),(3,'default','auth','logout'),(1,'default','index','index'),(27,'default','index','list'),(6,'default','menus','add'),(8,'default','menus','delete'),(7,'default','menus','edit'),(4,'default','menus','index'),(5,'default','menus','list'),(11,'default','resources','add'),(13,'default','resources','delete'),(12,'default','resources','edit'),(9,'default','resources','index'),(10,'default','resources','list'),(22,'default','usergroups','add'),(25,'default','usergroups','delete'),(23,'default','usergroups','edit'),(20,'default','usergroups','index'),(21,'default','usergroups','list'),(24,'default','usergroups','rights'),(16,'default','users','add'),(19,'default','users','delete'),(17,'default','users','edit'),(14,'default','users','index'),(15,'default','users','list'),(18,'default','users','rights');
/*!40000 ALTER TABLE `RESOURCES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `USERGROUPS`
--

DROP TABLE IF EXISTS `USERGROUPS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USERGROUPS` (
  `USERGROUPS_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `USERGROUPS_TITLE` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`USERGROUPS_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USERGROUPS`
--

LOCK TABLES `USERGROUPS` WRITE;
/*!40000 ALTER TABLE `USERGROUPS` DISABLE KEYS */;
INSERT INTO `USERGROUPS` VALUES (1,'Administrators'),(2,'Guests');
/*!40000 ALTER TABLE `USERGROUPS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `USERGROUPS_ACL`
--

DROP TABLE IF EXISTS `USERGROUPS_ACL`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USERGROUPS_ACL` (
  `GROUPSACL_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `GROUPSACL_RESOURCEID` int(10) unsigned NOT NULL,
  `GROUPSACL_GROUPID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`GROUPSACL_ID`),
  KEY `GROUPSACL_RESOURCEID` (`GROUPSACL_RESOURCEID`),
  KEY `GROUPSACL_GROUPID` (`GROUPSACL_GROUPID`),
  CONSTRAINT `USERGROUPS_ACL_ibfk_1` FOREIGN KEY (`GROUPSACL_RESOURCEID`) REFERENCES `RESOURCES` (`RESOURCES_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `USERGROUPS_ACL_ibfk_2` FOREIGN KEY (`GROUPSACL_GROUPID`) REFERENCES `USERGROUPS` (`USERGROUPS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USERGROUPS_ACL`
--

LOCK TABLES `USERGROUPS_ACL` WRITE;
/*!40000 ALTER TABLE `USERGROUPS_ACL` DISABLE KEYS */;
INSERT INTO `USERGROUPS_ACL` VALUES (1,2,2),(2,4,2),(3,5,2),(4,3,2),(5,27,2),(6,1,2);
/*!40000 ALTER TABLE `USERGROUPS_ACL` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `USERS`
--

DROP TABLE IF EXISTS `USERS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USERS` (
  `USERS_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `USERS_LOGIN` varchar(50) NOT NULL DEFAULT 'Anonymous',
  `USERS_PASSWORD` varchar(32) NOT NULL DEFAULT '',
  `USERS_GROUPID` int(10) unsigned NOT NULL,
  `USERS_FIRSTNAME` varchar(50) NOT NULL DEFAULT '',
  `USERS_LASTNAME` varchar(50) NOT NULL DEFAULT '',
  `USERS_EMAIL` varchar(50) NOT NULL DEFAULT '',
  `USERS_STATUS` tinyint(1) unsigned DEFAULT NULL,
  `USERS_REGISTERED` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`USERS_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USERS`
--

LOCK TABLES `USERS` WRITE;
/*!40000 ALTER TABLE `USERS` DISABLE KEYS */;
INSERT INTO `USERS` VALUES (1,'admin','037f85b5bc3bd0ec6efb1357eb5bc238',1,'System','Administrator','admin@gelezka.net',1, CURRENT_TIMESTAMP),(2,'guest','037f85b5bc3bd0ec6efb1357eb5bc238',2,'Guest','Guest','guest@gelezka.net',1, CURRENT_TIMESTAMP);
/*!40000 ALTER TABLE `USERS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `USERS_ACL`
--

DROP TABLE IF EXISTS `USERS_ACL`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USERS_ACL` (
  `USERSACL_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `USERSACL_RESOURCEID` int(10) unsigned NOT NULL,
  `USERSACL_USERID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`USERSACL_ID`),
  KEY `USERSACL_RESOURCEID` (`USERSACL_RESOURCEID`),
  KEY `USERSACL_USERID` (`USERSACL_USERID`),
  CONSTRAINT `USERS_ACL_ibfk_1` FOREIGN KEY (`USERSACL_RESOURCEID`) REFERENCES `RESOURCES` (`RESOURCES_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `USERS_ACL_ibfk_2` FOREIGN KEY (`USERSACL_USERID`) REFERENCES `USERS` (`USERS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USERS_ACL`
--

LOCK TABLES `USERS_ACL` WRITE;
/*!40000 ALTER TABLE `USERS_ACL` DISABLE KEYS */;
/*!40000 ALTER TABLE `USERS_ACL` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-03-01 21:53:48
