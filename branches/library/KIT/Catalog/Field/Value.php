<?php
/**
 * Catalog Field Value Model
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

class KIT_Catalog_Field_Value extends KIT_Model_Abstract
{
	private $_fieldId;
	private $_value;

	public function getFieldId()
	{
		return $this->_fieldId;
	}

	public function setFieldId($id)
	{
		$this->_fieldId = $id;
	}

	public function getValue()
	{
		return $this->_value;
	}

	public function setValue($value)
	{
		$this->_value = $value;
	}
}
