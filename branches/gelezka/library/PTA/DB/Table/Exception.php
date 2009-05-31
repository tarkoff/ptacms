<?php
/**
 * Database Table Exception
 *
 * @package PTA_Core
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_DB_Table_Exception extends PTA_Exception
{
	public function __construct($className)
	{
		echo "Table {$className} not found!" . get_call_stack();
	}
}