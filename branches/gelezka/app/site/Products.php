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

		//$productId = (int)$this->getHttpProduct();
		$productAlias = $this->getHttpProduct();

		$productTable = PTA_DB_Table::get('Catalog_Product');
		$productCategoryTable = PTA_DB_Table::get('Catalog_Product_Category');
		$catTable = PTA_DB_Table::get('Catalog_Category');
		$brantTable = PTA_DB_Table::get('Catalog_Brand');
		$fieldGroupTable = PTA_DB_Table::get('Catalog_Field_Group');
		$fieldGroupFieldsTable = PTA_DB_Table::get('Catalog_Field_Group_Field');
		$valueTable = PTA_DB_Table::get('Catalog_Value');
		$fieldValueTable = PTA_DB_Table::get('Catalog_Field_Value');

		//$product = current($productTable->findById($productId));
		$product = current($productTable->getByAlias($productAlias));
		
		if (empty($product)) {
			$this->redirect('/');
		}
		
		$app = $this->getApp();
		$productId = (int)$product[$productTable->getPrimary()];

		$this->updateProductStat($productId);
		$this->addPriceForm($productId);

		$this->addVisual(new Products_CommentsForm('commentForm', $productId));

		$categories = $productCategoryTable->getProductCategories($productId, true);

		$catIdField = $catTable->getPrimary();
		$catTitle = $catTable->getFieldByAlias('title');
		$isDefaultField = $productCategoryTable->getFieldByAlias('isDefault');

		$category = $categoryId = null;
		foreach ($categories as $cat) {
			$app->addKeyword($cat[$catTitle]);
			if (!empty($cat[$isDefaultField])) {
				$category = $cat;
				$categoryId = $cat[$catIdField];
			}
		}

/*
		$parentCategories = $this->getApp()->getModule('Categories')->getParentCategories(
			$categoryId
		);
*/

		$brandTitleField = $brantTable->getFieldByAlias('title');
		$brand = current(
			$brantTable->findById(
				$product[$productTable->getFieldByAlias('brandId')]
			)
		);

		$productTitleField = $productTable->getFieldByAlias('title');
		$app->addKeyword($brand[$brandTitleField]);
		$app->addKeyword($product[$productTitleField]);
		if ($this->isActive()) {
			$app->setTitle($brand[$brandTitleField] . ' ' . $product[$productTitleField]);
		}
		unset($productTitleField);

		$groupIdField = $fieldGroupTable->getPrimary();
		$groupTitleField = $fieldGroupTable->getFieldByAlias('title');


		$fieldGroups = $fieldGroupsIds = array();
		foreach ($fieldGroupTable->getCategoryGroups($categoryId) as $group) {
			$groupId = $group[$groupIdField];
			$group['fields'] = array();
			$fieldGroups[$groupId] = $group;
		}

		$fieldIdField = $fieldGroupFieldsTable->getFieldByAlias('fieldId');
		$fieldGroupIdField = $fieldGroupFieldsTable->getFieldByAlias('groupId');

		foreach ($fieldGroupFieldsTable->getGroupFields(array_keys($fieldGroups)) as $groupField) {
			$groupId = $groupField[$fieldGroupIdField];
			$fieldId = $groupField[$fieldIdField];
			$fieldGroupsIds[$fieldId] = $groupId;
		}

		$fieldGroups['else'][0][$groupTitleField] = '';

		$fieldIdField = $valueTable->getFieldByAlias('fieldId');
		$valueIdField = $valueTable->getFieldByAlias('valueId');
		$valueField = $fieldValueTable->getFieldByAlias('value');

		foreach ($valueTable->getValuesByProductId($productId) as $field) {
			$fieldId = (int)$field[$fieldIdField];
			if (
				isset($fieldGroupsIds[$fieldId])
				&& isset($fieldGroups[$fieldGroupsIds[$fieldId]])
			) {
				$value = &$fieldGroups[$fieldGroupsIds[$fieldId]]['fields'][$fieldId];
			} else {
				$value = $fieldGroups['else']['fields'][$fieldId];
			}
			if (empty($value)) { $value = $field; }
			if (!is_array($value[$valueField])) {
				$value[$valueField] = array($field[$valueIdField] => $value[$valueField]);
			}
			$value[$valueField][$field[$valueIdField]] = $field[$valueField];
		}

		$this->setVar('product', $product);
		$this->setVar('category', $category);
//		$this->setVar('parentCategories', $parentCategories);
		$this->setVar('photos', PTA_DB_Table::get('Catalog_Product_Photo')->getPhotos($productId));
		$this->setVar('customFields', $fieldGroups);
		$this->setVar('categories', $categories);
		$this->setVar('brand', $brand);
		$this->setVar('comments', PTA_DB_Table::get('Post')->getProductPosts($productId));
		$this->setVar(
			'secondHandPrices',
			PTA_DB_Table::get('Catalog_Price')->getSecondHandPrices($productId)
		);
		$this->setVar('mixPrices', PTA_DB_Table::get('MixMarket_Offer')->getOffers($productId));
		$this->getApp()->setVar(
			'mixCategory',
			PTA_DB_Table::get('MixMarket_Category')->getMixCategoryId($categoryId)
		);
	}
	
	public function getHttpProduct()
	{
		return $this->getApp()->getHttpVar('Product');
	}
	
	public function updateProductStat($productId)
	{
		if (empty($productId)) {
			return false;
		}

		$productStat = PTA_DB_Object::get('Catalog_Product_Stat', $productId);
		$productStat->setProductId($productId);
		
		$viewsCnt = $productStat->getViews();
		$productStat->setViews(++$viewsCnt);

		return $productStat->save();
	}
	
	public function addPriceForm($productId)
	{
		if (empty($productId)) {
			return false;
		}

		$price = PTA_DB_Object::get('Catalog_Price');

		if (!$user = $this->getApp()->getUser()) {
			$user = PTA_DB_Object::get('User_Guest');
		}

		$price->setUserId($user->getId());
		$price->setProductId($productId);

//		var_dump($this->_price);

		$this->addVisual(new Prices_EditForm('PriceForm', $price));
	}
}
