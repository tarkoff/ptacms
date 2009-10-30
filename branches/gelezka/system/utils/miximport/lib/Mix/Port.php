<?php
/**
 * Class for porting parsed MixMarket xml file to PTA Catalog
 *
 * @package    Tools
 * @copyright  Copyright (c) 2009 Taras Pavuk (tpavuk@gmail.com)
 * @license    BSD License
 */

require_once 'Mix/Abstract.php';

class Mix_Port extends Mix_Abstract
{
	const QUERY_LIMIT = 100;

	public function portCategories()
	{
		$this->alert('Categories porting started');

		$db = $this->_db;
		$catalogCategories = $db->fetchPairs(
			'select CATEGORIES_ID, CATEGORIES_TITLE '
				. 'from CATALOG_CATEGORIES where CATEGORIES_ISPUBLIC = 1'
		);

		$db->beginTransaction();
		foreach ($catalogCategories as $catId => $catTitle) {
			$sql = 'update MIXMARKET_CATEGORIES set CATEGORIES_CATALOGID = ' . $catId . ' where ';
			$keywords = explode(' ', $catTitle);
			$sqlLikes = array();
			$keywordsCnt = count($keywords);
			foreach ($keywords as $keyword) {
				$keyword = trim($keyword);
				if (mb_strlen($keyword, 'UTF-8') > 1) {
					if ($keywordsCnt > 1) {
						$sqlLikes[] = 'UCASE(CATEGORIES_TITLE) like "%' . $keyword . '%"';
					} else {
						$sqlLikes[] = 'UCASE(CATEGORIES_TITLE) like "' . $keyword . '"';
					}
				}
			}
			$sql .= implode(' and ', $sqlLikes);
			$this->alert($sql);
			$db->query($sql);
		}
		$db->commit();
		$this->alert('Categories porting finished');
	}
	
	public function portBrands()
	{
		$this->alert('Brands porting started');

		$db = $this->_db;
		$catalogBrands = $db->fetchPairs(
			'select BRANDS_ID, BRANDS_TITLE from CATALOG_BRANDS'
		);

		$db->beginTransaction();
		foreach ($catalogBrands as $brandId => $brandTitle) {
			$sql = 'update MIXMARKET_BRANDS set BRANDS_CATALOGID = ' . $brandId
				. ' where ';
			$keywords = explode(' ', $brandTitle);
			$keywordsCnt = count($keywords);
			$sqlLikes = array();
			foreach ($keywords as $keyword) {
				$keyword = trim($keyword);
				if (mb_strlen($keyword, 'UTF-8') > 1) {
					if ($keywordsCnt > 1) {
						$sqlLikes[] = 'UCASE(BRANDS_TITLE) like "%' . $keyword . '%"';
					} else {
						$sqlLikes[] = 'UCASE(BRANDS_TITLE) like "' . $keyword . '"';
					}
				}
			}
			$sql .= implode(' and ', $sqlLikes);
			$this->alert($sql);
			$db->query($sql);
		}
		$db->commit();
		$this->alert('Brands porting finished');
	}
	
	public function portOffers()
	{
		$this->alert('Offers porting started');

		$db = $this->_db;

		$sql = 'select prods.PRODUCTS_ID as ID, prods.PRODUCTS_TITLE as TITLE, brands.BRANDS_ID as BRANDID, mixCats.CATEGORIES_ID as CATEGORYID '
			. 'from CATALOG_PRODUCTS as prods inner join CATALOG_PRODUCTCATEGORIES as cats '
				. 'on prods.PRODUCTS_ID = cats.PRODUCTCATEGORIES_PRODUCTID '
			. 'inner join MIXMARKET_BRANDS as brands '
				. 'on prods.PRODUCTS_BRANDID = brands.BRANDS_CATALOGID '
			. 'inner join MIXMARKET_CATEGORIES as mixCats '
				. 'on cats.PRODUCTCATEGORIES_ID = mixCats.CATEGORIES_CATALOGID '
			. 'where cats.PRODUCTCATEGORIES_ISDEFAULT = 1 limit ';
		$offset = 0;
		$prods = array();
var_dump($sql);
return;
		while ($prods = $db->fetchAll($sql . $offset . ',' . self::QUERY_LIMIT)) {
			foreach ($prods as $product) {
				$updateSql = 'update MIXMARKET_OFFERS set OFFERS_CATALOGID = ' . $product['ID']
					. ' where OFFERS_CAT OFFERS_BRANDID';
				$keywords = explode(' ', $product['TITLE']);
				$sqlLikes = array();
				foreach ($keywords as $keyword) {
					$keyword = trim($keyword);
					if (mb_strlen($keyword, 'UTF-8') > 1) {
						$sqlLikes[] = 'UCASE(OFFERS_TITLE) like "%' . $keyword . '%"';
					}
				}
				$sql .= implode(' and ', $sqlLikes);
				$this->alert($sql);
				//$db->query($sql);
			}
			$offset += self::QUERY_LIMIT + 1;
		}

		$this->alert('Offers porting finished');
	}
}
