<?php
/**
 * Product Field Edit Form
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Fields_editForm extends PTA_Control_Form 
{
	private $_field;
	private $_copy;

	public function __construct($prefix, PTA_Catalog_Field $field, $copy = false)
	{
		$this->_field = $field;
		$this->_copy = $copy;

		parent::__construct($prefix);

		$this->setTitle('Field ' . $field->getTitle() . ' Edit Form');
	}

	public function initForm()
	{
		$title = new PTA_Control_Form_Text('title', 'Field Title', true, '');
		$title->setSortOrder(100);
		$this->addVisual($title);

		$alias = new PTA_Control_Form_Text('alias', 'Field Alias', true, '');
		$alias->setSortOrder(200);
		$this->addVisual($alias);

		$fieldType = new PTA_Control_Form_Select(
			'fieldtype', 'Field Type', true, 
			array(
				array(PTA_Control_Form_Field::TYPE_SELECT, 'Select'),
				array(PTA_Control_Form_Field::TYPE_TEXT, 'Text'),
				array(PTA_Control_Form_Field::TYPE_CHECKBOX, 'Checkbox')
			)
		);
		$fieldType->setSortOrder(300);
		$this->addVisual($fieldType);

		$autoc = new PTA_Control_Form_Checkbox('autocomplete', 'Autocomplete', false, '1');
		$autoc->setChecked(false);
		$autoc->setSortOrder(320);
		$this->addVisual($autoc);

		$submit = new PTA_Control_Form_Submit('submit', 'Save', true, 'Save Field');
		$submit->setSortOrder(400);
		$this->addVisual($submit);
	}

	public function onLoad()
	{
		$data = new stdClass();

		$this->_field->loadTo($data);

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

		$this->_field->loadFrom($data);

		if ($this->_copy) {
			$this->_field->setId(null);
		}

		if (empty($data->autocomplete)) {
			$this->_field->setAutocomplete(0);
		}

		if ($this->_field->save() || $this->_copy) {
			$this->message(
				PTA_Object::MESSAGE_SUCCESS,
				'Field Successfully Saved!'
			);
			$this->redirect($this->getApp()->getActiveModule()->getModuleUrl());
		} else {
			$this->message(
				PTA_Object::MESSAGE_ERROR,
				'Error While Field Saving!'
			);
			return false;
		}

		return true;
	}
}
