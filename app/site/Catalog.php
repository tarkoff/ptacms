<?php
/**
 * Controller For Catalog List Of Products
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Catalog extends PTA_WebModule
{
	private $_catalog;
	
	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Catalog.tpl');
		$this->setModuleUrl(PTA_BASE_URL . '/Products/View/Product');
	}

	public function init()
	{
		parent::init();

		$categoryAlias = $this->getApp()->getHttpVar('Category', false);
		$themeAlias = $this->getApp()->getHttpVar('Theme', false);
//		$productAlias = $this->getApp()->getHttpVar('Product', false);

		$prodsTable = PTA_DB_Table::get('PTA_Catalog_Product');

		$select = $prodsTable->select()->from(array('prods' => $prodsTable->getTableName()));
		$select->setIntegrityCheck(false);
/*
		if (!empty($productAlias)) {
			$select->where('prods.' . $prodsTable->getFieldByAlias('alias') . ' = ?', $productAlias);
		}
*/
		$catsTable = PTA_DB_Table::get('Catalog_Category');
		if (!empty($categoryAlias) || !empty($themeAlias)) {
			$catsTableName = $catsTable->getTableName();
			$catsPrimaryField = $catsTable->getPrimary();
			$catsParentIdField = $catsTable->getFieldByAlias('parentId');
			$catsAliasField = $catsTable->getFieldByAlias('alias');

			$select->join(
				array('cats' => $catsTableName),
				'prods.'. $prodsTable->getFieldByAlias('categoryId') . " = cats.{$catsPrimaryField}",
				array()
			);
			if (!empty($themeAlias)) {
				$select->where("cats.{$catsAliasField} = ?", $themeAlias);
			}
			if (!empty($categoryAlias)) {
				$select->join(
					array('cats2' => $catsTableName),
					"cats. {$catsParentIdField} = cats2.{$catsPrimaryField}",
					array()
				);
				$select->where("cats2.{$catsAliasField} = ?", $categoryAlias);
			}
		
			$category = current(
				$catsTable->findByFields(
					array('alias'),
					array(empty($themeAlias) ? $categoryAlias : $themeAlias)
				)
			);
		} else {
			$category[$catsTable->getFieldByAlias('alias')] = '';
		}

		$select->order('prods.' . $prodsTable->getFieldByAlias('date') . ' desc');
		$select->limit(20);

		$products = $prodsTable->fetchAll($select)->toArray();

		
		$this->setVar('products', $products);
		$this->setVar('category', $category);
	}

	public function mainPage()
	{
		//$this->getApp()->insertModule('NewList', 'NewList');
		$this->getApp()->insertModule('TopList', 'TopList');
		$this->getApp()->getModule('TopList')->init();
		
	}
}
