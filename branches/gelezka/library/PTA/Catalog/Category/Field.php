<?php
/**
 * Catalog Category Custom Field
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: CategoryField.php 62 2009-05-31 16:59:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Category_Field extends PTA_DB_Object 
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
