<?php
/**
 * User Table
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Table.php 143 2009-08-13 18:09:49Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_User_Guest_Table extends PTA_User_Table
{
	/**
	 * The default table name 
	 */
	protected $_name = 'USERS';
	protected $_primary = 'USERS_ID';

	public function getGuest($asId = false)
	{
		$select = $this->select();
		$select->where($this->getFieldByAlias('login') . ' = "Guest"')->limit(1);

		if ($asId) {
			$select->from($this->getTableName(), $this->getPrimary());
			return $this->getAdapter()->fetchOne($select);
		} else {
			return $this->fetchAll($select)->toArray();
		}
	}

}
