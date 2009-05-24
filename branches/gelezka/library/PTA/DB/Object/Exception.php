<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Exception.php 21 2009-03-11 19:53:54Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_DB_Object_Exception extends PTA_Exception 
{
	public function __construct($className, $objectId)
	{
		echo "Object {$className} with id:{$objectId} not found!" . get_call_stack();
	}
}