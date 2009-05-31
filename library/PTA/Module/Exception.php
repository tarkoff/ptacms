<?php
/**
 * App Module Exception
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
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