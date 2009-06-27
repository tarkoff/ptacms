<?php
/**
 * Form Field Select
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Control_Form_Select extends PTA_Control_Form_Field 
{
	function __construct ($prefix, $label = '', $mandatory = false, $options = array(), $value = null)
	{
			parent::__construct($prefix, $label, $mandatory, $value);
			$this->setOptions($options);
	}

	public function init()
	{
		parent::init();
		$this->setFieldType(PTA_Control_Form_Field::TYPE_SELECT);
	}

	public function setSelected($value)
	{
		$this->setValue($value);
	}
	
	public function setValue($value)
	{
		$this->setVar('value', (array)$value);
	}

	public function getSelected()
	{
		return $this->getValue();
	}

	public function addOption($option)
	{
		$options = (array)$this->getOptions();

		$options[] = $option;
		$this->setOptions($options);
	}

	public function getOptions()
	{
		return $this->getVar('options');
	}

	public function setOptions($options)
	{
		$this->setVar('options', (array)$options);
	}

	/**
	 * build option from array by setted fields
	 *
	 * @param array $data
	 * @param string $valueField
	 * @param string $labelField
	 * @return array
	 */
	public function setOptionsFromArray($data, $valueField, $labelField)
	{
		if (!is_array($data)) {
			return array();
		}

		$resData = array();
		foreach ($data as $field) {
			$resData[] = array(@$field[$valueField], $field[$labelField]);
		}

		$this->setOptions($resData);
		return $resData;
	}

	public function setMultiple($multiple = true)
	{
		$this->setVar('multiple', $multiple);
		if ($multiple) {
			$this->setArrayMode(true);
		}
	}

	public function getMultiple()
	{
		return $this->getVar('multiple');
	}
}
