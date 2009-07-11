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

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Catalog.tpl');
		$this->setModuleUrl(PTA_BASE_URL . '/Products/View/Product');
	}

	public function init()
	{
		parent::init();

		if ($this->isActive() && ($category = $this->getCategory())) {
			$catTable = PTA_DB_Table::get('PTA_Catalog_Category');
			$catTileField = $catTable->getFieldByAlias('title');
			$this->getApp()->setTitle($category[$catTileField]);
			unset($catTable, $catTileField, $category);
		}

		$this->setVar('view', $this->getCatalogPage($this->getCategoryId(), 1));
		$this->setVar('brandUrl', PTA_BASE_URL . '/Brands/View/Brand');
	}

	/**
	 * Get Current Category
	 *
	 * @return array
	 */
	public function getCategory()
	{
		return $this->getApp()->getModule('Categories')->getCategory();
	}
	
	public function getCategoryId()
	{
		return $this->getApp()->getModule('Categories')->getCategoryId();
	}
	
	public function getCatalogPage($categoryId = null, $page = 1)
	{
		$categoryId = (array)$categoryId;
		$prodsTable = PTA_DB_Table::get('PTA_Catalog_Product');
		
		$subCategories = $this->getApp()->getModule('Categories')->getSubCategories($categoryId);
		if (!empty($subCategories)) {
			$categoryIdField = PTA_DB_Table::get('PTA_Catalog_Category')->getPrimary();
			foreach ($subCategories as $cat) {
				$categoryId[] = $cat[$categoryIdField];
			}
		}

		$select = $this->getCatalogQuery($categoryId);
		
		$prodCatsTable = PTA_DB_Table::get('PTA_Catalog_Product_Category');
		$view = new PTA_Control_View('catalogView');
		$view->setTable($prodCatsTable);
		$view->setSelect($select);
		$view->setTotalRecordsCnt(
			$view->getTotalRecordsCnt(
				$prodCatsTable->getAdapter()->quoteInto(
					$prodCatsTable->getFieldByAlias('categoryId') . ' in (?)',
					$categoryId
				)
			)
		);
		$view->setMinRpp(10);
		$view->setMaxRpp(50);
		return $view->exec();
		/*
		$select->order('prods.' . $prodsTable->getFieldByAlias('date') . ' desc');
		$select->limitPage(intval($page), 10);

		return $prodsTable->fetchAll($select)->toArray();
		*/
	}
	
	/**
	 * Get Most Recent Products List
	 *
	 * @param int $categoryId
	 */
	public function getMostRecent($categoryId = null)
	{
		$prodsTable = PTA_DB_Table::get('PTA_Catalog_Product');
		$select = $this->getCatalogQuery($categoryId);
		$select->order('prods.' . $prodsTable->getFieldByAlias('date') . ' desc');
		$select->limit(10);

		$this->setVar('brandUrl', PTA_BASE_URL . '/Brands/View/Brand');
		return $prodsTable->fetchAll($select)->toArray();
		
	}
	
	/**
	 * Return Zend_DB_Table_Select for catalog items
	 *
	 * @param int $categoryId
	 * @return Zend_Db_Table_Select
	 */
	public function getCatalogQuery($categoryId = null)
	{
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

		$select->join(
			array('prodCats' => $prodsCatsTable->getTableName()),
			'prods.'. $prodsTable->getPrimary()
			. ' = prodCats.' . $prodsCatsTable->getFieldByAlias('productId'),
			array()
		);

		$select->join(
			array('cats' => $catsTableName),
			'prodCats.'. $prodsCatsTable->getFieldByAlias('categoryId') . " = cats.{$catsPrimaryField}",
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

		if (!empty($categoryId)) {
			$select->where('cats.' . $catsTable->getPrimary() . ' in (?)', $categoryId);
		}

		return $select;
	}
	
	public function getSponsoredLinks()
	{
		
	}

}
