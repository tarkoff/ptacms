<?php
/**
 * Add Custom Fields To Category Form
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: addFieldsForm.php 201 2009-10-10 20:24:35Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/
class Categories_addFilterFieldsForm extends PTA_Control_Form
{
	private $_category;
	private $_categoryFieldTable;

	public function __construct($prefix, $category)
	{
		$this->_category = $category;
		$this->_categoryFieldTable = PTA_DB_Table::get('Catalog_Category_Field');

		parent::__construct($prefix);

		$title = 'Add Fields To "' . $category->getTitle() . '" Category';
		$this->setTitle($title);
//		$this->message(PTA_Object::MESSAGE_NOTICE, $title);

	}

	public function initForm()
	{
		$categoryFieldTable = $this->_categoryFieldTable;
		$filterFieldsTable = PTA_DB_Table::get('Catalog_Category_Filter_Field');

		$filterFields = $filterFieldsTable->getCategoryFields($this->_category->getId());
		$notFilterFields = $filterFieldsTable->getCategoryFields($this->_category->getId(), false);

		$select = new PTA_Control_Form_Hidden('filterFields', 'Category Fields', false);
		$select->setSortOrder(110);
		$this->addVisual($select);

		$fieldType = new PTA_Control_Form_Hidden('fieldtype', 'Field Type', false);
		$fieldType->setSortOrder(115);
		$this->addVisual($fieldType);

		$this->setVar(
			'fieldsTypes',
			array(
				array(PTA_Control_Form_Field::TYPE_SELECT, 'Select'),
				array(PTA_Control_Form_Field::TYPE_TEXT, 'Text'),
				array(PTA_Control_Form_Field::TYPE_CHECKBOX, 'Checkbox')
			)
		);

		$select = new PTA_Control_Form_Hidden('sortOrder', 'Field Sort Order', false);
		$select->setSortOrder(120);
		$this->addVisual($select);

		$select = new PTA_Control_Form_Hidden('autocomplete', 'Field Autocomplete', false);
		$select->setSortOrder(130);
		$this->addVisual($select);

		$this->setVar('filterFields', $filterFields);
		$this->setVar('notFilterFields', $notFilterFields);

		$submit = new PTA_Control_Form_Submit('submit', 'Save Fields', true, 'Save Filter Fields');
		$submit->setSortOrder(300);
		$this->addVisual($submit);
	}

	public function onLoad()
	{
		return new stdClass();
	}

	public function onSubmit(&$data)
	{
/*
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
*/
		$filterFieldsTable = PTA_DB_Table::get('Catalog_Category_Filter_Field');
		$filterFieldsTable->clearCategoryFields($this->_category->getId());

		$fieldIds = array();
		foreach ($data->filterFields as $fieldId => $fieldValue) {
			if (!empty($fieldValue)) {
				$fieldIds[] = intval($fieldId);
			}
		}

		$saved = $filterFieldsTable->setCategoryFields($this->_category->getId(), $fieldIds);

		if ($saved) {
			$this->message(
				PTA_Object::MESSAGE_SUCCESS,
				'Category Fields Successfully saved!'
			);
			//$this->redirect($this->getApp()->getActiveModule()->getModuleUrl());
		} else {
			$this->message(
				PTA_Object::MESSAGE_ERROR,
				'Error While Category Fields Saving!'
			);
			return false;
		}
		return true;
	}

}
