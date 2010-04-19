<?php
/**
 * Catalog Field Value Edit Form
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Catalog
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id$
 */

class Catalog_Form_Fields_Value extends KIT_Form_Abstract
{
	/**
	 * @var Catalog_Model_Field_Value
	 */
	private $_value;
	private $_fieldId;

	public function __construct($id = 0, $fieldId = 0, $options = null)
	{
		$id = intval($id);
		$this->_fieldId = intval($fieldId);
		$this->_value = KIT_Model_Abstract::get('Catalog_Model_Field_Value', $id);
		!empty($this->_fieldId) || $this->_fieldId = $this->_value->getFieldId();

		parent::__construct($options);
		$this->setName('fieldsValuesEditForm');

		$fieldIdField = new Zend_Form_Element_Hidden('fieldId');
		$fieldIdField->setValue($this->_fieldId);
		$this->addElement($fieldIdField);

		$value = new Zend_Form_Element_Text('value');
		$value->setLabel('Value')
			  ->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$this->addElement($value);

		$submit = new Zend_Form_Element_Submit('submit');
		$this->addElement($submit);

		if (!empty($id)) {
			$this->loadFromModel($this->_value);
			$this->setLegend('Field Value Edit Form');
			$submit->setLabel('Save');
		} else {
			$this->setLegend('Field Value Add Form');
			$submit->setLabel('Add');
		}
	}

	public function submit()
	{
		if ($this->isPost()) {
			$formData = (array)$this->getPost();
			if ($this->isXmlHttpRequest()) {
				if (is_numeric($formData['id'])) {
					$formData['id'] = $formData['id'];
				} else {
					$formData['id'] = null;
				}
				foreach ($this->_value->getDbTable()->getFields() as $fieldAlias => $fieldName) {
					if (isset($formData[$fieldName])) {
						$formData[$fieldAlias] = $formData[$fieldName];
					}
				}
			}

			if ($this->isValid($formData)) {
				$this->_value->setOptions($this->getValues());
				$this->_value->setFieldId($this->_fieldId);
				return $this->_value->save();
			} else {
				$this->populate($formData);
			}
		}
		return false;
	}
}
