<?php
/**
 * Short description for file
 *
 * @package Catalog
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_CategoryField extends PTA_DB_Object 
{
	private $_categoryId;
	private $_fieldId;
	private $_sortOrder;

	public function getCategoryid()
	{
		return $this->_categoryId;
	}

	public function setCategoryid($value)
	{
		$this->_categoryId = (int)$value;
	}

	public function getFieldId()
	{
		return $this->_fieldId;
	}

	public function setFieldId($value)
	{
		$this->_fieldId = (int)$value;
	}

	public function getSortOrder()
	{
		return $this->_sortOrder;
	}

	public function setSortOrder($value)
	{
		$this->_sortOrder = (int)$value;
	}

	/**
 	 * Load object By ID
	 *
	 * @method loadById
	 * @access public
	 * @param int $id
	 * @return boolean
	*/	
	public function loadByCategoryId($id)
	{
		$info = $this->getTable()->findById(intval($id));

		if (empty($info)) {
			return false;
		}

		$this->loadFrom(current($info));
		return true;
	}

}
