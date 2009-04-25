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

DROP TABLE IF EXISTS `EBOOKVA`.`CATEGORIES`;
CREATE TABLE  `EBOOKVA`.`CATEGORIES` (
  `CATEGORIES_ID` int(10) unsigned NOT NULL auto_increment,
  `CATEGORIES_PARENTID` int(10) unsigned default NULL,
  `CATEGORIES_TITLE` char(80) NOT NULL default '',
  PRIMARY KEY  (`CATEGORIES_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;


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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `EBOOKVA`.`PRODUCTS`;
CREATE TABLE  `EBOOKVA`.`PRODUCTS` (
  `PRODUCTS_ID` int(10) unsigned NOT NULL auto_increment,
  `PRODUCTS_CATEGORYID` int(10) unsigned NOT NULL,
  `PRODUCTS_MANUFACTURERID` int(10) unsigned default NULL,
  `PRODUCTS_TITLE` varchar(100) NOT NULL,
  `PRODUCTS_IMAGE` varchar(255) NOT NULL default '',
  `PRODUCTS_SHORTDESCR` text,
  `PRODUCTS_DATE` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `PRODUCTS_URL` varchar(255) NOT NULL,
  PRIMARY KEY  (`PRODUCTS_ID`),
  KEY `PRODUCTS_CATEGORYID` (`PRODUCTS_CATEGORYID`),
  CONSTRAINT `PRODUCTS_ibfk_1` FOREIGN KEY (`PRODUCTS_CATEGORYID`) REFERENCES `CATEGORIES` (`CATEGORIES_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `EBOOKVA`.`PRODUCTSFIELDS`;
CREATE TABLE  `EBOOKVA`.`PRODUCTSFIELDS` (
  `PRODUCTSFIELDS_ID` int(10) unsigned NOT NULL auto_increment,
  `PRODUCTSFIELDS_ALIAS` char(50) NOT NULL default '',
  `PRODUCTSFIELDS_TITLE` char(50) NOT NULL default '',
  `PRODUCTSFIELDS_FIELDTYPE` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`PRODUCTSFIELDS_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `EBOOKVA`.`PRODUCTSVALUES`;
CREATE TABLE  `EBOOKVA`.`PRODUCTSVALUES` (
  `PRODUCTSVALUES_ID` int(10) unsigned NOT NULL auto_increment,
  `PRODUCTSVALUES_PRODUCTID` int(10) unsigned NOT NULL,
  `PRODUCTSVALUES_FIELDID` int(10) unsigned NOT NULL,
  `PRODUCTSVALUES_VALUE` varchar(255) default NULL,
  PRIMARY KEY  (`PRODUCTSVALUES_ID`),
  KEY `PRODUCTSVALUES_ibfk_1` (`PRODUCTSVALUES_PRODUCTID`),
  KEY `PRODUCTSVALUES_FIELDID` (`PRODUCTSVALUES_FIELDID`),
  CONSTRAINT `PRODUCTSVALUES_ibfk_2` FOREIGN KEY (`PRODUCTSVALUES_FIELDID`) REFERENCES `CATEGORIESFIELDS` (`CATEGORIESFIELDS_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `PRODUCTSVALUES_ibfk_1` FOREIGN KEY (`PRODUCTSVALUES_PRODUCTID`) REFERENCES `PRODUCTS` (`PRODUCTS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;


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