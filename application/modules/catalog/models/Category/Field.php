<?php
/**
 * Catalog Category Field Model
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
 * @version    $Id: Resource.php 278 2010-02-27 18:36:32Z TPavuk $
 */

class Catalog_Model_Category_Field extends KIT_Model_Abstract
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
}
