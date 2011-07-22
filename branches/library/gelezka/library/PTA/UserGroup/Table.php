<?php
/**
 * User Group Table
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
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
