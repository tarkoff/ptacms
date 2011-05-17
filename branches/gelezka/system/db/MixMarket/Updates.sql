-- Add fields to MixMarket import
alter table MIXMART_ADVERTIZERS add ADVERTIZERS_PRICEMARKUP smallint not null default 0, add ADVERTIZERS_URL char(255);
alter table MIXMART_ADVERTIZERS add ADVERTIZERS_UPDATED tinyint(1) unsigned not null default 0;
alter table MIXMARKET_ADVREGIONGEOTARGET add ADVREGIONGEOTARGET_PRICEMARKUP smallint not null default 0;
alter table MIXMARKET_OFFERS add  OFFERS_LINKED tinyint(1) unsigned not null default 0;
alter table MIXMARKET_BRANDS add BRANDS_LINKED tinyint(1) unsigned not null default 0;
