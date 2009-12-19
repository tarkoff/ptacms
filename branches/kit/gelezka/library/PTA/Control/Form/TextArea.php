<?php
/**
 * Form Field Text Area
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Control_Form_TextArea extends PTA_Control_Form_Field 
{
	public function init()
	{
		parent::init();
		
		$this->setFieldType(PTA_Control_Form_Field::TYPE_TEXTAREA);
	}

}
