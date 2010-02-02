<?php
abstract class KIT_Db_Table_Abstract extends Zend_Db_Table_Abstract
{
	protected $_viewSelect;
	protected static $_cachedTables = array();
	
	/**
	 * Get data for view
	 *
	 * @param int $page
	 * @param int $limit
	 * @param string $sortField
	 * @param string $sortDirection
	 * @return array|null
	 */
	public function getView($page, $limit = 20, $sortField = null, $sortDirection = 'asc')
	{
		$page = intval($page);
		$limit = intval($limit);

		!empty($page) || $page = 1;
		!empty($limit) || $limit = 20;
		!empty($sortField) || $sortField = $this->_primary;
		!empty($sortDirection) || $sortDirection = 'asc';

		if (empty($this->_viewSelect)) {
			$select = $this->select();
		} else {
			$select = $this->_viewSelect;
		}

		//$select->setIntegrityCheck(false);
		$select->limitPage($page, $limit)->order(array($sortField . ' ' . $sortDirection));

		$db = $this->getAdapter();
		$sql = str_replace('SELECT ', 'SELECT SQL_CALC_FOUND_ROWS ', $select->assemble());

		$response = new stdClass();
		$response->page = $page;
		$response->rows = $db->fetchAll($sql);
		$response->records = (int)$db->fetchOne('SELECT FOUND_ROWS()');
		$response->total = ($response->records > 0 ? ceil($response->records/$limit) : 0);

		return $response;
	}

	/**
	 * Select sql for view
	 *
	 * @param string $select
	 * @return unknown_type
	 */
	public function setViewSelect($select)
	{
		$this->_viewSelect = $select;
	}
	
	/**
	 * Return Select for view
	 * @return unknown_type
	 */
	public function getViewSelect()
	{
		return $this->_viewSelect;
	}
	
	/**
	 * Get table object instance
	 *
	 * @param string $tableName
	 * @return KIT_Db_Table_Abstract
	 */
	public static function get($tableName)
	{
		if ( !is_string($tableName) ) {
			return false;
		}

		if ( isset(self::$_cachedTables[$tableName]) ) {
			return self::$_cachedTables[$tableName];
		}

		if ( Zend_Loader_Autoloader::autoload($tableName) ) {
			self::$_cachedTables[$tableName] = new $tableName();
			return self::$_cachedTables[$tableName];
		}

		throw new Zend_Exception('Table not found: ' . $tableName);
		return false;
	}
}