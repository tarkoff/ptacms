<?php
/**
 * Add Custom Fields To Category Form
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/
class Categories_addFieldsForm extends PTA_Control_Form 
{
	private $_category;
	private $_categoryFieldTable;

	public function __construct($prefix, $category)
	{
		$this->_category = $category;
		$this->_categoryFieldTable = PTA_DB_Table::get('Catalog_Category_Field');

		parent::__construct($prefix);

		$this->setTitle('Add Fields To "' . $category->getTitle() . '" Category');
	}

	public function initForm()
	{
		$categoryFieldTable = $this->_categoryFieldTable;
		$fieldsTable = PTA_DB_Table::get('Catalog_Field');

		$notCategoryFields = (array)$categoryFieldTable->getFieldsByCategory($this->_category->getId(), false, true);
		$categoryFields = (array)$categoryFieldTable->getFieldsByCategory($this->_category->getId(), true, true);

		$select = new PTA_Control_Form_Select('notCategoryFields', 'Fields For Adding', false);
		$select->setOptionsFromArray(
			$notCategoryFields,
			$fieldsTable->getPrimary(),
			$fieldsTable->getFieldByAlias('title')
		);
		$select->addOption(array(0, '- Empty -'));
		$select->setSortOrder(100);
		$select->setMultiple(true);
		$this->addVisual($select);

		$select = new PTA_Control_Form_Select('categoryFields', 'Category Fields', false);
		$select->setOptionsFromArray(
			$categoryFields,
			$fieldsTable->getPrimary(),
			$fieldsTable->getFieldByAlias('title')
		);
		$select->addOption(array(0, '- Empty -'));
		$select->setSortOrder(110);
		$select->setMultiple(true);
		$this->addVisual($select);
		
		$this->setVar('categoryFields', $categoryFields);
		
		$sortOrder = new PTA_Control_Form_Text('fieldSortOrder', 'Field Sort Order', false);
		$sortOrder->setSortOrder(120);
		$this->addVisual($sortOrder);

		$submit = new PTA_Control_Form_Submit('submit', 'Save Fields', true, 'Save');
		$submit->setSortOrder(300);
		$this->addVisual($submit);
	}

	public function onLoad()
	{
		$data = new stdClass();

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

		$data->categoryFields = array_diff((array)@$data->categoryFields, array(0, null));
		$data->notCategoryFields = array_diff((array)@$data->notCategoryFields, array(0, null));
		
		$saved = true;
		if (!empty($data->categoryFields)) {
			$saved = $this->_categoryFieldTable->delCategoryFields(
				$this->_category->getId(),
				$data->categoryFields
			);
		}

		if (!empty($data->notCategoryFields)) {
			$saved = $this->_categoryFieldTable->addCategoryFields(
				$this->_category->getId(),
				$data->notCategoryFields
			);
		}

		if ($saved) {
			//$this->redirect($this->getApp()->getModule('activeModule')->getModuleUrl());
		}
	}

}
