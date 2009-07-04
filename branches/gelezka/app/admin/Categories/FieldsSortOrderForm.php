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
class Categories_FieldsSortOrderForm extends PTA_Control_Form 
{
	private $_category;
	private $_categoryFieldTable;
	private $_categoryFields = array();

	public function __construct($prefix, $category)
	{
		$this->_category = $category;
		$this->_categoryFieldTable = PTA_DB_Table::get('Catalog_CategoryField');

		parent::__construct($prefix);

		$this->setTitle('Fields Orderig For "' . $category->getTitle() . '" Category');
	}

	public function initForm()
	{
		$categoryFieldIdField = $this->_categoryFieldTable->getPrimary();
		$categoryFieldOrderField = $this->_categoryFieldTable->getFieldByAlias('sortOrder');
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
				echo 'Field "' . $field->getLabel() . '" is required!<br />';
			}

			return false;
		}

		$categoryFieldIdField = $this->_categoryFieldTable->getPrimary();
		$categoryFieldOrderField = $this->_categoryFieldTable->getFieldByAlias('sortOrder');

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

		if ($this->_categoryFieldTable->setFieldsSortOrder($sortOrders)) {
			//$this->redirect($this->getApp()->getModule('activeModule')->getModuleUrl());
		}
	}
	
	public function getCategoryFields()
	{
		if (empty($this->_categoryFields)) {
			$this->_categoryFields = (array)$this->_categoryFieldTable->getFieldsByCategory(
				$this->_category->getId(), true, true
			);
		}
		
		return $this->_categoryFields;
	}

}
