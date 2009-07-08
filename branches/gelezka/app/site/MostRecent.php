<?php
/**
 * Most REcent List Of Products
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: TopList.php 62 2009-05-31 16:59:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class MostRecent extends PTA_WebModule
{
	function __construct ($prefix)
	{
		parent::__construct($prefix, 'MostRecent.tpl');
		$this->setModuleUrl(PTA_BASE_URL . '/Products/View/Product');
	}

	public function init()
	{
		parent::init();

		$categoryAlias = $this->getApp()->getHttpVar('Category', false);

		$catsTable = PTA_DB_Table::get('Catalog_Category');
		$prodsCatsTable = PTA_DB_Table::get('Catalog_Product_Category');
		$brandsTable = PTA_DB_Table::get('Catalog_Brand');
		//$photoTable = PTA_DB_Table::get('Catalog_Product_Photo');

		$prodsTable = PTA_DB_Table::get('PTA_Catalog_Product');

		$select = $prodsTable->select()->from(array('prods' => $prodsTable->getTableName()));
		$select->setIntegrityCheck(false);
/*
		$select->joinLeft(
			array('photos' => $photoTable->getTableName()),
			'prods.' . $prodsTable->getPrimary() . ' = ' . $photoTable->getFieldByAlias('productId')
			. ' AND photos.' . $photoTable->getFieldByAlias('default') . ' = 1',
			array($photoTable->getFieldByAlias('photo'))
		);
*/
		$catsTableName = $catsTable->getTableName();
		$catsPrimaryField = $catsTable->getPrimary();
		$catsParentIdField = $catsTable->getFieldByAlias('parentId');
		$catsAliasField = $catsTable->getFieldByAlias('alias');

		$select->joinLeft(
			array('prodCats' => $prodsCatsTable->getTableName()),
			'prods.'. $prodsTable->getPrimary()
			. ' = prodCats.' . $prodsCatsTable->getFieldByAlias('productId'),
			array()
		);

		$select->join(
			array('cats' => $catsTableName),
			'(prodCats.'. $prodsCatsTable->getFieldByAlias('categoryId') . " = cats.{$catsPrimaryField}"
			. ' or prods.' . $prodsTable->getFieldByAlias('categoryId') .' = cats.' . $catsPrimaryField . ')',
			array(
				$catsTable->getFieldByAlias('alias'),
				$catsTable->getFieldByAlias('title')
			)
		);

		$select->join(
			array('brands' => $brandsTable->getTableName()),
			'prods.'. $prodsTable->getFieldByAlias('brandId') 
			. ' = brands.' . $brandsTable->getPrimary(),
			array(
				$brandsTable->getFieldByAlias('alias'),
				$brandsTable->getFieldByAlias('title')
			)
		);

		$category = array();
		if (!empty($categoryAlias)) {
			$select->join(
				array('cats2' => $catsTableName),
				"cats. {$catsParentIdField} = cats2.{$catsPrimaryField}",
				array()
			);
			$select->where("cats2.{$catsAliasField} = ?", $categoryAlias);
		} else {
			$category[$catsTable->getFieldByAlias('alias')] = '';
		}

		$select->group('cats.' . $catsTable->getPrimary());
		$select->order('prods.' . $prodsTable->getFieldByAlias('date') . ' desc');
		$select->limit(10);
		$products = $prodsTable->fetchAll($select)->toArray();

		$this->setVar('products', $products);
		$this->setVar('brandUrl', PTA_BASE_URL . '/Brands/View/Brand');
	}
}
