<?php
/**
 * Catalog Field Model
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

class Catalog_Model_Field extends KIT_Model_Abstract
{
	private $_alias;
	private $_title;
	private $_fieldType;

	public static function getFieldTypes()
	{
		return array(
			1 => 'Text',
			6 => 'Select',
			7 => 'Checkbox'
		);
	}

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

	public function getFieldType()
	{
		return $this->_fieldType;
	}

	public function setFieldType($fieldType)
	{
		$this->_fieldType = $fieldType;
	}
}