<?php
/**
 * Product Field Edit Form
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Fields_editFieldsValuesForm extends PTA_Control_Form 
{
	private $_field;
	private $_copy;
	private $_values = array();

	public function __construct($prefix, $field, $copy = false)
	{
		$this->_field = $field;
		$this->_copy = $copy;

		parent::__construct($prefix);

		$this->setTitle('Field Values Edit Form');
	}

	public function initForm()
	{
		$title = new PTA_Control_Form_Select(
			'title', 'Field', true, 
			array(
				array(0, $this->_field->getTitle())
			)
		);
		$title->setSortOrder(100);
		$title->setDisabled(true);
		$this->addVisual($title);

		$valuesTable = PTA_DB_Table::get('Catalog_Field_Value');
		$fieldValues = $valuesTable->getFieldValues($this->_field->getId());
		$valueIdField = $valuesTable->getPrimary();
		$valueValueField = $valuesTable->getFieldByAlias('value');

		$sortOrder = 100;
		foreach ($fieldValues as $field) {
			$value = new PTA_Control_Form_Text(
				'value_' . $field[$valueIdField],
				$field[$valueIdField],
				false,
				$field[$valueValueField]
			);
			$value->setSortOrder(++$sortOrder);
			$this->addVisual($value);
			$this->_values[$field[$valueIdField]] = $field[$valueValueField];
		}

		$value = new PTA_Control_Form_Text('newValue', 'New Value', false);
		$value->setValue('');
		$value->setSortOrder(++$sortOrder);
		$this->addVisual($value);

		$submit = new PTA_Control_Form_Submit('submit', 'Save', true, 'Save Values');
		$submit->setSortOrder(400);
		$this->addVisual($submit);
	}

	public function onLoad()
	{
		$data = new stdClass();

		$this->_field->loadTo($data);
		//$data->submit = 'save';

		return $data;
	}

	public function onSubmit(&$data)
	{
		$invalidFields = $this->validate($data);
		if (!empty($invalidFields)) {
			foreach ($invalidFields as $field) {
				echo 'Filed ' . $field->getLabel() . ' is required!<br />';
			}

			return false;
		}

		$valuesTable = PTA_DB_Table::get('Catalog_Field_Value');
		$valueIdField = $valuesTable->getPrimary();
		$valueValueField = $valuesTable->getFieldByAlias('value');
		
		if (empty($this->_values)) {
			$fieldValues = $valuesTable->getFieldValues($this->_field->getId());
			foreach ($fieldValues as $value) {
				$this->_values[$value[$valueIdField]] = $value[$valueValueField];
			}
		}
		$forRemove = array();
		$forUpdate = array();
		foreach ($this->_values as $valueId => $value) {
			$alias = 'value_' . $valueId;
			if (isset($data->$alias)) {
				if (empty($data->$alias)) {
					$forRemove[$valueId] = $valueId;
					$valuesTable->delete(
						$valuesTable->getAdapter()->quoteInto(
							$valueIdField . ' = ?', intval($valueId)
						)
					);
				} elseif ($data->$alias != $value) {
					$forUpdate[$valueId] = $data->$alias;
				}
			}
		}
		
		if (!empty($data->newValue)) {
			$fieldValue = PTA_DB_Object::get('Catalog_Field_Value');
			$fieldValue->setFieldId($this->_field->getId());
			$fieldValue->setValue($data->newValue);
			$fieldValue->save();
		}
		
//var_dump($forUpdate, $forRemove, $data, $this->_values);
		if (
			$valuesTable->saveFieldValues($forUpdate)
		) {
			//$this->redirect($this->getApp()->getModule('activeModule')->getModuleUrl());
		}

		return true;
	}
}
