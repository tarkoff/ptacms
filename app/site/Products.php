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

		$productId = (int)$this->getHttpProduct();

		$productTable = PTA_DB_Table::get('Catalog_Product');
		$product = current($productTable->findById($productId));

		if (empty($product)) {
			$this->redirect($this->getApp()->getBaseUrl());
		}

		$productCategoryTable = PTA_DB_Table::get('Catalog_Product_Category');

		$categories = $productCategoryTable->getCategoriesByProductId(
			$product[$productTable->getPrimary()], true
		);

		$brantTable = PTA_DB_Table::get('Catalog_Brand');
		$brandTitleField = $brantTable->getFieldByAlias('title');
		
		$brand = current(
			$brantTable->findById(
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
		$this->setVar('categories', $categories);
		$this->setVar('brand', $brand);
	}
	
	public function getHttpProduct()
	{
		return $this->getApp()->getHttpVar('Product');
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
