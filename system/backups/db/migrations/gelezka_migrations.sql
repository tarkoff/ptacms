-- 30-06-2009
CREATE TABLE `CATALOG_FIELDSGROUPS` (
  `FIELDSGROUPS_ID` int(10) unsigned NOT NULL auto_increment,
  `FIELDSGROUPS_CATEGORYID` int(10) unsigned NOT NULL,
  `FIELDSGROUPS_ALIAS` char(50) default NULL,
  `FIELDSGROUPS_TITLE` char(100) default NULL,
  PRIMARY KEY  (`FIELDSGROUPS_ID`),
  KEY `FIELDSGROUPS_CATEGORYID` (`FIELDSGROUPS_CATEGORYID`),
  CONSTRAINT `CATALOG_FIELDSGROUPS_ibfk_1` FOREIGN KEY (`FIELDSGROUPS_CATEGORYID`) REFERENCES `CATALOG_CATEGORIES` (`CATEGORIES_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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