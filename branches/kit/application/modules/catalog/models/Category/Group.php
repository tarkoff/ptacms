<?php
/**
 * Catalog Category Field Group Model
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

class Catalog_Model_Category_Group extends KIT_Model_Abstract
{
	private $_categoryId;
	private $_groupId;
	private $_sortOrder;

	public function getCategoryId()
	{
		return $this->_categoryId;
	}

	public function setCategoryId($id)
	{
		$this->_categoryId = $id;
	}

	public function getGroupId()
	{
		return $this->_groupId;
	}

	public function setGroupId($groupId)
	{
		$this->_groupId = intval($groupId);
	}
	
	public function getSortOrder()
	{
		return $this->_sortOrder;
	}

	public function setSortOrder($order)
	{
		$this->_sortOrder = intval($order);
	}

}
