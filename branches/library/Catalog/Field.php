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
 * @version    $Id: Field.php 397 2010-05-04 20:46:34Z TPavuk $
 */

class KIT_Catalog_Field extends KIT_Model_Abstract
{
	private $_alias;
	private $_title;
	private $_fieldType;

	public static function getFieldTypes()
	{
		return array(
			KIT_Form_Element_Abstract::TYPE_TEXT     => 'Text',
			KIT_Form_Element_Abstract::TYPE_SELECT   => 'Select',
			KIT_Form_Element_Abstract::TYPE_MULTISELECT => 'Multi Select',
			KIT_Form_Element_Abstract::TYPE_CHECKBOX => 'Checkbox'
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
