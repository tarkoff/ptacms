<?php
/**
 * Catalog Product Field Value Model
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
 * @version    $Id: Value.php 397 2010-05-04 20:46:34Z TPavuk $
 */

class KIT_Catalog_Product_Field_Value extends KIT_Model_Abstract
{
	private $_productId;
	private $_fieldId;
	private $_valueId;

	public function getProductId()
	{
		return $this->_productId;
	}

	public function setProductId($id)
	{
		$this->_productId = $id;
	}

	public function getFieldId()
	{
		return $this->_fieldId;
	}

	public function setFieldId($id)
	{
		$this->_fieldId = $id;
	}

	public function getValueId()
	{
		return $this->_valueId;
	}

	public function setValueId($id)
	{
		$this->_valueId = $id;
	}
}
