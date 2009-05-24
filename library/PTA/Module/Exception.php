<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2009 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Module_Exception extends PTA_Exception 
{
	public function __construct($className)
	{
		echo "Module {$className} not found!" . $this->getTrace();
	}
}