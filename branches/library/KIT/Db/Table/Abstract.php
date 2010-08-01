<?php
/**
 * Database table
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Core
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id$
 */

abstract class KIT_Db_Table_Abstract extends Zend_Db_Table_Abstract
{
	protected $_viewSelect;
	protected static $_cachedTables = array();
	protected static $_cachedFields;
	protected static $_filterOperations = array('eq' => ' = ?',
												'ne' => ' <> ?',
												'lt' => ' < ?',
												'le' => ' <= ?',
												'gt' => ' > ?',
												'ge' => ' >= ?',
												'in' => ' IN (?)',
												'ni' => ' NOT IN (?)',
												'bw' => ' LIKE "?%"',
												'bn' => ' NOT LIKE "?%"',
												'ew' => ' LIKE "%?"',
												'en' => ' NOT LIKE "%?"',
												'cn' => ' LIKE "%?%"' ,
												'nc' => ' NOT LIKE "%?%"');

	/**
	 * Get data for view
	 * $viewParams = array('page' 	=> 1,
	 * 					   'limit' 	=> 20,
	 * 					 'sortField => 'id',
	 * 				 'sortDirection => 'asc');
	 *
	 * $filterParams = array('searchField' => 'id',
	 * 						'searchString' => '2'
	 *						'searchOper' => 'gt');
	 *
	 * @param mixed $viewParamse
	 * @param mixed $filterParams
	 * @return mixed|null
	 */
	public function getView($viewParams, $filterParams = null)
	{
		$viewParams = (array)$viewParams;
		$filterParams = (array)$filterParams;

		extract($viewParams);
//		extract($filterParams);

		!empty($page) || $page = 1;
		!empty($rows) || $rows = 20;
		!empty($sortField) || $sortField = $this->getPrimary();
		!empty($sortDirection) || $sortDirection = 'ASC';

		$page = intval($page);
		$rows = intval($rows);

		$db = $this->getAdapter();
		$select = $this->getViewSelect();
		!empty($select) || $select = $this->select();

		//$select->setIntegrityCheck(false);
		$select->limitPage($page, $rows)->order(array($sortField . ' ' . $sortDirection));

		if (!empty($filterParams['rules'])) {
			$filterData = array();
			foreach ($filterParams['rules'] as $rule) {
				if (!empty($rule['field'])
					&& isset(self::$_filterOperations[$rule['op']])
				) {
					$filterData[] = str_replace(
										'?',
										htmlspecialchars(strip_tags($rule['data'])),
										$rule['field']. self::$_filterOperations[$rule['op']]
									);
				}
			}
			if (!empty($filterData)) {
				if (strtoupper($filterParams['groupOp']) == 'AND') {
					$select->where('(' . implode(' AND ', $filterData) . ')');
				} else {
					$select->where('(' . implode(' OR ', $filterData) . ')');
				}
			}
		}

		$sql = str_replace('SELECT ', 'SELECT SQL_CALC_FOUND_ROWS ', $select->assemble());

		$response = new stdClass();
		$response->page = $page;
		$response->rows = $db->fetchAll($sql);
		$response->records = (int)$db->fetchOne('SELECT FOUND_ROWS()');
		$response->total = ($response->records > 0 ? ceil($response->records/$rows) : 0);

		return $response;
	}

	/**
	 * Set Select sql for view
	 *
	 * @param string $select
	 */
	public function setViewSelect($select)
	{
		$this->_viewSelect = $select;
	}

	/**
	 * Return Select for view
	 * @return Zend_Db_Select
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

		if ( class_exists($tableName, true) ) {
			self::$_cachedTables[$tableName] = new $tableName();
			return self::$_cachedTables[$tableName];
		}

		throw new Zend_Exception('Table not found: ' . $tableName);
		return false;
	}

	/**
	 * Get table name
	 *
	 * @return string
	 */
	public function getTableName()
	{
		return $this->_name;
	}

	/**
	 * Get table primary key
	 *
	 * @return string
	 */
	public function getPrimary()
	{
		if (is_array($this->_primary)) {
			return current($this->_primary);
		} else {
			return $this->_primary;
		}
	}

	/**
	 * Get table fields
	 *
	 * @param boolean $withAliases
	 * @return mixed
	 */
	public function getFields($withAliases = true)
	{
		$this->_setupCachedFields();
		if ($withAliases) {
			return self::$_cachedFields[$this->_name];
		}
		return array_values(self::$_cachedFields[$this->_name]);
	}

	protected function _setupCachedFields()
	{
		if (empty(self::$_cachedFields[$this->_name])) {
			$this->_setupMetadata();
			foreach (array_keys($this->_metadata) as $field) {
				self::$_cachedFields[$this->_name][self::fieldToAlias($field)] = $field;
			}
		}
		return $this;
	}

	/**
	 * Get table field by alias
	 *
	 * @param string $alias
	 * @return string|null
	 */
	public function getFieldByAlias($alias)
	{
		$alias = strtolower($alias);
		$this->_setupCachedFields();
		if (isset(self::$_cachedFields[$this->_name][$alias])) {
			return self::$_cachedFields[$this->_name][$alias];
		}
		return null;
	}

	/**
	 * Convert database fields to aliases
	 *
	 * @param mixed $fields
	 * @return mixed
	 */
	public static function dbFieldsToAlias($fields)
	{
		$fields = (array)$fields;

		$result = array();
		foreach($fields as $fieldName => $fieldValue) {
			$result[self::fieldToAlias($fieldName)] = $fieldValue;
		}

		return $result;
	}

	/**
	 * Convert database field to alias
	 *
	 * @param string $field
	 * @return string
	 */
	public static function fieldToAlias($field)
	{
		if (empty($field)) {
			return false;
		}

		list($table, $alias) = @explode('_', $field);
		!empty($alias) || $alias = $table;

		return strtolower($alias);
	}

	/**
	 * Remove record from database by primary key
	 *
	 * @param int $id
	 * @return boolean
	 */
	public function removeById($id)
	{
		if (empty($id)) {
			return false;
		}

		$where = $this->getAdapter()->quoteInto($this->getPrimary() . ' = ?', intval($id));
		return $this->delete($where);
	}

	/**
	 * Get custom table fields by contitions
	 *
	 * @param array $fields
	 * @param array $where
	 * @param boolean $pairs
	 * @return Zend_Db_Table_Rowset_Abstract|array
	 */
	public function getSelectedFields($fields = null, $where = array(), $pairs = false)
	{
		if (empty($fields)) {
			$fields = null;
		} else {
			$fields = (array)$fields;
		}
		$where = (array)$where;

		$select = $this->select();
		if (!empty($fields)) {
			$select->from($this->getTableName(), $fields);
		}

		if (!empty($where)) {
			$this->_where($select, $where);
		}

		if ($pairs) {
			return $this->getAdapter()->fetchPairs($select);
		} else {
			return $this->fetchAll($select);
		}
	}

	public function findByFields($fields)
	{
		$firelds = (array)$fields;

		$select = $this->select()->setIntegrityCheck(false);
		$tableFields = $this->getFields(true);
		foreach ($fields as $fieldName => $condition) {
			$fieldName = strtolower($fieldName);
			if (isset($tableFields[$fieldName])) {
				$select->where($tableFields[$fieldName] . ' ' . trim($condition));
			} else if (($index = array_search($fieldName, $tableFields))) {
				$select->where($tableFields[$index] . ' ' . trim($condition));
			}
		}
		return $this->fetchAll($select)->toArray();
	}
}
