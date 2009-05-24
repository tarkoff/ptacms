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

class Products extends PTA_WebModule
{
	private $_catalog;
	
	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Products.tpl');
		//$this->setModuleUrl(BASEURL . '/Product/list/Book');
	}

	public function init()
	{
		parent::init();

		$bookId = $this->getApp()->getHttpVar('Product');

		$productTable = PTA_DB_Table::get('Catalog_Product');
		$book = current($productTable->findById($bookId));

		if (empty($book)) {
			$this->redirect($this->getApp()->getBaseUrl());
		}

		$categoryTable = PTA_DB_Table::get('Catalog_Category');

		$category = current(
			$categoryTable->findById(
				$book[$productTable->getFieldByAlias('categoryId')]
			)
		);

		$aliasField = $categoryTable->getFieldByAlias('alias');

		$parentCategory = PTA_DB_Table::get('Catalog_Category')->getRootCategory(
			$category[$categoryTable->getFieldByAlias('parentId')]
		);

		$this->getModule('TopMenu')->setCategory($parentCategory[$aliasField]);
		$this->getModule('LeftMenu')->setTheme($category[$aliasField]);
		
		$brand = current(
			PTA_DB_Table::get('Catalog_Brand')->findById(
				$book[$productTable->getFieldByAlias('brandId')]
			)
		);
		
		$this->setVar('product', $book);
		$this->setVar('customProductField', PTA_DB_Table::get('Catalog_Value')->getValuesByProductId($bookId));
		$this->setVar('category', $category);
		$this->setVar('brand', $brand);
	}
}
