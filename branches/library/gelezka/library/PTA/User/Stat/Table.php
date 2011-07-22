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
	public function getUserStatByHash($hash)
	{
		$hashField = $this->getFieldByAlias('sessionHash');
		foreach (self::$_usersStats as $userRow) {
			if ($hash == $userRow->$hashField) {
				return $userRow->toArray();
			}
		}

		$select = $this->select()->where('USERSTAT_SESSIONHASH = ?', $hash);
		$select->limit(1);

		if (($userRow = $this->fetchRow($select))) {
			self::$_usersStats[$userRow->{$this->getFieldByAlias('userId')}] = $userRow;
			return $userRow->toArray();
		}
		
		return array();
	}

	public function getUserStatByUserId($userId)
	{
		if (empty($userId)) {
			return array();
		}

		$userId = (int)$userId;

		if (isset(self::$_usersStats[$userId])) {
			return self::$_usersStats[$userId]->toArray();
		}

		$row = $this->fetchRow(
			$this->select()->where(
				$this->getFieldByAlias('userId') . ' = ' . $userId
			)
		);

		if (!empty($row)) {
			self::$_usersStats[$userId] = $row;
			return $row->toArray();
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
		$userStat = $this->getUserStatByUserId($user->getId());

		$row[$this->getFieldByAlias('loginDate')] = new Zend_Db_Expr('CURRENT_TIMESTAMP');
		$row[$this->getFieldByAlias('lastClickDate')] = new Zend_Db_Expr('CURRENT_TIMESTAMP');
		$row[$this->getFieldByAlias('sessionHash')] = $user->getSessionHash();

		$userIdField = $this->getFieldByAlias('userId');
		if (empty($userStat)) {
			$row[$userIdField] = $user->getId();
			return $this->getAdapter()->insert($this->getTableName(), $row);
		} else {
			$primaryIdField = $this->getPrimary();
			return $this->getAdapter()->update(
				$this->getTableName(),
				$row,
				$primaryIdField . ' = ' . (int)$userStat[$primaryIdField]
			);
		}
	}
}
