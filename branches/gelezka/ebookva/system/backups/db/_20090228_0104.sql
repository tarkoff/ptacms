-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.67-0ubuntu6


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema EBOOKVA
--

CREATE DATABASE IF NOT EXISTS EBOOKVA;
USE EBOOKVA;

--
-- Definition of table `EBOOKVA`.`CATEGORIES`
--

DROP TABLE IF EXISTS `EBOOKVA`.`CATEGORIES`;
CREATE TABLE  `EBOOKVA`.`CATEGORIES` (
  `CATEGORIES_ID` int(10) unsigned NOT NULL auto_increment,
  `CATEGORIES_PARENTID` int(10) unsigned default NULL,
  `CATEGORIES_TITLE` char(80) NOT NULL default '',
  PRIMARY KEY  (`CATEGORIES_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Definition of table `EBOOKVA`.`CATEGORIESFIELDS`
--

DROP TABLE IF EXISTS `EBOOKVA`.`CATEGORIESFIELDS`;
CREATE TABLE  `EBOOKVA`.`CATEGORIESFIELDS` (
  `CATEGORIESFIELDS_ID` int(10) unsigned NOT NULL auto_increment,
  `CATEGORIESFIELDS_CATEGORYID` int(10) unsigned NOT NULL,
  `CATEGORIESFIELDS_FIELDID` int(10) unsigned NOT NULL,
  `CATEGORIESFIELDS_SORTORDER` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`CATEGORIESFIELDS_ID`),
  KEY `CATEGORIESFIELDS_CATEGORYID` (`CATEGORIESFIELDS_CATEGORYID`),
  KEY `CATEGORIESFIELDS_FIELDID` (`CATEGORIESFIELDS_FIELDID`),
  CONSTRAINT `CATEGORIESFIELDS_ibfk_1` FOREIGN KEY (`CATEGORIESFIELDS_CATEGORYID`) REFERENCES `CATEGORIES` (`CATEGORIES_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CATEGORIESFIELDS_ibfk_2` FOREIGN KEY (`CATEGORIESFIELDS_FIELDID`) REFERENCES `PRODUCTSFIELDS` (`PRODUCTSFIELDS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Definition of table `EBOOKVA`.`PRODUCTSFIELDS`
--

DROP TABLE IF EXISTS `EBOOKVA`.`PRODUCTSFIELDS`;
CREATE TABLE  `EBOOKVA`.`PRODUCTSFIELDS` (
  `PRODUCTSFIELDS_ID` int(10) unsigned NOT NULL auto_increment,
  `PRODUCTSFIELDS_ALIAS` char(50) NOT NULL default '',
  `PRODUCTSFIELDS_TITLE` char(50) NOT NULL default '',
  PRIMARY KEY  (`PRODUCTSFIELDS_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Definition of table `EBOOKVA`.`SQLLOG`
--

DROP TABLE IF EXISTS `EBOOKVA`.`SQLLOG`;
CREATE TABLE  `EBOOKVA`.`SQLLOG` (
  `SQLLOG_QUERY` varchar(255) NOT NULL,
  `SQLLOG_QUERYTYPE` tinyint(3) unsigned NOT NULL,
  `SQLLOG_RUNTIME` float(4,4) unsigned NOT NULL default '0.0000',
  `SQLLOG_STARTTIME` timestamp NOT NULL default CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
