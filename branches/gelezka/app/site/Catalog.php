<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Catalog.php 25 2009-03-16 21:32:59Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Catalog extends PTA_WebModule
{
	private $_catalog;
	
	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Catalog.tpl');
		$this->setModuleUrl(BASEURL . '/Books/view/Book');
	}

	public function init()
	{
		parent::init();

		$categoryAlias = $this->getApp()->getHttpVar('Category');
		$themeAlias = $this->getApp()->getHttpVar('Theme');
		$bookAlias = $this->getApp()->getHttpVar('Book');

		$prodsTable = PTA_DB_Table::get('PTA_Catalog_Product');
		$select = $prodsTable->select()->from(array('prods' => $prodsTable->getTableName()));

		if (!empty($bookAlias)) {
			$select->where('prods.' . $prodsTable->getFieldByAlias('alias') . ' = ?', $bookAlias);
		}
		
		if (!empty($categoryAlias) || !empty($themeAlias)) {

			$catsTable = PTA_DB_Table::get('Catalog_Category');
			$catsTableName = $catsTable->getTableName();
			$catsPrimaryField = $catsTable->getPrimary();
			$catsParentIdField = $catsTable->getFieldByAlias('parentId');
			$catsAliasField = $catsTable->getFieldByAlias('alias');

			if (!empty($themeAlias) || !empty($categoryAlias)) {
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
			} 
		}

		$select->order('prods.' . $prodsTable->getFieldByAlias('date') . ' desc');
		$select->limit(20);
//var_dump($select->assemble());
		$this->setVar('books', $prodsTable->fetchAll($select)->toArray());
	}

	public function mainPage()
	{
		//$this->getApp()->insertModule('NewList', 'NewList');
		$this->getApp()->insertModule('TopList', 'TopList');
		$this->getApp()->getModule('TopList')->init();
		
	}
}
