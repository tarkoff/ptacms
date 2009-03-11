<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/
class Categories_addProductForm extends PTA_Control_Form 
{
	private $_product;
	private $_category;

	public function __construct($prefix, $product)
	{
		$this->_product = $product;
		$this->_category = new PTA_Catalog_Category('category');
		$this->_category->loadById($product->getCategoryId());

		parent::__construct($prefix);

		$this->setTitle('Add Product To "' . $this->_category->getTitle() . '" Category');
	}

	public function initForm()
	{
		$this->_initStaticFields();
		$this->_initDinamicFields();
		$data = new stdClass();
		$data->author = 'Taras Pavuk';
		$data->year = 1984;

		$submit = new PTA_Control_Form_Submit('submit', 'Remove Field', true, 'Save Book');
		$submit->setSortOrder(1000);
		$this->addVisual($submit);
	}

	private function _initStaticFields()
	{
		$title = new PTA_Control_Form_Text('title', 'Book Title');
		$title->setSortOrder(10);
		$this->addVisual($title);

		$url = new PTA_Control_Form_Text('url', 'Book URL');
		$url->setSortOrder(20);
		$this->addVisual($url);

		$image = new PTA_Control_Form_Text('image', 'Book Photo');
		$image->setSortOrder(30);
		$this->addVisual($image);

		$desc = new PTA_Control_Form_TextArea('shortDescr', 'Book Description');
		$desc->setSortOrder(40);
		$this->addVisual($desc);
	}

	private function _initDinamicFields()
	{
		$categoryFieldTable = PTA_DB_Table::get('Catalog_CategoryField');
		$fieldsTable = PTA_DB_Table::get('Catalog_Field');

		$categoryFields = (array)$categoryFieldTable->getFieldsByCategory($this->_category->getId(), true, true);

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
						'sortOrder' => (empty($fieldArray[$sortOrder]) ? ++$orderPosition : $fieldArray[$sortOrder])
			);

			$field = PTA_Control_Form_Field::getFieldByType(
													$fieldArray[$fieldType], 
													"{$fieldArray[$name]}_{$fieldArray[$fieldId]}",
													$options
											);
			$field->setValue(@$fieldsValues[$fieldArray[$name]]);
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
/*	
	public function onLoad()
	{
		$data = new stdClass();
		//$this->_category->loadTo($data);
		$data->sortOrder = 10;
		$data->submit = 'Remove Field';

		return $data;
	}
*/	
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
											array(
												'categoryId',
												'title'
											),
											array(
												$this->_product->getCategoryId(),
												$this->_product->getTitle()
											)
										);

		if (!empty($savedProduct)) {
			$savedProduct = current($savedProduct);
			$productPrimary = $productTable->getPrimary();
			$this->_product->setId(@$savedProduct[$productPrimary]);
		}
		if ($this->_product->save()) {
			$this->_product->saveCustomFields($data);
			$this->redirect($this->getApp()->getModule('activeModule')->getModuleUrl());
		}
	}

}
