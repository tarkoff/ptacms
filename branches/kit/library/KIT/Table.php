<?php
abstract class KIT_Table extends Zend_Db_Table_Abstract
{
	protected $_viewSelect;
	
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

		!empty($limit) || $limit = 20;
		!empty($sortField) || $sortField = $this->_primary;
		!empty($sortDirection) || $sortDirection = 'asc';

		if (empty($this->_viewSelect)) {
			$select = $this->select();
		} else {
			$select = $this->_viewSelect;
		}

		$select->limitPage($page, $limit)->order(array($sortField . ' ' . $sortDirection));

		$db = $this->getAdapter();
		$sql = str_replace('SELECT', 'SELECT SQL_CALC_FOUND_ROWS', $select->assemble());

		$response = new stdClass();
		$response->page = $page;
		$response->rows = $this->fetchAll($sql);
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
}