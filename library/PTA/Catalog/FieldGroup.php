<?php
/**
 * Field Group
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_FieldGroup extends PTA_DB_Object
{

	private $_alias;
	private $_title;
	private $_categoryId;

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
	
	/**
	 * Remove Fields From Field Group
	 *
	 * @param array $fieldsIds
	 * @return boolean
	 */
	public function removeFields($fieldsIds = array())
	{
		return PTA_DB_Table::get('Catalog_FieldGroup_Field')
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

		return PTA_DB_Table::get('Catalog_FieldGroup_Field')
			->addGroupFields($this->getId(), $fieldsIds);
	}
}
