<?php
/**
 * Catalog Product Description Controller
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Products extends PTA_WebModule
{
	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Products.tpl');
		//$this->setModuleUrl(PTA_BASE_URL . '/Product/list/Book');
	}

	public function init()
	{
		parent::init();

		$productId = (int)$this->getApp()->getHttpVar('Product');

		$productTable = PTA_DB_Table::get('Catalog_Product');
		$product = current($productTable->findById($productId));

		if (empty($product)) {
			$this->redirect($this->getApp()->getBaseUrl());
		}

		$categoryTable = PTA_DB_Table::get('Catalog_Category');

		$category = current(
			$categoryTable->findById(
				$product[$productTable->getFieldByAlias('categoryId')]
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
				$product[$productTable->getFieldByAlias('brandId')]
			)
		);
		
		$this->updateProductStat($productId);
		
		$this->setVar('product', $product);
		$this->setVar(
			'photos',
			PTA_DB_Table::get('Catalog_Product_Photo')->getPhotos($productId)
		);
		$this->setVar(
			'customProductField',
			PTA_DB_Table::get('Catalog_Value')->getValuesByProductId($productId, false)
		);
		$this->setVar('category', $category);
		$this->setVar('brand', $brand);
	}
	
	public function updateProductStat($productId)
	{
		$productStat = PTA_DB_Object::get('Catalog_Product_Stat', $productId);
		$productStat->setProductId($productId);
		
		$viewsCnt = $productStat->getViews();
		$productStat->setViews(++$viewsCnt);

		return $productStat->save();
	}
}
