<?php
/**
 * Field Group
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: FieldGroup.php 71 2009-07-04 10:57:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Field_Group extends PTA_DB_Object
{

	private $_alias;
	private $_title;
	private $_categoryId;
	private $_sortOrder;

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

	public function setAlias($alias)
	{
		$this->_alias = $alias;
	}

	public function getCategoryId()
	{
		return $this->_categoryId;
	}

	public function setCategoryId($categoryId)
	{
		$this->_categoryId = intval($categoryId);
	}
	
	public function getSortOrder()
	{
		return $this->_sortOrder;
	}

	public function setSortOrder($order)
	{
		$this->_sortOrder = intval($order);
	}

	/**
	 * Remove Fields From Field Group
	 *
	 * @param array $fieldsIds
	 * @return boolean
	 */
	public function removeFields($fieldsIds = array())
	{
		return PTA_DB_Table::get('Catalog_Field_Group_Field')
			->removeGroupFields($this->getId(), $fieldsIds);
	}
	
	/**
	 * Add Fields To Field Group
	 *
	 * @param array $fieldsIds
	 * @return boolean
	 */
	public function addFields($fieldsIds)
	{
		if (empty($fieldsIds)) {
			return false;
		}

		return PTA_DB_Table::get('Catalog_Field_Group_Field')
			->addGroupFields($this->getId(), $fieldsIds);
	}
}
