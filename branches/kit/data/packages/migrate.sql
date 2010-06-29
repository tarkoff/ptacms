SET NAMES UTF8;

-- Import brands
insert into CATALOG_BRANDS select * from gelezkan_gelezka.CATALOG_BRANDS;

-- Import categories
-- INSERT INTO CATALOG_CATEGORIES (CATEGORIES_ID,CATEGORIES_PARENTID,CATEGORIES_TITLE,CATEGORIES_ALIAS) SELECT CATEGORIES_ID,CATEGORIES_PARENTID,CATEGORIES_TITLE,CATEGORIES_ALIAS FROM gelezkan_gelezka.CATALOG_CATEGORIES;

-- Import field groups
INSERT INTO gelezkaz.CATALOG_FIELDGROUPS (FIELDGROUPS_ID, FIELDGROUPS_ALIAS, FIELDGROUPS_TITLE) SELECT FIELDSGROUPS_ID, CONCAT('alias', FIELDSGROUPS_ID),FIELDSGROUPS_TITLE FROM gelezkan_gelezka.CATALOG_FIELDSGROUPS -- GROUP BY FIELDSGROUPS_TITLE;

-- Import category groups
INSERT INTO gelezkaz.CATALOG_CATEGORYGROUPS (/*CATEGORYGROUPS_ID,*/ CATEGORYGROUPS_CATEGORYID, CATEGORYGROUPS_GROUPID, CATEGORYGROUPS_SORTORDER) 
SELECT  g2.FIELDSGROUPS_CATEGORYID, g2.FIELDSGROUPS_ID, g2.FIELDSGROUPS_SORTORDER FROM gelezkan_gelezka.CATALOG_FIELDSGROUPS as g2;

-- Import fields
INSERT INTO CATALOG_FIELDS SELECT PRODUCTSFIELDS_ID, PRODUCTSFIELDS_ALIAS, PRODUCTSFIELDS_TITLE, PRODUCTSFIELDS_FIELDTYPE FROM gelezkan_gelezka.CATALOG_PRODUCTSFIELDS;

-- Import fields values
INSERT INTO CATALOG_FIELDSVALUES (FIELDSVALUES_ID, FIELDSVALUES_FIELDID, FIELDSVALUES_VALUE) SELECT PRODUCTSFIELDSVALUES_ID, PRODUCTSFIELDSVALUES_FIELDID, PRODUCTSFIELDSVALUES_VALUE FROM gelezkan_gelezka.CATALOG_PRODUCTSFIELDSVALUES;

-- Import products
INSERT INTO gelezkaz.CATALOG_PRODUCTS (PRODUCTS_ID, PRODUCTS_BRANDID, PRODUCTS_AUTHORID, PRODUCTS_ALIAS, PRODUCTS_TITLE, PRODUCTS_SHORTDESCR, PRODUCTS_DATE, PRODUCTS_URL, PRODUCTS_DRIVERSURL) SELECT PRODUCTS_ID, PRODUCTS_BRANDID, 1, PRODUCTS_ALIAS, PRODUCTS_TITLE, PRODUCTS_SHORTDESCR, PRODUCTS_DATE, PRODUCTS_URL, PRODUCTS_DRIVERSURL FROM gelezkan_gelezka.CATALOG_PRODUCTS;

-- Import product category
INSERT INTO gelezkaz.CATALOG_PRODUCTCATEGORIES (PRODUCTCATEGORIES_ID, PRODUCTCATEGORIES_PRODUCTID, PRODUCTCATEGORIES_CATEGORYID, PRODUCTCATEGORIES_ISDEFAULT) SELECT PRODUCTCATEGORIES_ID, PRODUCTCATEGORIES_PRODUCTID, PRODUCTCATEGORIES_CATEGORYID, PRODUCTCATEGORIES_ISDEFAULT FROM gelezkan_gelezka.CATALOG_PRODUCTCATEGORIES;

-- Import Group Fields
 insert into gelezkaz.CATALOG_GROUPFIELDS (/*GROUPFIELDS_ID,*/ GROUPFIELDS_CATEGORYGROUPID, GROUPFIELDS_FIELDID, GROUPFIELDS_SORTORDER)
SELECT /*cf.CATEGORIESFIELDS_ID,*/ cg.CATEGORYGROUPS_ID, cf.CATEGORIESFIELDS_FIELDID, cf.CATEGORIESFIELDS_SORTORDER 
FROM gelezkan_gelezka.CATALOG_CATEGORIESFIELDS as cf  
inner join gelezkan_gelezka.CATALOG_FIELDGROUPFIELDS as fgf on fgf.FIELDGROUPFIELDS_FIELDID = cf.CATEGORIESFIELDS_ID
inner join gelezkaz.CATALOG_CATEGORYGROUPS as cg on cg.CATEGORYGROUPS_GROUPID = fgf.FIELDGROUPFIELDS_GROUPID


-- Import product values
 insert into gelezkaz.CATALOG_PRODUCTVALUES (PRODUCTVALUES_PRODUCTID, PRODUCTVALUES_FIELDID, PRODUCTVALUES_VALUEID)
SELECT pv.PRODUCTSVALUES_PRODUCTID, gf.GROUPFIELDS_ID, pv.PRODUCTSVALUES_VALUEID
FROM gelezkan_gelezka.CATALOG_PRODUCTSVALUES pv
 inner join gelezkaz.CATALOG_FIELDSVALUES fv on fv.FIELDSVALUES_ID  = pv.PRODUCTSVALUES_VALUEID
 inner join gelezkaz.CATALOG_GROUPFIELDS gf on gf.GROUPFIELDS_FIELDID = fv.FIELDSVALUES_FIELDID
group by pv.PRODUCTSVALUES_ID
order by pv.PRODUCTSVALUES_PRODUCTID;

-- Import images
insert into gelezkaz.CATALOG_PHOTOS (PHOTOS_PRODUCTID, PHOTOS_ISDEFAULT, PHOTOS_FILE)
select p1.PHOTOS_PRODUCTID, p1.PHOTOS_DEFAULT, REPLACE(p1.PHOTOS_PHOTO, 'images/', 'images/catalog/')
from gelezkan_gelezka.CATALOG_PHOTOS p1;

