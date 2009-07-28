<?php
/**
 * User Site Header Controller
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Header.php 106 2009-07-17 17:48:15Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Brands extends PTA_WebModule
{
	private $_brand;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Brands.tpl');
		$this->setModuleUrl(PTA_BASE_URL . '/Brands/View/Brand');
	}

	public function init()
	{
		if ($this->isActive()) {
			$this->viewAction();
		}
	}

	public function viewAction()
	{
		$brand = $this->getBrand();
		if (empty($brand)) {
			$this->redirect('/');
		}

		$category = $this->getCategory();
		$categoryId = $this->getCategoryId();

		$prodsTable = PTA_DB_Table::get('Catalog_Product');
		$categoryTable = PTA_DB_Table::get('Catalog_Category');
		$prodCategoryTable = PTA_DB_Table::get('Catalog_Product_Category');
		$brandTable = PTA_DB_Table::get('Catalog_Brand');

		if (!empty($category)) {
			$this->getApp()->addKeyword(
				$category[$categoryTable->getFieldByAlias('title')]
			);
		}

		$brandTitle = $brand[$brandTable->getFieldByAlias('title')];
		$this->getApp()->addKeyword($brandTitle);
		$this->getApp()->setTitle($brandTitle);

		$select = $prodsTable->getCatalogQuery($categoryId);

		$brandIdField = $brandTable->getPrimary();
		$select->where('brands.' . $brandIdField . ' = ' . intval($brand[$brandIdField]));
		if (empty($categoryId)) {
			$select->where('prodCats.' . $prodCategoryTable->getFieldByAlias('isDefault') . ' = 1');
		}

		$view = new PTA_Control_View('catalogView');
		$view->setTable($prodCategoryTable);
		$view->setSelect($select);

		if (!empty($categoryId)) {
			$view->setTotalRecordsCnt(
				$view->getTotalRecordsCnt(
					$prodCategoryTable->getAdapter()->quoteInto(
						$prodCategoryTable->getFieldByAlias('categoryId') . ' in (?)',
						$categoryId
					)
				)
			);
		}

		$view->setMinRpp(10);
		$view->setMaxRpp(50);

		$this->setVar('view', $view->exec());
		$this->setVar('brand', $brand);
		$this->setVar('prodUrl', '/Products/View/Product');
	}

	public function getHttpBrand()
	{
		return $this->getApp()->getHttpVar('Brand');
	}
	
	public function getBrand()
	{
		if (is_null($this->_brand)) {
			$this->_brand = current(
				PTA_DB_Table::get('Catalog_Brand')->getByAlias($this->getHttpBrand())
			);
		}
		
		return $this->_brand;
	}

	public function getCategoryId()
	{
		return $this->getApp()->getModule('Categories')->getCategoryId();
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
}
