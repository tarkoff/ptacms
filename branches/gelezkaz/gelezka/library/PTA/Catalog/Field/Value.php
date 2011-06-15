<?php
/**
 * Product Field Value
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Field_Value extends PTA_DB_Object 
{
	private $_fieldId;
	private $_value;

	public function getFieldId()
	{
		return $this->_fieldId;
	}

	public function setFieldId($fieldId)
	{
		$this->_fieldId = $fieldId;
	}
	
	public function getValue()
	{
		return $this->_value;
	}

	public function setValue($value)
	{
		$this->_value = $value;
	}
}
