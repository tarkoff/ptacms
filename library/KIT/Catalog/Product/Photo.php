<?php
/**
 * Catalog Product Photo Model
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
 * @version    $Id: Photo.php 397 2010-05-04 20:46:34Z TPavuk $
 */

class KIT_Catalog_Product_Photo extends KIT_Model_Abstract
{
	private $_productId;
	private $_file;
	private $_isDefault;

	public function getProductId()
	{
		return $this->_productId;
	}

	public function setProductId($id)
	{
		$this->_productId = $id;
	}

	public function getFile()
	{
		return $this->_file;
	}

	public function setFile($file)
	{
		$this->_file = $file;
	}

	public function getIsDefault()
	{
		return $this->_isDefault;
	}

	public function setIsDefault($isDefault)
	{
		$this->_isDefault = (int)$isDefault;
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
			$this->getDbTable()->unsetDefaultPhoto($this->getProductId());
		}
		return parent::save($data);
	}
}
