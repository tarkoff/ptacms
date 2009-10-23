<?php
/**
 * Catalog Category Filter Field
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: CategoryField.php 62 2009-05-31 16:59:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Category_Filter_Field extends PTA_DB_Object 
{
	private $_categoryId;
	private $_categoryFieldId;
	private $_fieldType;
	private $_sortOrder;
	private $_autocomplete = 0;

	public function getCategoryId()
	{
		return $this->_categoryId;
	}

	public function setCategoryId($value)
	{
		$this->_categoryId = (int)$value;
	}

	public function getCategoryFieldId()
	{
		return $this->_categoryFieldId;
	}

	public function setCategoryFieldId($value)
	{
		$this->_categoryFieldId = (int)$value;
	}

	public function getSortOrder()
	{
		return $this->_sortOrder;
	}

	public function setSortOrder($value)
	{
		$this->_sortOrder = (int)$value;
	}

	public function getFieldType()
	{
		return $this->_fieldType;
	}

	public function setFieldType($fieldType)
	{
		$this->_fieldType = $fieldType;
	}

	public function getAutocomplete()
	{
		return $this->_autocomplete;
	}

	public function setAutocomplete($autocomplete = 0)
	{
		$this->_autocomplete = intval((boolean)$autocomplete);
	}
}
