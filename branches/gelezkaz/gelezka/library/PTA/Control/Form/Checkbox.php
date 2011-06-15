<?php
/**
 * Form Field Checkbox
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Control_Form_Checkbox extends PTA_Control_Form_Field 
{
	/**
	 * 
	 */

	public function init()
	{
		parent::init();

		$this->setFieldType(PTA_Control_Form_Field::TYPE_CHECKBOX);
	}

	public function setChecked($cheked)
	{
		$this->setVar('checked', (int)$cheked);
	}

	public function getChecked()
	{
		return $this->getVar('checked');
	}
	
	public function setValue($value)
	{
		if (!empty($value)) {
			$this->setChecked(1);
			parent::setValue($value);
		}
	}

}
