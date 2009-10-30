-- 28-10-2009
alter table MIXMARKET_CATEGORIES add CATEGORIES_CATALOGID int unsigned not null default 0, add index (CATEGORIES_CATALOGID);
alter table MIXMARKET_BRANDS add BRANDS_CATALOGID int unsigned not null default 0, add index (BRANDS_CATALOGID);
alter table MIXMARKET_OFFERS add OFFERS_CATALOGID int unsigned not null default 0, add index (OFFERS_CATALOGID);
