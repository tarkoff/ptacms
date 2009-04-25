<?php
/**
 * Short description for file
 *
 * @package Catalog
 * @copyright	008 PTA Studio
 * @license		http://framework.zend.com/license   BSD License
 * @version		$Id: Table.php 13 2009-02-28 14:47:29Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_UserGroup_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'USERGROUPS';
	protected $_primary = 'USERGROUPS_ID';
	protected $_sequence = true;

}
