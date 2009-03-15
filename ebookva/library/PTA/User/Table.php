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

class PTA_User_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'USERS';
	protected $_primary = 'USERS_ID';
	protected $_sequence = true;

	public function getUserByHash($hash)
	{
		$statTable = PTA_DB_Table::get('User_Stat');
		$dbUser = $statTable->getUserByHash($hash);

		if (!empty($dbUser)) {
			$user = $this->getTableObject('currentUser');
			$user->loadById($dbUser[$statTable->getFieldByAlias('userId')]);
			$user->setSessionHash($dbUser[$statTable->getFieldByAlias('sessionHash')]);
			return $user;
		}

		return null;
	}

}
