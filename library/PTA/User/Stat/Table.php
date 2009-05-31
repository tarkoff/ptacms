<?php
/**
 * User Statistic Table
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_User_Stat_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'USERSTAT';
	protected $_primary = 'USERSTAT_ID';
	protected $_sequence = true;
	protected static $_usersStats = array();

	/**
	 * Get user by cookie hash from /db
	 *
	 * @param string $hash
	 * @return array
	 */
	public function getUserByHash($hash)
	{
		$hashField = $this->getFieldByAlias('sessionHash');
		foreach (self::$_usersStats as $userRow) {
			if ($hash == $userRow->$hashField) {
				return $userRow->toArray();
			}
		}

		$select = $this->select();
		$select->where($this->getAdapter()->quoteInto('USERSTAT_SESSIONHASH = ?', $hash));
		//$select->limit(1);

		if (($userRow = $this->fetchRow($select))) {
			self::$_usersStats[$userRow->{$this->getFieldByAlias('userId')}] = $userRow;
			return $userRow->toArray();
		}
		
		return array();
	}

	/**
	 * save current user session to DB
	 *
	 * @param PTA_User $user
	 * @return boolean
	 */
	public function saveUserSession(PTA_User $user)
	{
		if (isset(self::$_usersStats[$user->getId()])) {
			$row = self::$_usersStats[$user->getId()];
		} else {
			$row = $this->fetchRow(
				$this->select()->where($this->getFieldByAlias('userId') . ' = ?', $user->getId())
			);
		}

		if (empty($row)) {
			$row = $this->fetchNew();
		}

		$row->{$this->getFieldByAlias('userId')} = $user->getId();
		$row->{$this->getFieldByAlias('loginDate')} = new Zend_Db_Expr('CURRENT_TIMESTAMP');
		$row->{$this->getFieldByAlias('lastClickDate')} = new Zend_Db_Expr('CURRENT_TIMESTAMP');
		$row->{$this->getFieldByAlias('sessionHash')} = $user->getSessionHash();

		return $row->save();
	}
}
