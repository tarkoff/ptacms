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

		$insertValues = array();
		$bulkPos = 1;
		foreach ($catalogCategories as $catId => $catTitle) {
			$sql = 'select CATEGORIES_ID from MIXMARKET_CATEGORIES where ';
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
			$sql .= implode(' and ', $sqlLikes) . ' limit 1';

			$mixId = $db->fetchOne($sql);
			if (!empty($mixId)) {
				$insertValues[] = "({$catId}, {$mixId})";
			}

			if ($bulkPos > self::QUERY_LIMIT) {
				$db->query(
					'insert ignore into MIXMARKET_LINKCATEGORIES '
					. '(LINKCATEGORIES_CATALOGID, LINKCATEGORIES_MIXID) values '
					. implode(', ', $insertValues)
				);
				$insertValues = array();
				$bulkPos = 1;
			}
		}

		if (!empty($insertValues)) {
			$db->query(
				'insert ignore into MIXMARKET_LINKCATEGORIES '
				. '(LINKCATEGORIES_CATALOGID, LINKCATEGORIES_MIXID) values '
				. implode(', ', $insertValues)
			);
		}
		$this->alert('Categories porting finished');
	}
	
	public function portBrands()
	{
		$this->alert('Brands porting started');

		$db = $this->_db;
		$catalogBrands = $db->fetchPairs(
			'select BRANDS_ID, BRANDS_TITLE from CATALOG_BRANDS'
		);

		$insertValues = array();
		$bulkPos = 1;
		foreach ($catalogBrands as $brandId => $brandTitle) {
			$sql = 'select BRANDS_ID from MIXMARKET_BRANDS where ';
			$keywords = explode(' ', $brandTitle);
			$sqlLikes = array();
			$keywordsCnt = count($keywords);
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
			$sql .= implode(' and ', $sqlLikes) . ' limit 1';

			$mixId = $db->fetchOne($sql);
			if (!empty($mixId)) {
				$insertValues[] = "({$brandId}, {$mixId})";
				$bulkPos++;
			}

			if ($bulkPos > self::QUERY_LIMIT) {
				$db->query(
					'insert ignore into MIXMARKET_LINKBRANDS '
					. '(LINKBRANDS_CATALOGID, LINKBRANDS_MIXID) values '
					. implode(', ', $insertValues)
				);
				$insertValues = array();
				$bulkPos = 1;
			}
		}

		if (!empty($insertValues)) {
			$db->query(
					'insert ignore into MIXMARKET_LINKBRANDS '
					. '(LINKBRANDS_CATALOGID, LINKBRANDS_MIXID) values '
					. implode(', ', $insertValues)
			);
		}

		$this->alert('Brands porting finished');
	}
	
	public function portOffers()
	{
		$this->alert('Offers porting started');

		$db = $this->_db;

		$sql = 'select prods.PRODUCTS_ID, prods.PRODUCTS_BRANDID, cats.PRODUCTCATEGORIES_CATEGORYID, prods.PRODUCTS_TITLE, prodSet.PRODUCTSETTINGS_SETTINGS '
			. 'from CATALOG_PRODUCTS as prods '
			. 'inner join CATALOG_PRODUCTCATEGORIES as cats on prods.PRODUCTS_ID = cats.PRODUCTCATEGORIES_PRODUCTID '
			. 'left join CATALOG_PRODUCTSETTINGS as prodSet on prods.PRODUCTS_ID = prodSet.PRODUCTSETTINGS_PRODUCTID '
			. ' where cats.PRODUCTCATEGORIES_ISDEFAULT = 1 limit ';

		$offset = 0;
		$prods = array();
		$bulkPos = 1;
		$insertValues = array();
		while ($prods = $db->fetchAll($sql . $offset . ',' . self::QUERY_LIMIT)) {
			foreach ($prods as $product) {
				$mixProdSql = 'select mo.OFFERS_ID from MIXMARKET_OFFERS as mo '
					. ' inner join MIXMARKET_LINKCATEGORIES as mlc on mo.OFFERS_CATID = mlc.LINKCATEGORIES_MIXID '
					. ' inner join MIXMARKET_LINKBRANDS as mlb on mo.OFFERS_BRANDID = mlb.LINKBRANDS_MIXID '
						. ' where mlb.LINKBRANDS_CATALOGID = ' . $product['PRODUCTS_BRANDID']
						. ' and mlc.LINKCATEGORIES_CATALOGID = ' . $product['PRODUCTCATEGORIES_CATEGORYID'] . ' and ';
				$settings = $keywords = $stopKeywords = array();
				if (!empty($product['PRODUCTSETTINGS_SETTINGS'])) {
					$settings = (array)unserialize($product['PRODUCTSETTINGS_SETTINGS']);
				}

				if (!empty($settings['tags'])) {
					$keywords = explode(',', $settings['tags']);
					if (!empty($settings['stopTags'])) {
						$stopKeywords = explode(',', $settings['stopTags']);
					}
				} else {
					$keywords = explode(' ', $product['PRODUCTS_TITLE']);
				}
				$sqlLikes = array();
				foreach ($keywords as $keyword) {
					$keyword = trim($keyword);
					if (mb_strlen($keyword, 'UTF-8') > 1) {
						$sqlLikes[] = 'UCASE(mo.OFFERS_NAME) like "%' . $keyword . '%"';
					}
				}
				foreach ($stopKeywords as $keyword) {
					$keyword = trim($keyword);
					if (mb_strlen($keyword, 'UTF-8') > 1) {
						$sqlLikes[] = 'UCASE(mo.OFFERS_NAME) not like "%' . $keyword . '%"';
					}
				}
				$mixProdSql .= implode(' and ', $sqlLikes);
				$this->alert($mixProdSql);
				foreach ($db->fetchAll($mixProdSql) as $mixId) {
					$insertValues[] = "({$product['PRODUCTS_ID']}, {$mixId['OFFERS_ID']})";
					$bulkPos++;
				}

				if ($bulkPos > self::QUERY_LIMIT) {
					$db->query(
						'insert ignore into MIXMARKET_LINKOFFERS '
						. '(LINKOFFERS_CATALOGID, LINKOFFERS_MIXID) values '
						. implode(', ', $insertValues)
					);
					$insertValues = array();
					$bulkPos = 1;
				}
			}
			$offset += self::QUERY_LIMIT + 1;
		}

		$this->alert('Offers porting finished');
	}
}
