<?php
/**
 * Abstract Form
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Core
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id: Abstract.php 282 2010-03-05 18:03:44Z TPavuk $
 */
abstract class KIT_Form_Element_Abstract
{
	const TYPE_TEXT = 1;
	const TYPE_TEXTAREA = 2;
	const TYPE_RADIO = 3;
	const TYPE_SUBMIT = 4;
	const TYPE_IMAGE = 5;
	const TYPE_SELECT = 6;
	const TYPE_CHECKBOX = 7;
	const TYPE_FIELDSGROUP = 8;
	const TYPE_PASSWORD = 9;
	const TYPE_FILE = 10;
	const TYPE_HIDDEN = 11;

	
	/**
	 * Get Form element by type
	 *
	 * @param int $type
	 * @param string $alias
	 * @param array $options
	 * @return Zend_Form_Element
	 */
	public static function getElementByType($type, $alias, $options = null)
	{
		$field = null;
		switch ($type) {
			case self::TYPE_TEXT:
				$field = new Zend_Form_Element_Text($alias);
			break;

			case self::TYPE_HIDDEN:
				$field = new Zend_Form_Element_Hidden($alias);
			break;

			case self::TYPE_TEXTAREA:
				$field = new Zend_Form_Element_Textarea($alias);
			break;

			case self::TYPE_CHECKBOX:
				$field = new Zend_Form_Element_Checkbox($alias);
			break;

			case self::TYPE_RADIO:
				$field = new Zend_Form_Element_Radio($alias);
			break;

			case self::TYPE_SUBMIT:
				$field = new Zend_Form_Element_Submit($alias);
			break;

			case self::TYPE_IMAGE:
				$field = Zend_Form_Element_Image($alias);
			break;

			case self::TYPE_SELECT:
				$field = new Zend_Form_Element_Select($alias);
			break;
		}
		return self::_setOptions($field, $options);
	}

	private static function _setOptions($field, $options)
	{
		$options = (array)$options;
		foreach ($options as $option => $value) {
			$method = 'set' . $option;
			if (method_exists($field, $method)) {
				//call_user_func_array(array($field, $method), $value);
				$field->$method($value);
			}
		}

		return $field;
	}
}
