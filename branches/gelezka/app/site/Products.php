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
		$productCategoryTable = PTA_DB_Table::get('Catalog_Product_Category');
		$catTable = PTA_DB_Table::get('Catalog_Category');
		$brantTable = PTA_DB_Table::get('Catalog_Brand');

		$product = current($productTable->findById($productId));

		if (empty($product)) {
			$this->redirect($this->getApp()->getBaseUrl());
		}
		
		$app = $this->getApp();

		$categoryId = $product[$productTable->getFieldByAlias('categoryId')];
		$category = current($catTable->findById($categoryId));
/*
		$parentCategories = $this->getApp()->getModule('Categories')->getParentCategories(
			$categoryId
		);
*/
		$categories = $productCategoryTable->getCategoriesByProductId(
			$product[$productTable->getPrimary()], true
		);
		$catTitle = $catTable->getFieldByAlias('title');
		
		$app->addKeyword($category[$catTitle]);
		foreach ($categories as $cat) {
			$app->addKeyword($cat[$catTitle]);
		}

		$brandTitleField = $brantTable->getFieldByAlias('title');
		$brand = current(
			$brantTable->findById(
				$product[$productTable->getFieldByAlias('brandId')]
			)
		);
		$app->addKeyword($brand[$brandTitleField]);
		$app->addKeyword($product[$productTable->getFieldByAlias('title')]);
		
		$this->updateProductStat($productId);
		
		$fieldGroupTable = PTA_DB_Table::get('Catalog_Field_Group');
		$valueTable = PTA_DB_Table::get('Catalog_Value');

		$groupIdField = $fieldGroupTable->getPrimary();
		$groupTitleField = $fieldGroupTable->getFieldByAlias('title');
		
		$res = $fieldGroupTable->getCategoryGroups($categoryId);
		$fieldGroups = array();
		foreach ($res as $group) {
			$groupId = $group[$groupIdField];
			$group['fields'] = array();
			$fieldGroups[$groupId] = $group;
		}
		unset($res);
		
		$fieldGroups['else'][0][$groupTitleField] = '';

		$customFields = $valueTable->getValuesByProductId($productId, false);
		$groupIdField = PTA_DB_Table::get('Catalog_Field_Group_Field')->getFieldByAlias('groupId');
		foreach ($customFields as $field) {
			$groupId = $field[$groupIdField];
			if (isset($fieldGroups[$groupId])) {
				$fieldGroups[$groupId]['fields'][] = $field; 
			} else {
				$fieldGroups['else']['fields'][] = $field;
			}
		}
//var_dump($fieldGroups);
		$this->setVar('product', $product);
		$this->setVar('category', $category);
//		$this->setVar('parentCategories', $parentCategories);
		$this->setVar(
			'photos',
			PTA_DB_Table::get('Catalog_Product_Photo')->getPhotos($productId)
		);
		$this->setVar('customFields', $fieldGroups);
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
