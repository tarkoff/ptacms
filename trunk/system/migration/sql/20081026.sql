create database if not exists `UDL` default character set utf8;
use `UDL`;

CREATE TABLE `CATEGORYS` (
  `CATEGORY_ID` int(10) unsigned NOT NULL auto_increment,
  `CATEGORY_PARENTID` int(10) unsigned NOT NULL,
  `CATEGORY_TITLE` char(60) NOT NULL default '',
  PRIMARY KEY  (`CATEGORY_ID`),
  KEY `CATEGORY_PARENTID` (`CATEGORY_PARENTID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `MENUS` (
  `MENUID` int(10) unsigned NOT NULL auto_increment,
  `MENUTITLE` char(50) NOT NULL default '',
  `PARENTMENUID` int(10) unsigned NOT NULL default '0',
  `SITEID` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`MENUID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8



