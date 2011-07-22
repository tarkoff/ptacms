<?php
/**
 * User Rights Table
 *
 * @package PTA_Core
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Table.php 143 2009-08-13 18:09:49Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_User_Rights_Table extends PTA_DB_Table 
{
	/**
	 * The default table name 
	 */
	protected $_name = 'USERRIGHTS';
	protected $_primary = 'USERRIGHTS_ID';

	public function getUserRights($userId)
	{
		if (empty($userId)) {
			return array();
		}

		$userIdField = $this->getFieldByAlias('userId');
		$select = $this->select()->from(
			$this->getTableName(), array($userIdField, $this->getFieldByAlias('right'))
		);
		$select->where($userIdField . ' = ?', intval($userId));

		return $this->getAdapter()->fetchPairs($select);
	}
}
