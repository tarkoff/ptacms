<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: editForm.php 34 2009-03-31 17:58:03Z TPavuk $
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

		$this->_category = new PTA_Catalog_Category('category');
		$this->_category->loadById($product->getCategoryId());

		parent::__construct($prefix);

		$this->setTitle('Add Product To "' . $this->_category->getTitle() . '" Category');
	}

	public function initForm()
	{
		$this->_initStaticFields();
		$this->_initDinamicFields();

		$submit = new PTA_Control_Form_Submit('submit', 'Remove Field', true, 'Save Item');
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
		$brand->addOption(array(0, '- Empty -'));
		$brand->setSelected((int)$this->_product->getBrandId());
		$this->addVisual($brand);
		
		$catsTable = PTA_DB_Table::get('Catalog_Category');
		$category = new PTA_Control_Form_Select(
			'categoryId', 
			'Category', 
			false,
			$catsTable->getSelectedFields(
				array('id', 'title'),
				$catsTable->getFieldByAlias('parentId') . ' <> 0'
			), 
			$this->_product->getCategoryId()
		);
		$category->setSortOrder(16);
		$this->addVisual($category);
		
		$url = new PTA_Control_Form_Text('url', 'URL');
		$url->setSortOrder(20);
		$this->addVisual($url);

		$image = new PTA_Control_Form_File('image', 'Photo');
		$image->setSortOrder(30);
		$image->getUploader()->setDestination(
			PTA_CONTENT_PHOTOS_PATH 
		);
		$image->isImage(true);
		$this->addVisual($image);

		$desc = new PTA_Control_Form_TextArea('shortDescr', 'Description');
		$desc->setSortOrder(40);
		$this->addVisual($desc);
	}

	private function _initDinamicFields()
	{
		$categoryFieldTable = PTA_DB_Table::get('Catalog_CategoryField');
		$fieldsTable = PTA_DB_Table::get('Catalog_Field');

		$categoryFields = $categoryFieldTable->getFieldsByCategory($this->_category->getId(), true, true);
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

		$orderPosition = 100;
		foreach ($categoryFields as $fieldArray) {
			$options = array(
				'name' => $fieldArray[$name],
				'label' => $fieldArray[$title],
				'sortOrder' => (
					empty($fieldArray[$sortOrder]) ? ++$orderPosition : $fieldArray[$sortOrder]
				)
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
		if (($image = $this->getVisual('photo'))) {
			$image->getUploader()->setDestination(PTA_CONTENT_PHOTOS_PATH);
			if ($image->upload()) {
				$this->_product->setImage($image->getValue());
			}
		}
*/
		if (($imgFile = PTA_Util::upload(PTA_CONTENT_PHOTOS_PATH))) {
			$this->_product->setImage($imgFile);
		}

		$this->_product->saveCustomFields($data);
		if ($this->_product->save()) {
			$this->redirect($this->getApp()->getModule('activeModule')->getModuleUrl());
		}
	}

}
