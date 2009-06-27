<?php
/**
 * Product Custom Field
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Field extends PTA_DB_Object 
{
	private $_title;
	private $_alias;
	private $_fieldType;

	public function getCategoryId()
	{
		return $this->_categoryId;
	}

	public function setCategoryId($value)
	{
		$this->_categoryId = (int)$value;
	}

	public function getTitle()
	{
		return $this->_title;
	}

	public function setTitle($title)
	{
		$this->_title = $title;
	}

	public function getAlias()
	{
		return $this->_alias;
	}

	public function setAlias($alais)
	{
		$this->_alias = $alais;
	}

	public function getFieldType()
	{
		return $this->_fieldType;
	}

	public function setFieldType($fieldType)
	{
		$this->_fieldType = $fieldType;
	}
}
