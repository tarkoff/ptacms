<?php
/**
 * Add Custom Fields To Category Form
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: FieldsSortOrderForm.php 64 2009-06-02 19:51:08Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/
class FieldsGroups_FieldsSortOrderForm extends PTA_Control_Form 
{
	private $_fieldGroup;
	private $_fieldGroupTable;
	private $_fieldsGroup = array();

	public function __construct($prefix, $category)
	{
		$this->_fieldGroup = $category;
		$this->_fieldGroupTable = PTA_DB_Table::get('Catalog_Category_Field');

		parent::__construct($prefix);

		$this->setTitle('Fields Orderig For "' . $category->getTitle() . '" Category');
	}

	public function initForm()
	{
		$categoryFieldIdField = $this->_fieldGroupTable->getPrimary();
		$categoryFieldOrderField = $this->_fieldGroupTable->getFieldByAlias('sortOrder');
		$fieldTitleField = PTA_DB_Table::get('Catalog_Field')->getFieldByAlias('title');

		foreach ($this->getCategoryFields() as $field) {
			$options = array(
				'value' => $field[$categoryFieldOrderField],
				'label' => $field[$fieldTitleField],
				'sortOrder' => $field[$categoryFieldOrderField]
			);
			$input = PTA_Control_Form_Field::getFieldByType(
				PTA_Control_Form_Field::TYPE_TEXT,
				'sortOrder_' . $field[$categoryFieldIdField],
				$options
			);
			$this->addVisual($input);
		}

		$submit = new PTA_Control_Form_Submit('submit', 'Save Fields Ordering', true, 'Save Fields Ordering');
		$submit->setSortOrder(5000);
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

		$categoryFieldIdField = $this->_fieldGroupTable->getPrimary();
		$categoryFieldOrderField = $this->_fieldGroupTable->getFieldByAlias('sortOrder');

		$sortOrders = array();
		foreach ($this->getCategoryFields() as $field) {
			$fieldAlias = 'sortOrder_' . $field[$categoryFieldIdField];
			if (
				!empty($data->$fieldAlias)
				&& ($data->$fieldAlias != $field[$categoryFieldOrderField])
			) {
				$sortOrders[$field[$categoryFieldIdField]] = $data->$fieldAlias;
			}
		}

		if ($this->_fieldGroupTable->setFieldsSortOrder($sortOrders)) {
			$this->message(
				PTA_Object::MESSAGE_SUCCESS,
				'Fields Order Successfully saved!'
			);
			//$this->redirect($this->getApp()->getModule('activeModule')->getModuleUrl());
		} else {
			$this->message(
				PTA_Object::MESSAGE_ERROR,
				'Error While Field Order Saving!'
			);
			return false;
		}
		return true;
	}
	
	public function getCategoryFields()
	{
		if (empty($this->_fieldsGroup)) {
			$this->_fieldsGroup = (array)$this->_fieldGroupTable->getFieldsByCategory(
				$this->_fieldGroup->getId(), true, true
			);
		}
		
		return $this->_fieldsGroup;
	}

}
