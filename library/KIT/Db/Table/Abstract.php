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
		extract($filterParams);

		!empty($page) || $page = 1;
		!empty($limit) || $limit = 20;
		!empty($sortField) || $sortField = $this->getPrimary();
		!empty($sortDirection) || $sortDirection = 'asc';

		$page = intval($page);
		$limit = intval($limit);

		$db = $this->getAdapter();
		$select = $this->getViewSelect();
		!empty($select) || $select = $this->select();

		//$select->setIntegrityCheck(false);
		$select->limitPage($page, $limit)
			   ->order(array($sortField . ' ' . $sortDirection));

		if (!empty($searchField) && !empty($searchString)) {
			if (isset($searchOper) && isset(self::$_filterOperations[$searchOper])) {
				$select->where($searchField
							   . str_replace('?',
											 $searchString,
											 self::$_filterOperations[$searchOper]));
			}
		}
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
		$this->_setupMetadata();
		$this->_setupCachedFields();

		if ($withAliases) {
			return self::$_cachedFields[$this->_name];
		} else {
			return array_values(self::$_cachedFields[$this->_name]);
		}
	}

	protected function _setupCachedFields()
	{
		if (empty(self::$_cachedFields[$this->_name])) {
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

	public function getSelectedFields($fields = null, $where = array())
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

		@list($whereCond, $params) = $where;
		if (!empty($whereCond)) {
			$select->where($whereCond, $params);
		}

		return $this->fetchAll($select)->toArray();
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
