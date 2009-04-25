<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Text.php 20 2009-03-10 21:27:25Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Control_Form_Password extends PTA_Control_Form_Field 
{
	public function init()
	{
		parent::init();

		$this->setFieldType(PTA_Control_Form_Field::TYPE_PASSWORD);
	}

}
