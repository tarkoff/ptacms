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

class PTA_User_Stat_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'USERSTAT';
	protected $_primary = 'USERSTAT_ID';
	protected $_sequence = true;

	public function getUserByHash($hash)
	{
		$select = $this->select();
		$select->where($this->getAdapter()->quoteInto('USERSTAT_SESSIONHASH = ?', $hash));
		$select->limit(1);

		return current($this->fetchAll($select)->toArray());
	}

	public function saveUserSession(PTA_User $user)
	{
		$select = $this->select()->where($this->getFieldByAlias('userId') . ' = ?', $user->getId());
		$row = $this->fetchRow($select);
		
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
