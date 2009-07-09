<?php
/**
 * Add Custom Fields To Fields Grpoups Form
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: addFieldsForm.php 68 2009-06-27 15:31:31Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/
class FieldsGroups_addFieldsForm extends PTA_Control_Form 
{
	private $_fieldGroup;

	public function __construct($prefix, $fieldGroup)
	{
		$this->_fieldGroup = $fieldGroup;

		parent::__construct($prefix);

		$this->setTitle('Add Fields To "' . $fieldGroup->getTitle() . '" Field Group');
	}

	public function initForm()
	{
		$groupFieldsTable = $this->_fieldGroup->getTable();
		$fieldsTable = PTA_DB_Table::get('Catalog_Field');
		$catFieldsTable = PTA_DB_Table::get('Catalog_Category_Field');

		$notfieldGroupFields = (array)$groupFieldsTable->getNotGroupFields(
			$this->_fieldGroup->getId(),
			$this->_fieldGroup->getCategoryId()
		);
		$fieldGroupFields = (array)$groupFieldsTable->getGroupFields(
			$this->_fieldGroup->getId(),
			$this->_fieldGroup->getCategoryId()
		);

		$select = new PTA_Control_Form_Select('notfieldGroupFields', 'Fields For Adding', false);
		$select->setOptionsFromArray(
			$notfieldGroupFields,
			$catFieldsTable->getPrimary(),
			$fieldsTable->getFieldByAlias('title')
		);
		$select->addOption(array(0, '- Empty -'));
		$select->setSortOrder(100);
		$select->setMultiple(true);
		$this->addVisual($select);

		$select = new PTA_Control_Form_Select('fieldGroupFields', 'Field Group Fields', false);
		$select->setOptionsFromArray(
			$fieldGroupFields,
			$catFieldsTable->getPrimary(),
			$fieldsTable->getFieldByAlias('title')
		);
		$select->addOption(array(0, '- Empty -'));
		$select->setSortOrder(110);
		$select->setMultiple(true);
		$this->addVisual($select);
		
		$this->setVar('fieldGroupFields', $fieldGroupFields);
		
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

		$data->fieldGroupFields = array_diff((array)@$data->fieldGroupFields, array(0, null));
		$data->notfieldGroupFields = array_diff((array)@$data->notfieldGroupFields, array(0, null));
		
		$saved = true;
		if (!empty($data->fieldGroupFields)) {
			$saved = $this->_fieldGroup->removeFields($data->fieldGroupFields);
		}

		if (!empty($data->notfieldGroupFields)) {
			$saved = $this->_fieldGroup->addFields($data->notfieldGroupFields);
		}

		if ($saved) {
			//$this->redirect($this->getApp()->getModule('activeModule')->getModuleUrl());
		}
	}

}
