-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.49-1ubuntu8.1


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,ANSI_QUOTES' */;


--
-- Create schema gelezkan_gelezka
--

CREATE DATABASE IF NOT EXISTS gelezkan_gelezka;
USE gelezkan_gelezka;



--
-- Definition of table "GEO_BLOCKS"
--

DROP TABLE IF EXISTS "GEO_BLOCKS";
CREATE TABLE "GEO_BLOCKS" (
  "GEO_STARTNUM" int(10) unsigned NOT NULL,
  "GEO_ENDNUM" int(10) unsigned NOT NULL,
  "GEO_STARTIP" char(15) NOT NULL DEFAULT '',
  "GEO_ENDIP" char(15) NOT NULL DEFAULT '',
  "GEO_CODE" char(2) NOT NULL DEFAULT '',
  "GEO_CITY" char(50) NOT NULL DEFAULT '',
  "GEO_STATE" char(100) NOT NULL DEFAULT '',
  "GEO_REGION" char(50) NOT NULL DEFAULT '',
  "GEO_TARGETID" int(10) unsigned NOT NULL,
  PRIMARY KEY ("GEO_STARTNUM","GEO_ENDNUM")
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Definition of table "MENUS"
--

DROP TABLE IF EXISTS "MENUS";
CREATE TABLE "MENUS" (
  "MENUS_ID" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "MENUS_ALIAS" char(30) NOT NULL,
  "MENUS_PARENTID" int(10) unsigned DEFAULT NULL,
  "MENUS_TITLE" varchar(50) NOT NULL,
  "MENUS_URL" varchar(255) NOT NULL,
  PRIMARY KEY ("MENUS_ID")
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table "MENUS"
--

/*!40000 ALTER TABLE "MENUS" DISABLE KEYS */;
/*!40000 ALTER TABLE "MENUS" ENABLE KEYS */;



--
-- Definition of table "SITES"
--

DROP TABLE IF EXISTS "SITES";
CREATE TABLE "SITES" (
  "SITE_ID" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "SITE_TITLE" char(50) NOT NULL DEFAULT '',
  "SITE_URL" char(100) NOT NULL DEFAULT 'http://',
  PRIMARY KEY ("SITE_ID")
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table "SITES"
--

/*!40000 ALTER TABLE "SITES" DISABLE KEYS */;
INSERT INTO "SITES" VALUES   (1,'Admin Panel','http://www.admin.gelezka.net');
INSERT INTO "SITES" VALUES   (2,'User Area','http://www.gelezka.net');
/*!40000 ALTER TABLE "SITES" ENABLE KEYS */;


--
-- Definition of table "SQLLOG"
--

DROP TABLE IF EXISTS "SQLLOG";
CREATE TABLE "SQLLOG" (
  "SQLLOG_QUERY" text NOT NULL,
  "SQLLOG_QUERYTYPE" tinyint(3) unsigned NOT NULL,
  "SQLLOG_RUNTIME" float(4,4) unsigned NOT NULL DEFAULT '0.0000',
  "SQLLOG_STARTTIME" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "SQLLOG_SITEID" tinyint(3) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table "SQLLOG"
--

/*!40000 ALTER TABLE "SQLLOG" DISABLE KEYS */;
/*!40000 ALTER TABLE "SQLLOG" ENABLE KEYS */;


--
-- Definition of table "THEMES"
--

DROP TABLE IF EXISTS "THEMES";
CREATE TABLE "THEMES" (
  "THEME_ID" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "THEME_SITEID" int(10) unsigned NOT NULL,
  "THEME_TITLE" char(50) NOT NULL DEFAULT '',
  "THEME_ACTIVE" tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY ("THEME_ID"),
  KEY "THEME_SITEID" ("THEME_SITEID"),
  CONSTRAINT "THEMES_ibfk_1" FOREIGN KEY ("THEME_SITEID") REFERENCES "SITES" ("SITE_ID") ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table "THEMES"
--

/*!40000 ALTER TABLE "THEMES" DISABLE KEYS */;
INSERT INTO "THEMES" VALUES   (1,1,'redmond',0);
INSERT INTO "THEMES" VALUES   (2,1,'ui-lightness',1);
INSERT INTO "THEMES" VALUES   (3,1,'cupertino',0);
INSERT INTO "THEMES" VALUES   (4,1,'dark-hive',0);
INSERT INTO "THEMES" VALUES   (5,1,'eggplant',0);
INSERT INTO "THEMES" VALUES   (6,1,'flick',0);
INSERT INTO "THEMES" VALUES   (7,1,'pepper-grinder',0);
INSERT INTO "THEMES" VALUES   (8,1,'smoothness',0);
INSERT INTO "THEMES" VALUES   (9,1,'sunny',0);
INSERT INTO "THEMES" VALUES   (10,1,'ui-darkness',0);
/*!40000 ALTER TABLE "THEMES" ENABLE KEYS */;


--
-- Definition of table "USERGROUPS"
--

DROP TABLE IF EXISTS "USERGROUPS";
CREATE TABLE "USERGROUPS" (
  "USERGROUPS_ID" smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  "USERGROUPS_NAME" char(50) NOT NULL,
  PRIMARY KEY ("USERGROUPS_ID")
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table "USERGROUPS"
--

/*!40000 ALTER TABLE "USERGROUPS" DISABLE KEYS */;
INSERT INTO "USERGROUPS" VALUES   (1,'Admins');
INSERT INTO "USERGROUPS" VALUES   (2,'Mamagers');
INSERT INTO "USERGROUPS" VALUES   (3,'Guests');
INSERT INTO "USERGROUPS" VALUES   (4,'Market');
/*!40000 ALTER TABLE "USERGROUPS" ENABLE KEYS */;


--
-- Definition of table "USERS"
--

DROP TABLE IF EXISTS "USERS";
CREATE TABLE "USERS" (
  "USERS_ID" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "USERS_GROUPID" smallint(5) unsigned NOT NULL,
  "USERS_LOGIN" char(50) NOT NULL,
  "USERS_PASSWORD" char(32) DEFAULT NULL,
  "USERS_REGISTERDATE" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY ("USERS_ID"),
  KEY "USERS_fk1" ("USERS_GROUPID"),
  CONSTRAINT "USERS_fk1" FOREIGN KEY ("USERS_GROUPID") REFERENCES "USERGROUPS" ("USERGROUPS_ID") ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table "USERS"
--

/*!40000 ALTER TABLE "USERS" DISABLE KEYS */;
INSERT INTO "USERS" VALUES   (1,1,'admin','cc0bd768e0ed0ce3060f618897bd8f80','2009-03-14 15:45:15');
INSERT INTO "USERS" VALUES   (2,3,'Guest','0144712dd81be0c3d9724f5e56ce6685','2009-10-03 00:46:19');
/*!40000 ALTER TABLE "USERS" ENABLE KEYS */;


--
-- Definition of table "USERSTAT"
--

DROP TABLE IF EXISTS "USERSTAT";
CREATE TABLE "USERSTAT" (
  "USERSTAT_ID" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "USERSTAT_USERID" int(10) unsigned NOT NULL,
  "USERSTAT_LOGINDATE" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  "USERSTAT_LASTCLICKDATE" timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  "USERSTAT_SESSIONHASH" char(32) NOT NULL,
  PRIMARY KEY ("USERSTAT_ID"),
  KEY "USERSTAT_fk1" ("USERSTAT_USERID"),
  KEY "USERSTAT_SESSIONHASH" ("USERSTAT_SESSIONHASH"),
  CONSTRAINT "USERSTAT_fk1" FOREIGN KEY ("USERSTAT_USERID") REFERENCES "USERS" ("USERS_ID") ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table "USERSTAT"
--

/*!40000 ALTER TABLE "USERSTAT" DISABLE KEYS */;
INSERT INTO "USERSTAT" VALUES   (2,1,'2011-03-29 23:40:29','2011-03-29 23:40:29','4586740c8af53a89fefec17a2653e56e');
/*!40000 ALTER TABLE "USERSTAT" ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
