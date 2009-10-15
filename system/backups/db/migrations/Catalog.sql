-- 30-06-2009
DROP TABLE IF EXISTS `CATALOG_FIELDSGROUPS`;
CREATE TABLE `CATALOG_FIELDSGROUPS` (
  `FIELDSGROUPS_ID` int(10) unsigned NOT NULL auto_increment,
  `FIELDSGROUPS_CATEGORYID` int(10) unsigned NOT NULL,
  `FIELDSGROUPS_ALIAS` char(50) default NULL,
  `FIELDSGROUPS_TITLE` char(100) default NULL,
  PRIMARY KEY  (`FIELDSGROUPS_ID`),
  KEY `FIELDSGROUPS_CATEGORYID` (`FIELDSGROUPS_CATEGORYID`),
  CONSTRAINT `CATALOG_FIELDSGROUPS_ibfk_1` FOREIGN KEY (`FIELDSGROUPS_CATEGORYID`) REFERENCES `CATALOG_CATEGORIES` (`CATEGORIES_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `CATALOG_FIELDGROUPFIELDS`;
CREATE TABLE `CATALOG_FIELDGROUPFIELDS` (
  `FIELDGROUPFIELDS_ID` int(10) unsigned NOT NULL auto_increment,
  `FIELDGROUPFIELDS_GROUPID` int(10) unsigned NOT NULL,
  `FIELDGROUPFIELDS_FIELDID` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`FIELDGROUPFIELDS_ID`),
  KEY `FIELDGROUPFIELDS_GROUPID` (`FIELDGROUPFIELDS_GROUPID`),
  KEY `FIELDGROUPFIELDS_FIELDID` (`FIELDGROUPFIELDS_FIELDID`),
  CONSTRAINT `CATALOG_FIELDGROUPFIELDS_ibfk_1` FOREIGN KEY (`FIELDGROUPFIELDS_GROUPID`) REFERENCES `CATALOG_FIELDSGROUPS` (`FIELDSGROUPS_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CATALOG_FIELDGROUPFIELDS_ibfk_2` FOREIGN KEY (`FIELDGROUPFIELDS_FIELDID`) REFERENCES `CATALOG_CATEGORIESFIELDS` (`CATEGORIESFIELDS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

alter table CATALOG_PRODUCTS add  PRODUCTS_CATEGORYID int unsigned not null after PRODUCTS_ID, 
	add foreign key (PRODUCTS_CATEGORYID) references CATALOG_CATEGORIES (CATEGORIES_ID) on delete cascade on update cascade;

-- 04-07-2009
alter table CATALOG_FIELDSGROUPS add FIELDSGROUPS_SORTORDER int unsigned not null default 0;

-- 11-07-2009
alter table CATALOG_FIELDSGROUPS drop FIELDSGROUPS_ALIAS;
alter table CATALOG_PRODUCTS modify PRODUCTS_URL text(500), modify PRODUCTS_DRIVERSURL text(500);

-- 13-07-2009
alter table CATALOG_PRODUCTS drop foreign key CATALOG_PRODUCTS_ibfk_3;
alter table CATALOG_PRODUCTS drop PRODUCTS_CATEGORYID;
alter table CATALOG_PRODUCTCATEGORIES add PRODUCTCATEGORIES_ISDEFAULT tinyint(1) unsigned not null default 0;

-- 24-07-2009
alter table CATALOG_PRODUCTS add unique(PRODUCTS_ALIAS);
alter table CATALOG_BRANDS add unique(BRANDS_ALIAS);

-- 28-07-2009
alter table CATALOG_PRODUCTSFIELDSVALUES add unique `FIELD_VALUE` (PRODUCTSFIELDSVALUES_FIELDID, PRODUCTSFIELDSVALUES_VALUE);
alter table CATALOG_FIELDGROUPFIELDS add unique GROUP_FIELD (FIELDGROUPFIELDS_GROUPID, FIELDGROUPFIELDS_FIELDID);

-- 02-10-2009
CREATE TABLE `CATALOG_CURRENCY` (
  `CURRENCY_ID` smallint(5) unsigned NOT NULL auto_increment,
  `CURRENCY_TITLE` char(50) NOT NULL default '',
  `CURRENCY_REDUCTION` char(10) NOT NULL default '',
  PRIMARY KEY  (`CURRENCY_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `CATALOG_PRICES` (
  `PRICES_ID` int(10) unsigned NOT NULL auto_increment,
  `PRICES_PRODUCTID` int(10) unsigned NOT NULL,
  `PRICES_USERID` int(10) unsigned NOT NULL,
  `PRICES_PRICE` float(10,2) unsigned default '0.00',
  `PRICES_DESCR` char(255) default NULL,
  `PRICES_URL` text,
  `PRICES_DATEFROM` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `PRICES_DATETO` timestamp NOT NULL default '0000-00-00 00:00:00',
  `PRICES_CURRENCY` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`PRICES_ID`),
  KEY `PRICES_PRODUCTID` (`PRICES_PRODUCTID`),
  KEY `PRICES_USERID` (`PRICES_USERID`),
  KEY `PRICES_CURRENCY` (`PRICES_CURRENCY`),
  CONSTRAINT `CATALOG_PRICES_ibfk_3` FOREIGN KEY (`PRICES_CURRENCY`) REFERENCES `CATALOG_CURRENCY` (`CURRENCY_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CATALOG_PRICES_ibfk_1` FOREIGN KEY (`PRICES_PRODUCTID`) REFERENCES `CATALOG_PRODUCTS` (`PRODUCTS_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CATALOG_PRICES_ibfk_2` FOREIGN KEY (`PRICES_USERID`) REFERENCES `USERS` (`USERS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 15-10-2009
CREATE TABLE `CATALOG_FILTERFIELDS` (
  `FILTERFIELD_ID` int(10) unsigned NOT NULL auto_increment,
  `FILTERFIELD_CATEGORYID` int(10) unsigned NOT NULL,
  `FILTERFIELD_CATEGORYFIELDID` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`FILTERFIELD_ID`),
  KEY `FILTERFIELD_CATEGORYID` (`FILTERFIELD_CATEGORYID`),
  KEY `FILTERFIELD_CATEGORYFIELDID` (`FILTERFIELD_CATEGORYFIELDID`),
  CONSTRAINT `CATALOG_FILTERFIELDS_ibfk_1` FOREIGN KEY (`FILTERFIELD_CATEGORYID`) REFERENCES `CATALOG_CATEGORIES` (`CATEGORIES_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CATALOG_FILTERFIELDS_ibfk_2` FOREIGN KEY (`FILTERFIELD_CATEGORYFIELDID`) REFERENCES `CATALOG_CATEGORIESFIELDS` (`CATEGORIESFIELDS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

