-- 22-07-2009
DROP TABLE IF EXISTS `POSTS`;
CREATE TABLE `POSTS` (
  `POSTS_ID` int(10) unsigned NOT NULL auto_increment,
  `POSTS_PRODUCTID` int(10) unsigned NOT NULL,
  `POSTS_AUTHOR` varchar(50) NOT NULL,
  `POSTS_POST` text NOT NULL,
  `POSTS_DATE` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`POSTS_ID`),
  KEY `POSTS_PRODUCTID` (`POSTS_PRODUCTID`),
  CONSTRAINT `CATALOG_POSTS_ibfk_1` FOREIGN KEY (`POSTS_PRODUCTID`) REFERENCES `CATALOG_PRODUCTS` (`PRODUCTS_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 07-08-2009
alter table USERSTAT add index `USERSTAT_SESSIONHASH` (USERSTAT_SESSIONHASH);
truncate table SQLLOG;
alter table SQLLOG add SQLLOG_SITEID tinyint unsigned not null default 0;

-- 29-09-2009
insert into USERGROUPS (USERGROUPS_NAME) values ('Guests'), ('Market');
select USERGROUPS_ID from USERGROUPS where USERGROUPS_NAME = 'Guests' into @groupId;
insert into USERS (USERS_GROUPID, USERS_LOGIN, USERS_PASSWORD) values (@groupId, 'Guest', '0144712dd81be0c3d9724f5e56ce6685');
-- rename table CATALOG_POSTS to POSTS;