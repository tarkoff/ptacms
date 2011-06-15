<?php
/**
 * Form Field Prototype
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

abstract class PTA_Control_Form_Field extends PTA_Object
{
	const TYPE_TEXT = 1;
	const TYPE_TEXTAREA = 2;
	const TYPE_RADIO = 3;
	const TYPE_SUBMIT = 4;
	const TYPE_IMAGE = 5;
	const TYPE_SELECT = 6;
	const TYPE_CHECKBOX = 7;
	const TYPE_FIELDSGROUP = 8;
	const TYPE_PASSWORD = 9;
	const TYPE_FILE = 10;
	const TYPE_HIDDEN = 11;

	function __construct ($prefix, $label = '', $mandatory = false, $value = null)
	{
		$this->setPrefix($prefix);
		$this->setLabel($label);
		$this->setMandatory($mandatory);
		$this->setValue($value);
	}

	/**
	 * Retrun possible field types
	 *
	 * @return array
	 */
	public static function getPossibleFields()
	{
		return array(
			self::TYPE_TEXT => array(self::TYPE_TEXT, 'Text'),
			self::TYPE_TEXTAREA => array(self::TYPE_TEXTAREA, 'Text Area'),
			self::TYPE_RADIO => array(self::TYPE_RADIO, 'Radio Group'),
			self::TYPE_SUBMIT => array(self::TYPE_SUBMIT, 'Submit'),
			self::TYPE_IMAGE => array(self::TYPE_IMAGE, 'Image'),
			self::TYPE_SELECT => array(self::TYPE_SELECT, 'Select'),
			self::TYPE_CHECKBOX => array(self::TYPE_CHECKBOX, 'Checkbox'),
			self::TYPE_FIELDSGROUP => array(self::TYPE_FIELDSGROUP, 'Fields Group')
		);
	}

	/**
	 * Get Form field by field type
	 *
	 * @param int $fieldType
	 * @param string $prefix
	 * @param array $options
	 * @return PTA_Control_Form_Field
	 */
	public static function getFieldByType($fieldType, $prefix, $options = null)
	{
		$field = null;
		switch ($fieldType) {
			case self::TYPE_TEXT:
				$field = new PTA_Control_Form_Text($prefix);
			break;

			case self::TYPE_HIDDEN:
				$field = new PTA_Control_Form_Hidden($prefix);
			break;

			case self::TYPE_TEXTAREA:
				$field = new PTA_Control_Form_TextArea($prefix);
			break;

			case self::TYPE_CHECKBOX:
				$field = new PTA_Control_Form_Checkbox($prefix);
			break;

			case self::TYPE_RADIO:
				$field = new PTA_Control_Form_Radio($prefix);
			break;

			case self::TYPE_SUBMIT:
				$field = new PTA_Control_Form_Submit($prefix);
			break;

			case self::TYPE_IMAGE:
				$field = null;
			break;

			case self::TYPE_SELECT:
				$field = new PTA_Control_Form_Select($prefix);
			break;
		}

		return self::_setOptions($field, $options);
	}

	public function run()
	{
		parent::run();

		$httpValue = $this->getHttpVar($this->getFormPrefix() . '_' . $this->getName());
		if ($this->getForm()->submitted() && !empty($httpValue)) {
			$this->setValue($httpValue);
		}
	}

	private static function _setOptions($field, $options)
	{
		$options = (array)$options;
		if (empty($field) || empty($options)) {
			return null;
		}

		foreach ($options as $option => $value) {
			$method = "set{$option}";
			if (method_exists($field, $method)) {
				//call_user_func_array(array($field, $method), $value);
				$field->$method($value);
			}
		}

		return $field;
	}

	public function setPrefix($value)
	{
		parent::setPrefix($value);
		$this->setName($value);
	}

	public function getValue()
	{
		return $this->getVar('value');
	}

	public function setValue($value)
	{
		$this->setVar('value', $value);
	}

	public function getLabel()
	{
		return $this->getVar('label');
	}

	public function setLabel($value)
	{
		$this->setVar('label', $value);
	}

	public function getName()
	{
		return $this->getVar('name');
	}

	public function setName($value)
	{
		$this->setVar('name', $value);
	}

	public function getCssClass()
	{
		return $this->getVar('cssClass');
	}

	public function setCssClass($value)
	{
		$this->setVar('cssClass', $value);
	}

	public function getFieldType()
	{
		return $this->getVar('type');
	}

	public function setFieldType($value)
	{
		$this->setVar('type', $value);
	}

	public function setSortOrder($value)
	{
		$this->setVar('sortOrder', (int)$value);
	}

	public function getSortOrder()
	{
		return $this->getVar('sortOrder');
	}

	public function setMandatory($value)
	{
		$this->setVar('mandatory', (int)$value);
	}

	public function isMandatory()
	{
		return $this->getVar('mandatory');
	}

	public function setFormPrefix($value)
	{
		$this->setVar('formPrefix', $value);
	}

	public function getFormPrefix()
	{
		return $this->getVar('formPrefix');
	}
	
	public function isValid()
	{
		if ($this->isMandatory() && !$this->getValue()) {
			return false;
		}
		return true;
	}

	/**
	 * Return field form
	 *
	 * @return PTA_Control_Form
	 */
	public function getForm()
	{
		return $this->getApp()->getVisual($this->getFormPrefix(), true);
	}

	public function isDisabled()
	{
		return $this->getVar('disabled');
	}

	public function setDisabled($value = true)
	{
		$this->setVar('disabled', $value);
	}

	/**
	 * add some var to template
	 *
	 * @param strimg $name
	 * @param mixed $value
	 */
	public function setProperty($name, $value)
	{
		if (!($properties = $this->getVar('properties'))) {
			$properties = new stdClass();
		}

		$properties->$name = $value;
		$this->setVar('properies', $properties);
	}

	public function getPtoperty($name)
	{
		if (($properties = $this->getVar('properties'))) {
			return (isset($properties->$name) ? $properties->$name : null);
		}

		return null;
	}

	public function setIndex($value)
	{
		$this->setVar('index', $value);
	}

	public function getIndex()
	{
		return $this->getVar('index');
	}

	/**
	 * Set array mode for field
	 *
	 * @param boolean $mode
	 * @param int $deep
	 */
	public function setArrayMode($mode = true, $deep = 1)
	{
		$this->setVar('arrayMode', $mode);
		$this->setVar('arrayModeDeep', $deep);
	}

	public function setReadOnly($mode = true)
	{
		$this->setVar('readOnly', $mode);
	}
	
	public function isREadOnly()
	{
		return $this->getVar('readOnly');
	}
}
