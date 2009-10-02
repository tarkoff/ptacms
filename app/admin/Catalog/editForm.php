<?php
/**
 * Catalog Product Edit Form
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/
class Catalog_editForm extends PTA_Control_Form 
{
	private $_product;
	private $_category;
	private $_copy;

	public function __construct($prefix, $product, $copy = false)
	{
		$this->_product = $product;
		$this->_copy = $copy;

		parent::__construct($prefix);

		$this->setTitle('Add/Edit "' . $product->getTitle() .'" Product Form');
	}

	public function initForm()
	{
		$this->_initStaticFields();
		$this->_initDinamicFields();

		$submit = new PTA_Control_Form_Submit('submit', 'Remove Field', true, 'Save Product');
		$submit->setSortOrder(5000);
		$this->addVisual($submit);
	}

	private function _initStaticFields()
	{
		$title = new PTA_Control_Form_Text('title', 'Title');
		$title->setSortOrder(10);
		$this->addVisual($title);

		$alias = new PTA_Control_Form_Text('alias', 'Alias');
		$alias->setSortOrder(15);
		$this->addVisual($alias);

		$brand = new PTA_Control_Form_Select('brandId', 'Brand', false);
		$brand->setSortOrder(16);
		$brand->setOptions(PTA_DB_Table::get('Catalog_Brand')->getSelectedFields(array('id', 'title')));
		//$brand->addOption(array(0, '- Empty -'));
		$brand->setSelected((int)$this->_product->getBrandId());
		$this->addVisual($brand);

		$catsTable = PTA_DB_Table::get('Catalog_Category');
		$prodCatTable = PTA_DB_Table::get('Catalog_Product_Category');

		$prodCatIdField = $catsTable->getPrimary();
		$prodDefaultCatId = $prodCatTable->getFieldByAlias('isDefault');

		$catsList = $catsTable->getSelectedFields(
			array('id', 'title'),
			$catsTable->getFieldByAlias('parentId') . ' <> 0'
		); 

		$prodCatsList = $prodCatTable->getProductCategories($this->_product->getId());
		$defaultCategoryId = 0;
		$prodCatsIds = array();
		foreach ($prodCatsList as $prodCat) {
			if (!empty($prodCat[$prodDefaultCatId])) {
				$defaultCategoryId = intval($prodCat[$prodCatIdField]);
			} else {
				$prodCatsIds[$prodCat[$prodCatIdField]] = intval($prodCat[$prodCatIdField]);
			}
		}

		if ($this->_product->getCategoryId()) {
			$defaultCategoryId = $this->_product->getCategoryId();
		}

		$category = new PTA_Control_Form_Select(
			'categoryId', 'Category', false, $catsList, $defaultCategoryId
		);
		$category->setSortOrder(16);
		$category->addOption(array('0', 'Empty'));
		$this->addVisual($category);

		foreach ($catsList as $catId => $category) {
			if ($defaultCategoryId == $category[0]) {
				unset($catsList[$catId]);
			}
		}

		$category = new PTA_Control_Form_Select(
			'showInCategories', 'Show in categories', false, $catsList, $prodCatsIds
		);
		$category->setSortOrder(17);
		$category->setMultiple(true);
		$category->addOption(array('0', 'Empty'));
		$this->addVisual($category);

		$url = new PTA_Control_Form_Text('url', 'URL');
		$url->setSortOrder(20);
		$this->addVisual($url);

		$driversUrl = new PTA_Control_Form_Text('driversUrl', 'Drivers URL');
		$driversUrl->setSortOrder(21);
		$this->addVisual($driversUrl);

		$desc = new PTA_Control_Form_TextArea('shortDescr', 'Description');
		$desc->setSortOrder(40);
		$this->addVisual($desc);
	}

	private function _initDinamicFields()
	{
		$categoryFieldTable = PTA_DB_Table::get('Catalog_Category_Field');
		$fieldsTable = PTA_DB_Table::get('Catalog_Field');

		$categoryFields = (array)$categoryFieldTable->getFieldsByCategory(
			$this->_product->getCategoryId(), true, true
		);

		if ($this->_product->getId()) {
			$fieldsValues = $this->_product->buildCustomFields($categoryFields);
		} else {
			$fieldsValues = array();
		}

		$name = $fieldsTable->getFieldByAlias('alias');
		$title = $fieldsTable->getFieldByAlias('title');
		$sortOrder = $categoryFieldTable->getFieldByAlias('sortOrder');
		$fieldId = $categoryFieldTable->getFieldByAlias('fieldId');
		$fieldType = $fieldsTable->getFieldByAlias('fieldType');

		$fieldsIds = array();
		foreach ($categoryFields as $fieldArray) {
			if ($fieldArray[$fieldType] == PTA_Control_Form_Field::TYPE_SELECT) {
				$fieldsIds[$fieldArray[$fieldId]] = $fieldArray[$fieldId];
			}
		}

		$selectsOptions = array();
		if (!empty($fieldsIds)) {
			$valueTable = PTA_DB_Table::get('Catalog_Field_Value');

			$valueIdField = $valueTable->getPrimary();
			$fieldIdField = $valueTable->getFieldByAlias('fieldId');
			$valueField = $valueTable->getFieldByAlias('value');

			foreach ($valueTable->getFieldValues($fieldsIds) as $fieldValue) {
				$selectsOptions[$fieldValue[$fieldIdField]][] = array(
					$fieldValue[$valueIdField], $fieldValue[$valueField]
				);
			}
		}

		$orderPosition = 100;
		foreach ($categoryFields as $fieldArray) {
			if (!empty($selectsOptions[$fieldArray[$fieldId]])) {
				usort($selectsOptions[$fieldArray[$fieldId]], array($this, '_sortOptions'));
			} else {
				$selectsOptions[$fieldArray[$fieldId]] = array();
			}
			$options = array(
				'name' => $fieldArray[$name],
				'label' => $fieldArray[$title],
				'sortOrder' => (
					empty($fieldArray[$sortOrder]) ? ++$orderPosition : $fieldArray[$sortOrder]
				),
				'options' => $selectsOptions[$fieldArray[$fieldId]]
			);

			$field = PTA_Control_Form_Field::getFieldByType(
				$fieldArray[$fieldType], 
				"{$fieldArray[$name]}_{$fieldArray[$fieldId]}",
				$options
			);

			if (is_object($field)) {
				if (!empty($fieldsValues[$fieldArray[$name]])) {
					$field->setValue($fieldsValues[$fieldArray[$name]]);
				}
				$field->setVar('fieldId', $fieldArray[$fieldId]);
				$this->addVisual($field);
			}
		}
	}
	
	private function _sortOptions($a, $b)
	{
		if ($a[1] == $b[1]) {
			return 0;
 		}
		return ($a[1] < $b[1]) ? -1 : 1;
	}

	private function _filterFields($fields, $firstField, $secondField)
	{
		$resData = array();
		foreach ($fields as $field) {
			$resData[] = array(@$field[$firstField], $field[$secondField]);
		}
		return $resData;
	}

	public function onLoad()
	{
		$data = new stdClass();
		$this->_product->loadTo($data);
		return $data;
	}

	public function onSubmit(&$data)
	{
		$invalidFields = $this->validate($data);
		if (!empty($invalidFields)) {
			foreach ($invalidFields as $field) {
				$this->message(
					PTA_Object::MESSAGE_ERROR,
					'Field "' . $field->getLabel() . '" is required!'
				);
			}
			
			return false;
		}

		if (!empty($data->categoryId)) {
			$data->showInCategories[] = intval($data->categoryId);
		}

		$productTable = PTA_DB_Table::get('Catalog_Product');
		
		$this->_product->loadFrom($data);
		$savedProduct = $productTable->findByFields(
			array('categoryId', 'title'),
			array(
				$this->_product->getCategoryId(),
				$this->_product->getTitle()
			)
		);

		if (!empty($savedProduct)) {
			$savedProduct = current($savedProduct);
			$this->_product->setId(
				$savedProduct[$productTable->getPrimary()]
			);
		}

		if ($this->_copy) {
			$this->_product->setId(null);
			$this->_product->setDate(date("Y-m-d H:i:s"));
		}
/*
		$brand = PTA_DB_Object::get('Catalog_Brand', $this->_product->getBrandId());
		if (($imgFile = PTA_Util::upload($brand->getContentPhotoPath()))) {
			if (!empty($oldImg)) {
				PTA_Util::unlink(PTA_CONTENT_PATH . '/' . $oldImg);
			}
			$this->_product->setImage($imgFile);
		}
*/
		//var_dump($data->categoryId);
		$this->_product->setCategoryId($data->categoryId);
		if (!empty($data->showInCategories)) {
			$this->_product->setShowInCategories($data->showInCategories);
		}
		if ($this->_product->save()) {
			$this->_product->saveCustomFields($data);
			$this->message(
				PTA_Object::MESSAGE_SUCCESS,
				'Product ' . $this->_product->getTitle() . ' successfully saved!'
			);
			$this->redirect($this->getApp()->getActiveModule()->getModuleUrl());
		} else {
			$this->message(
				PTA_Object::MESSAGE_ERROR,
				'Error while ' . $this->_product->getTitle() . ' saving!'
			);
		}
		return true;
	}

}
