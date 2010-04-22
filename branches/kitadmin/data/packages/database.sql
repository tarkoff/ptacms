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
-- Table structure for table `CATALOG_BRANDS`
--

DROP TABLE IF EXISTS `CATALOG_BRANDS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CATALOG_BRANDS` (
  `BRANDS_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `BRANDS_ALIAS` char(30) NOT NULL,
  `BRANDS_TITLE` varchar(50) NOT NULL,
  `BRANDS_URL` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`BRANDS_ID`),
  UNIQUE KEY `BRANDS_ALIAS` (`BRANDS_ALIAS`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `CATALOG_CATEGORIES`
--

DROP TABLE IF EXISTS `CATALOG_CATEGORIES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CATALOG_CATEGORIES` (
  `CATEGORIES_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `CATEGORIES_LEFT` int(10) unsigned NOT NULL,
  `CATEGORIES_RIGHT` int(10) unsigned NOT NULL,
  `CATEGORIES_LEVEL` int(10) unsigned NOT NULL DEFAULT '0',
  `CATEGORIES_PARENTID` int(10) unsigned NOT NULL,
  `CATEGORIES_TITLE` varchar(60) NOT NULL DEFAULT '',
  `CATEGORIES_ALIAS` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`CATEGORIES_ID`),
  KEY `tree_key` (`CATEGORIES_LEFT`,`CATEGORIES_RIGHT`,`CATEGORIES_LEVEL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `CATALOG_CATEGORYGROUPS`
--

DROP TABLE IF EXISTS `CATALOG_CATEGORYGROUPS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CATALOG_CATEGORYGROUPS` (
  `CATEGORYGROUPS_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `CATEGORYGROUPS_CATEGORYID` int(10) unsigned NOT NULL,
  `CATEGORYGROUPS_GROUPID` int(10) unsigned NOT NULL,
  `CATEGORYGROUPS_SORTORDER` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`CATEGORYGROUPS_ID`),
  UNIQUE KEY `CATEGORY_GROUP` (`CATEGORYGROUPS_CATEGORYID`,`CATEGORYGROUPS_GROUPID`),
  KEY `CATEGORYGROUPS_GROUPID` (`CATEGORYGROUPS_GROUPID`),
  CONSTRAINT `CATALOG_CATEGORYGROUPS_ibfk_1` FOREIGN KEY (`CATEGORYGROUPS_CATEGORYID`) REFERENCES `CATALOG_CATEGORIES` (`CATEGORIES_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CATALOG_CATEGORYGROUPS_ibfk_2` FOREIGN KEY (`CATEGORYGROUPS_GROUPID`) REFERENCES `CATALOG_FIELDGROUPS` (`FIELDGROUPS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `CATALOG_FIELDGROUPS`
--

DROP TABLE IF EXISTS `CATALOG_FIELDGROUPS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CATALOG_FIELDGROUPS` (
  `FIELDGROUPS_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FIELDGROUPS_ALIAS` varchar(50) NOT NULL DEFAULT '',
  `FIELDGROUPS_TITLE` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`FIELDGROUPS_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `CATALOG_FIELDS`
--

DROP TABLE IF EXISTS `CATALOG_FIELDS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CATALOG_FIELDS` (
  `FIELDS_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FIELDS_ALIAS` char(50) NOT NULL DEFAULT '',
  `FIELDS_TITLE` char(100) NOT NULL DEFAULT '',
  `FIELDS_FIELDTYPE` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`FIELDS_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `CATALOG_FIELDSVALUES`
--

DROP TABLE IF EXISTS `CATALOG_FIELDSVALUES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CATALOG_FIELDSVALUES` (
  `FIELDSVALUES_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FIELDSVALUES_FIELDID` int(10) unsigned NOT NULL,
  `FIELDSVALUES_VALUE` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`FIELDSVALUES_ID`),
  UNIQUE KEY `FIELD_VALUE` (`FIELDSVALUES_FIELDID`,`FIELDSVALUES_VALUE`),
  CONSTRAINT `CATALOG_FIELDSVALUES_ibfk_1` FOREIGN KEY (`FIELDSVALUES_FIELDID`) REFERENCES `CATALOG_FIELDS` (`FIELDS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `CATALOG_GROUPFIELDS`
--

DROP TABLE IF EXISTS `CATALOG_GROUPFIELDS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CATALOG_GROUPFIELDS` (
  `GROUPFIELDS_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `GROUPFIELDS_CATEGORYGROUPID` int(10) unsigned NOT NULL,
  `GROUPFIELDS_FIELDID` int(10) unsigned NOT NULL,
  `GROUPFIELDS_SORTORDER` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`GROUPFIELDS_ID`),
  UNIQUE KEY `GROUP_FIELD` (`GROUPFIELDS_CATEGORYGROUPID`,`GROUPFIELDS_FIELDID`),
  KEY `GROUPFIELDS_FIELDID` (`GROUPFIELDS_FIELDID`),
  CONSTRAINT `CATALOG_GROUPFIELDS_ibfk_1` FOREIGN KEY (`GROUPFIELDS_CATEGORYGROUPID`) REFERENCES `CATALOG_CATEGORYGROUPS` (`CATEGORYGROUPS_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CATALOG_GROUPFIELDS_ibfk_2` FOREIGN KEY (`GROUPFIELDS_FIELDID`) REFERENCES `CATALOG_FIELDS` (`FIELDS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `CATALOG_PHOTOS`
--

DROP TABLE IF EXISTS `CATALOG_PHOTOS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CATALOG_PHOTOS` (
  `PHOTOS_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `PHOTOS_PRODUCTID` int(10) unsigned NOT NULL,
  `PHOTOS_ISDEFAULT` tinyint(1) unsigned NOT NULL,
  `PHOTOS_FILE` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`PHOTOS_ID`),
  KEY `PHOTOS_PRODUCTID` (`PHOTOS_PRODUCTID`),
  CONSTRAINT `CATALOG_PHOTOS_ibfk_1` FOREIGN KEY (`PHOTOS_PRODUCTID`) REFERENCES `CATALOG_PRODUCTS` (`PRODUCTS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `CATALOG_PRODUCTCATEGORIES`
--

DROP TABLE IF EXISTS `CATALOG_PRODUCTCATEGORIES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CATALOG_PRODUCTCATEGORIES` (
  `PRODUCTCATEGORIES_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `PRODUCTCATEGORIES_PRODUCTID` int(10) unsigned NOT NULL,
  `PRODUCTCATEGORIES_CATEGORYID` int(10) unsigned NOT NULL,
  `PRODUCTCATEGORIES_ISDEFAULT` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`PRODUCTCATEGORIES_ID`),
  UNIQUE KEY `PRODUCT_CATEGORY` (`PRODUCTCATEGORIES_PRODUCTID`,`PRODUCTCATEGORIES_CATEGORYID`),
  KEY `PRODUCTCATEGORIES_PRODUCTID` (`PRODUCTCATEGORIES_PRODUCTID`),
  KEY `PRODUCTCATEGORIES_CATEGORYID` (`PRODUCTCATEGORIES_CATEGORYID`),
  CONSTRAINT `CATALOG_PRODUCTCATEGORIES_ibfk_1` FOREIGN KEY (`PRODUCTCATEGORIES_PRODUCTID`) REFERENCES `CATALOG_PRODUCTS` (`PRODUCTS_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CATALOG_PRODUCTCATEGORIES_ibfk_2` FOREIGN KEY (`PRODUCTCATEGORIES_CATEGORYID`) REFERENCES `CATALOG_CATEGORIES` (`CATEGORIES_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `CATALOG_PRODUCTS`
--

DROP TABLE IF EXISTS `CATALOG_PRODUCTS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CATALOG_PRODUCTS` (
  `PRODUCTS_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `PRODUCTS_BRANDID` int(10) unsigned DEFAULT NULL,
  `PRODUCTS_AUTHORID` int(10) unsigned NOT NULL,
  `PRODUCTS_ALIAS` char(50) NOT NULL,
  `PRODUCTS_TITLE` varchar(100) NOT NULL,
  `PRODUCTS_SHORTDESCR` text,
  `PRODUCTS_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `PRODUCTS_URL` text,
  `PRODUCTS_DRIVERSURL` text,
  PRIMARY KEY (`PRODUCTS_ID`),
  UNIQUE KEY `PRODUCTS_ALIAS` (`PRODUCTS_ALIAS`),
  KEY `PRODUCTS_BRANDID` (`PRODUCTS_BRANDID`),
  KEY `PRODUCTS_AUTHORID` (`PRODUCTS_AUTHORID`),
  CONSTRAINT `CATALOG_PRODUCTS_ibfk_1` FOREIGN KEY (`PRODUCTS_BRANDID`) REFERENCES `CATALOG_BRANDS` (`BRANDS_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CATALOG_PRODUCTS_ibfk_2` FOREIGN KEY (`PRODUCTS_AUTHORID`) REFERENCES `USERS` (`USERS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `CATALOG_PRODUCTVALUES`
--

DROP TABLE IF EXISTS `CATALOG_PRODUCTVALUES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CATALOG_PRODUCTVALUES` (
  `PRODUCTVALUES_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `PRODUCTVALUES_PRODUCTID` int(10) unsigned NOT NULL,
  `PRODUCTVALUES_FIELDID` int(10) unsigned NOT NULL,
  `PRODUCTVALUES_VALUEID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`PRODUCTVALUES_ID`),
  KEY `PRODUCTVALUES_PRODUCTID` (`PRODUCTVALUES_PRODUCTID`),
  KEY `PRODUCTVALUES_VALUEID` (`PRODUCTVALUES_VALUEID`),
  KEY `PRODUCTVALUES_FIELDID` (`PRODUCTVALUES_FIELDID`),
  CONSTRAINT `CATALOG_PRODUCTVALUES_ibfk_1` FOREIGN KEY (`PRODUCTVALUES_PRODUCTID`) REFERENCES `CATALOG_PRODUCTS` (`PRODUCTS_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CATALOG_PRODUCTVALUES_ibfk_2` FOREIGN KEY (`PRODUCTVALUES_VALUEID`) REFERENCES `CATALOG_FIELDSVALUES` (`FIELDSVALUES_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CATALOG_PRODUCTVALUES_ibfk_3` FOREIGN KEY (`PRODUCTVALUES_FIELDID`) REFERENCES `CATALOG_GROUPFIELDS` (`GROUPFIELDS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RESOURCES`
--

LOCK TABLES `RESOURCES` WRITE;
/*!40000 ALTER TABLE `RESOURCES` DISABLE KEYS */;
INSERT INTO `RESOURCES` VALUES (37,'catalog','brands','add'),(39,'catalog','brands','delete'),(38,'catalog','brands','edit'),(35,'catalog','brands','index'),(36,'catalog','brands','list'),(42,'catalog','categories','add'),(44,'catalog','categories','delete'),(43,'catalog','categories','edit'),(40,'catalog','categories','index'),(41,'catalog','categories','list'),(55,'catalog','categorygroups','list'),(52,'catalog','fieldgroups','add'),(54,'catalog','fieldgroups','delete'),(53,'catalog','fieldgroups','edit'),(61,'catalog','fieldgroups','fields'),(50,'catalog','fieldgroups','index'),(51,'catalog','fieldgroups','list'),(47,'catalog','fields','add'),(49,'catalog','fields','delete'),(48,'catalog','fields','edit'),(45,'catalog','fields','index'),(46,'catalog','fields','list'),(33,'catalog','index','index'),(34,'catalog','index','list'),(58,'catalog','photos','add'),(60,'catalog','photos','delete'),(59,'catalog','photos','edit'),(56,'catalog','photos','index'),(57,'catalog','photos','list'),(30,'catalog','products','add'),(32,'catalog','products','delete'),(31,'catalog','products','edit'),(28,'catalog','products','index'),(29,'catalog','products','list'),(2,'default','auth','login'),(3,'default','auth','logout'),(1,'default','index','index'),(27,'default','index','list'),(6,'default','menus','add'),(8,'default','menus','delete'),(7,'default','menus','edit'),(4,'default','menus','index'),(5,'default','menus','list'),(11,'default','resources','add'),(13,'default','resources','delete'),(12,'default','resources','edit'),(9,'default','resources','index'),(10,'default','resources','list'),(22,'default','usergroups','add'),(25,'default','usergroups','delete'),(23,'default','usergroups','edit'),(20,'default','usergroups','index'),(21,'default','usergroups','list'),(24,'default','usergroups','rights'),(16,'default','users','add'),(19,'default','users','delete'),(17,'default','users','edit'),(14,'default','users','index'),(15,'default','users','list'),(18,'default','users','rights');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USERGROUPS_ACL`
--

LOCK TABLES `USERGROUPS_ACL` WRITE;
/*!40000 ALTER TABLE `USERGROUPS_ACL` DISABLE KEYS */;
INSERT INTO `USERGROUPS_ACL` VALUES (39,2,2),(40,4,2),(41,5,2),(42,3,2),(43,27,2),(44,1,2);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USERS`
--

LOCK TABLES `USERS` WRITE;
/*!40000 ALTER TABLE `USERS` DISABLE KEYS */;
INSERT INTO `USERS` VALUES (1,'admin','037f85b5bc3bd0ec6efb1357eb5bc238',1,'System','Administrator','admin@gelezka.net',1,'2010-02-07 22:39:42'),(2,'guest','037f85b5bc3bd0ec6efb1357eb5bc238',2,'Guest','Guest','guest@gelezka.net',1,'2010-02-09 11:45:33');
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

-- Dump completed on 2010-04-22 22:58:40
