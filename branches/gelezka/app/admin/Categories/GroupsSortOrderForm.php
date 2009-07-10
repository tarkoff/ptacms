<?php
/**
 * Add Custom Fields To Category Form
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: FieldsSortOrderForm.php 76 2009-07-04 19:06:24Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/
class Categories_GroupsSortOrderForm extends PTA_Control_Form 
{
	private $_category;
	private $_fieldsGroupTable;
	private $_groupFields = array();

	public function __construct($prefix, $category)
	{
		$this->_category = $category;
		$this->_fieldsGroupTable = PTA_DB_Table::get('Catalog_Field_Group');

		parent::__construct($prefix);

		$this->setTitle('Fields Groups Orderig For "' . $category->getTitle() . '" Category');
	}

	public function initForm()
	{
		$groupIdField = $this->_fieldsGroupTable->getPrimary();
		$groupOrderField = $this->_fieldsGroupTable->getFieldByAlias('sortOrder');
		$groupTitleField = $this->_fieldsGroupTable->getFieldByAlias('title');

		foreach ($this->getCategoryGroup() as $field) {
			$options = array(
				'value' => $field[$groupOrderField],
				'label' => $field[$groupTitleField],
				'sortOrder' => $field[$groupOrderField]
			);
			$input = PTA_Control_Form_Field::getFieldByType(
				PTA_Control_Form_Field::TYPE_TEXT,
				'sortOrder_' . $field[$groupIdField],
				$options
			);
			$this->addVisual($input);
		}

		$submit = new PTA_Control_Form_Submit('submit', 'Save Groups Ordering', true, 'Save Groups Ordering');
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

		$categoryGroupIdField = $this->_fieldsGroupTable->getPrimary();
		$categoryGroupOrderField = $this->_fieldsGroupTable->getFieldByAlias('sortOrder');

		$sortOrders = array();
		foreach ($this->getCategoryGroup() as $group) {
			$groupAlias = 'sortOrder_' . $group[$categoryGroupIdField];
			if (
				!empty($data->$groupAlias)
				&& ($data->$groupAlias != $group[$categoryGroupOrderField])
			) {
				$sortOrders[$group[$categoryGroupIdField]] = $data->$groupAlias;
			}
		}

		if ($this->_fieldsGroupTable->setFieldsSortOrder($sortOrders)) {
			$this->message(
				PTA_Object::MESSAGE_SUCCESS,
				'Category Groups Order Successfully Saved!'
			);
			//$this->redirect($this->getApp()->getModule('activeModule')->getModuleUrl());
		} else {
			$this->message(
				PTA_Object::MESSAGE_ERROR,
				'Error While Category Groups Order Saving!'
			);
			return false;
		}
		return true;
	}
	
	public function getCategoryGroup()
	{
		if (empty($this->_groupFields)) {
			$this->_groupFields = (array)$this->_fieldsGroupTable->getCategoryGroups(
				$this->_category->getId()
			);
		}
		
		return $this->_groupFields;
	}

}
