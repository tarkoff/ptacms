-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.75-0ubuntu10.2


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

--
-- Definition of table `CATALOG_BRANDS`
--

DROP TABLE IF EXISTS `CATALOG_BRANDS`;
CREATE TABLE `CATALOG_BRANDS` (
  `BRANDS_ID` int(10) unsigned NOT NULL auto_increment,
  `BRANDS_ALIAS` char(30) NOT NULL,
  `BRANDS_TITLE` varchar(50) NOT NULL,
  `BRANDS_URL` varchar(255) default NULL,
  PRIMARY KEY  (`BRANDS_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Definition of table `CATALOG_CATEGORIES`
--

DROP TABLE IF EXISTS `CATALOG_CATEGORIES`;
CREATE TABLE `CATALOG_CATEGORIES` (
  `CATEGORIES_ID` int(10) unsigned NOT NULL auto_increment,
  `CATEGORIES_PARENTID` int(10) unsigned default NULL,
  `CATEGORIES_ALIAS` char(30) NOT NULL,
  `CATEGORIES_TITLE` char(80) NOT NULL default '',
  `CATEGORIES_ISPUBLIC` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`CATEGORIES_ID`),
  UNIQUE KEY `CATEGORIES_ALIAS` (`CATEGORIES_ALIAS`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Definition of table `CATALOG_CATEGORIESFIELDS`
--

DROP TABLE IF EXISTS `CATALOG_CATEGORIESFIELDS`;
CREATE TABLE `CATALOG_CATEGORIESFIELDS` (
  `CATEGORIESFIELDS_ID` int(10) unsigned NOT NULL auto_increment,
  `CATEGORIESFIELDS_CATEGORYID` int(10) unsigned NOT NULL,
  `CATEGORIESFIELDS_FIELDID` int(10) unsigned NOT NULL,
  `CATEGORIESFIELDS_SORTORDER` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`CATEGORIESFIELDS_ID`),
  UNIQUE KEY `CATEGORIESFIELDS_CATEGORYID_2` (`CATEGORIESFIELDS_CATEGORYID`,`CATEGORIESFIELDS_FIELDID`),
  KEY `CATEGORIESFIELDS_CATEGORYID` (`CATEGORIESFIELDS_CATEGORYID`),
  KEY `CATEGORIESFIELDS_FIELDID` (`CATEGORIESFIELDS_FIELDID`),
  CONSTRAINT `CATALOG_CATEGORIESFIELDS_ibfk_1` FOREIGN KEY (`CATEGORIESFIELDS_CATEGORYID`) REFERENCES `CATALOG_CATEGORIES` (`CATEGORIES_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CATALOG_CATEGORIESFIELDS_ibfk_2` FOREIGN KEY (`CATEGORIESFIELDS_FIELDID`) REFERENCES `CATALOG_PRODUCTSFIELDS` (`PRODUCTSFIELDS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Definition of table `CATALOG_PHOTOS`
--

DROP TABLE IF EXISTS `CATALOG_PHOTOS`;
CREATE TABLE `CATALOG_PHOTOS` (
  `PHOTOS_ID` int(10) unsigned NOT NULL auto_increment,
  `PHOTOS_PRODUCTID` int(10) unsigned NOT NULL,
  `PHOTOS_DEFAULT` tinyint(1) NOT NULL default '0',
  `PHOTOS_PHOTO` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`PHOTOS_ID`),
  KEY `PHOTOS_PRODUCTID` (`PHOTOS_PRODUCTID`),
  CONSTRAINT `CATALOG_PHOTOS_ibfk_1` FOREIGN KEY (`PHOTOS_PRODUCTID`) REFERENCES `CATALOG_PRODUCTS` (`PRODUCTS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Definition of table `CATALOG_PRODUCTCATEGORIES`
--

DROP TABLE IF EXISTS `CATALOG_PRODUCTCATEGORIES`;
CREATE TABLE `CATALOG_PRODUCTCATEGORIES` (
  `PRODUCTCATEGORIES_ID` int(10) unsigned NOT NULL auto_increment,
  `PRODUCTCATEGORIES_CATEGORYID` int(10) unsigned NOT NULL,
  `PRODUCTCATEGORIES_PRODUCTID` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`PRODUCTCATEGORIES_ID`),
  KEY `CATALOG_PRODUCTCATEGORIES_ibfk_1` (`PRODUCTCATEGORIES_CATEGORYID`),
  KEY `CATALOG_PRODUCTCATEGORIES_ibfk_2` (`PRODUCTCATEGORIES_PRODUCTID`),
  CONSTRAINT `CATALOG_PRODUCTCATEGORIES_ibfk_1` FOREIGN KEY (`PRODUCTCATEGORIES_CATEGORYID`) REFERENCES `CATALOG_CATEGORIES` (`CATEGORIES_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CATALOG_PRODUCTCATEGORIES_ibfk_2` FOREIGN KEY (`PRODUCTCATEGORIES_PRODUCTID`) REFERENCES `CATALOG_PRODUCTS` (`PRODUCTS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Definition of table `CATALOG_PRODUCTS`
--

DROP TABLE IF EXISTS `CATALOG_PRODUCTS`;
CREATE TABLE `CATALOG_PRODUCTS` (
  `PRODUCTS_ID` int(10) unsigned NOT NULL auto_increment,
  `PRODUCTS_BRANDID` int(10) unsigned default NULL,
  `PRODUCTS_ALIAS` char(50) NOT NULL,
  `PRODUCTS_TITLE` varchar(100) NOT NULL,
  `PRODUCTS_SHORTDESCR` text,
  `PRODUCTS_DATE` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `PRODUCTS_URL` varchar(255) NOT NULL,
  `PRODUCTS_DRIVERSURL` varchar(255) NOT NULL,
  PRIMARY KEY  (`PRODUCTS_ID`),
  KEY `PRODUCTS_ibfk_2` (`PRODUCTS_BRANDID`),
  CONSTRAINT `CATALOG_PRODUCTS_ibfk_2` FOREIGN KEY (`PRODUCTS_BRANDID`) REFERENCES `CATALOG_BRANDS` (`BRANDS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Definition of table `CATALOG_PRODUCTSFIELDS`
--

DROP TABLE IF EXISTS `CATALOG_PRODUCTSFIELDS`;
CREATE TABLE `CATALOG_PRODUCTSFIELDS` (
  `PRODUCTSFIELDS_ID` int(10) unsigned NOT NULL auto_increment,
  `PRODUCTSFIELDS_ALIAS` char(50) NOT NULL default '',
  `PRODUCTSFIELDS_TITLE` char(100) NOT NULL default '',
  `PRODUCTSFIELDS_FIELDTYPE` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`PRODUCTSFIELDS_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Definition of table `CATALOG_PRODUCTSFIELDSVALUES`
--

DROP TABLE IF EXISTS `CATALOG_PRODUCTSFIELDSVALUES`;
CREATE TABLE `CATALOG_PRODUCTSFIELDSVALUES` (
  `PRODUCTSFIELDSVALUES_ID` int(10) unsigned NOT NULL auto_increment,
  `PRODUCTSFIELDSVALUES_FIELDID` int(10) unsigned NOT NULL,
  `PRODUCTSFIELDSVALUES_VALUE` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`PRODUCTSFIELDSVALUES_ID`),
  KEY `CATALOG_PRODUCTSFIELDSVALUES_ibfk_1` (`PRODUCTSFIELDSVALUES_FIELDID`),
  CONSTRAINT `CATALOG_PRODUCTSFIELDSVALUES_ibfk_1` FOREIGN KEY (`PRODUCTSFIELDSVALUES_FIELDID`) REFERENCES `CATALOG_PRODUCTSFIELDS` (`PRODUCTSFIELDS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Definition of table `CATALOG_PRODUCTSSTAT`
--

DROP TABLE IF EXISTS `CATALOG_PRODUCTSSTAT`;
CREATE TABLE `CATALOG_PRODUCTSSTAT` (
  `PRODUCTSSTAT_PRODUCTID` int(10) unsigned NOT NULL,
  `PRODUCTSSTAT_VIEWS` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`PRODUCTSSTAT_PRODUCTID`),
  CONSTRAINT `CATALOG_PRODUCTSSTAT_ibfk_1` FOREIGN KEY (`PRODUCTSSTAT_PRODUCTID`) REFERENCES `CATALOG_PRODUCTS` (`PRODUCTS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Definition of table `CATALOG_PRODUCTSVALUES`
--

DROP TABLE IF EXISTS `CATALOG_PRODUCTSVALUES`;
CREATE TABLE `CATALOG_PRODUCTSVALUES` (
  `PRODUCTSVALUES_ID` int(10) unsigned NOT NULL auto_increment,
  `PRODUCTSVALUES_PRODUCTID` int(10) unsigned NOT NULL,
  `PRODUCTSVALUES_FIELDID` int(10) unsigned NOT NULL,
  `PRODUCTSVALUES_VALUEID` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`PRODUCTSVALUES_ID`),
  KEY `PRODUCTSVALUES_ibfk_1` (`PRODUCTSVALUES_PRODUCTID`),
  KEY `PRODUCTSVALUES_FIELDID` (`PRODUCTSVALUES_FIELDID`),
  KEY `CATALOG_PRODUCTSVALUES_ibfk_3` (`PRODUCTSVALUES_VALUEID`),
  CONSTRAINT `CATALOG_PRODUCTSVALUES_ibfk_1` FOREIGN KEY (`PRODUCTSVALUES_PRODUCTID`) REFERENCES `CATALOG_PRODUCTS` (`PRODUCTS_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CATALOG_PRODUCTSVALUES_ibfk_2` FOREIGN KEY (`PRODUCTSVALUES_FIELDID`) REFERENCES `CATALOG_CATEGORIESFIELDS` (`CATEGORIESFIELDS_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CATALOG_PRODUCTSVALUES_ibfk_3` FOREIGN KEY (`PRODUCTSVALUES_VALUEID`) REFERENCES `CATALOG_PRODUCTSFIELDSVALUES` (`PRODUCTSFIELDSVALUES_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 3072 kB; (`PRODUCTSVALUES_VALUEID`) REFER `GELE';


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;