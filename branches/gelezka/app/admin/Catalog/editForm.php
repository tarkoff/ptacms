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

		$this->setTitle('Add/Edit Product Form');
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
		$category = new PTA_Control_Form_Select(
			'categoryId', 'Category', false,
			$catsTable->getSelectedFields(
				array('id', 'title'),
				$catsTable->getFieldByAlias('parentId') . ' <> 0'
			), 
			$this->_product->getCategoryId()
		);
		$category->setSortOrder(16);
		//$category->setMultiple(true);
		$this->addVisual($category);

		$category = new PTA_Control_Form_Select(
			'showInCategories', 'Show in categories', false,
			$catsTable->getSelectedFields(
				array('id', 'title'),
				$catsTable->getFieldByAlias('parentId') . ' <> 0'
				. ' and ' . $catsTable->getPrimary() . ' <> ' .(int)$this->_product->getCategoryId()
			), 
			$this->_product->getShowInCategories()
		);
		$category->setSortOrder(17);
		$category->setMultiple(true);
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
		$categoryFieldTable = PTA_DB_Table::get('Catalog_CategoryField');
		$fieldsTable = PTA_DB_Table::get('Catalog_Field');

		$categoryFields = (array)$categoryFieldTable->getFieldsByCategory(
			$this->_product->getCategoryId(), true, true
		);
//var_dump($this->_product->getCategoryId());
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
			if (!empty($fieldsValues[$fieldArray[$name]])) {
				$field->setValue($fieldsValues[$fieldArray[$name]]);
			}
			if (!empty($field)) {
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
				echo 'Field "' . $field->getLabel() . '" is required!<br />';
			}
			
			return false;
		}

		$productTable = PTA_DB_Table::get('Catalog_Product');
		
		//$oldImg = $this->_product->getImage();
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
		$this->_product->saveCustomFields($data);
		if ($this->_product->save()) {
			$this->redirect($this->getApp()->getModule('activeModule')->getModuleUrl());
		}
		return true;
	}

}
