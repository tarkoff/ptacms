<?php
/**
 * Catalog Group Field Model
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

class KIT_Catalog_Field_Group_Field extends KIT_Model_Abstract
{
	private $_categoryGroupId;
	private $_fieldId;
	private $_sortOrder;
	private $_inFilter = 0;
	
	public function getFieldId()
	{
		return $this->_fieldId;
	}

	public function setFieldId($id)
	{
		$this->_fieldId = $id;
	}

	public function getCategoryGroupId()
	{
		return $this->_categoryGroupId;
	}

	public function setCategoryGroupId($groupId)
	{
		$this->_categoryGroupId = intval($groupId);
	}
	
	public function getSortOrder()
	{
		return $this->_sortOrder;
	}

	public function setSortOrder($order)
	{
		$this->_sortOrder = intval($order);
	}

	public function getInFilter()
	{
		return $this->_inFilter;
	}

	public function setInFilter($inFilter)
	{
		$this->_inFilter = intval($inFilter);
	}
}
