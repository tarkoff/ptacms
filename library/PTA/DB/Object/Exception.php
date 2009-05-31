<?php
/**
 * Database Object Exception
 *
 * @package PTA_Core
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_DB_Object_Exception extends PTA_Exception 
{
	public function __construct($className, $objectId)
	{
		echo "Object {$className} with id:{$objectId} not found!" . get_call_stack();
	}
}