<?php
/**
 * Catalog Product Category Model
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
 * @version    $Id: Category.php 288 2010-03-28 16:10:01Z TPavuk $
 */

class KIT_Catalog_Product_Category extends KIT_Model_Abstract
{
	private $_productId;
	private $_categoryId;
	private $_isDefault;

	public function getProductId()
	{
		return $this->_productId;
	}

	public function setProductId($id)
	{
		$this->_productId = $id;
	}

	public function getCategoryId()
	{
		return $this->_categoryId;
	}

	public function setCategoryId($id)
	{
		$this->_categoryId = $id;
	}

	public function getIsDefault()
	{
		return $this->_isDefault;
	}

	public function setIsDefault($isDefault)
	{
		$this->_isDefault = $isDefault;
	}

	/**
	 * Save data to database
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function save($data = null)
	{
		if ($this->getIsDefault()) {
			$this->getDbTable()->unsetDefaultCategory($this->getProductId());
			$this->_id = null;
		}
		return parent::save($data);
	}
}
