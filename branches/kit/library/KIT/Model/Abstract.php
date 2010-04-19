<?php
/**
 * Model
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
abstract class KIT_Model_Abstract
{
	/**
	 * @var Zend_Db_Table_Abstract
	 */
	protected $_dbTable;

	/**
	 * @var int
	 */
	protected $_id;

	protected static $_cachedModels = array();

	/**
	 * Constructor
	 *
	 * @param  array|null $options
	 * @return void
	 */
	public function __construct(array $options = null)
	{
		if (is_array($options)) {
			$this->setOptions($options);
		}
		$this->init();
	}

	public function init()
	{
		
	}

	/**
	 * Get cached model object
	 *
	 * @param string $className
	 * @param iny $id
	 * @return KIT_Model_Abstract
	 */
	public static function get($className, $id = null)
	{
		$id = (int)$id;
		if (isset(self::$_cachedModels[$className][$id])) {
			return clone self::$_cachedModels[$className][$id];
		}

		if (class_exists($className, true)) {
			self::$_cachedModels[$className][$id] = new $className();
			self::$_cachedModels[$className][$id]->loadById($id);
			return clone self::$_cachedModels[$className][$id];
		}

		throw new Zend_Exception('Wrong model class provided: ' . $className);
	}

	/**
	 * Overloading: allow property access
	 *
	 * @param  string $name
	 * @param  mixed $value
	 * @return void
	 */
	public function __set($name, $value)
	{
		$method = 'set' . $name;
		if (!method_exists($this, $method)) {
			throw Exception('Invalid property specified');
		}
		$this->$method($value);
	}

	/**
	 * Overloading: allow property access
	 *
	 * @param  string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		$method = 'get' . $name;
		if (!method_exists($this, $method)) {
			throw Zend_Exception('Invalid property specified');
		}
		return $this->$method();
	}

	/**
	 * Specify Zend_Db_Table instance to use for data operations
	 *
	 * @param  Zend_Db_Table_Abstract $dbTable
	 * @return Default_Model_GuestbookMapper
	 */
	public function setDbTable($dbTable)
	{
		if ( is_string($dbTable) && class_exists($dbTable) ) {
			$dbTable = KIT_Db_Table_Abstract::get($dbTable);
		}
		if ( !$dbTable instanceof Zend_Db_Table_Abstract ) {
			throw new Zend_Exception('Invalid table data gateway provided');
		}
		$this->_dbTable = $dbTable;
		return $this;
	}

	/**
	 * Get registered Zend_Db_Table instance
	 *
	 * Lazy loads KIT_Model if no instance registered
	 *
	 * @return KIT_Db_Table_Abstract
	 */
	public function getDbTable()
	{
		if (empty($this->_dbTable)) {
			$modelClass = array_shift(explode('_', get_class($this)));
			$dbTableClass  = 'KIT_' . array_shift($modelClass) . '_DbTable_' . implode('_', $modelClass);
			if ( class_exists($dbTableClass) ) {
				$this->_dbTable = KIT_Db_Table_Abstract::get($dbTableClass);
			}
		}
		return $this->_dbTable;
	}

	/**
	 * Set entry id
	 *
	 * @param  int $id
	 * @return KIT_Model
	 */
	public function setId($id)
	{
		$this->_id = (int) $id;
		return $this;
	}

	/**
	 * Retrieve entry id
	 *
	 * @return null|int
	 */
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * Set object state
	 *
	 * @param mixed $options
	 * @param boolean $isDbFields
	 * @return KIT_Model_Abstract
	 */
	public function setOptions($options, $isDbFields = false)
	{
		if ($isDbFields) {
			$options = KIT_Db_Table_Abstract::dbFieldsToAlias($options);
		}

		foreach ($options as $key => $value) {
			$method = 'set' . ucfirst($key);
			if (method_exists($this, $method)) {
				$this->$method($value);
			}
		}
		return $this;
	}

	/**
	 * Load object properties from database
	 *
	 * @param int $id
	 * @return KIT_Model_Abstract
	 */
	public function loadById($id)
	{
		$id = (int)$id;
		if (!empty($id) && ($this->_id != $id)) {
			$table = $this->getDbTable();
			$data = $table->fetchRow($table->getPrimary() . ' = ' . $id);
			if ($data instanceof Zend_Db_Table_Row_Abstract) {
				$data = KIT_Db_Table_Abstract::dbFieldsToAlias($data->toArray());
				$this->setOptions($data);
			}
		}
		return $this;
	}

	/**
	 * Save data to database
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function save($data = null)
	{
		$data = (array)$data;
		$table = $this->getDbTable();
		$id = $this->getId();

		if (empty($data)) {
			foreach ($table->getFields() as $fieldAlias => $fieldDbName) {
				$method = 'get' . ucfirst($fieldAlias);
				if (method_exists($this, $method)) {
					$data[$fieldDbName] = $this->$method();
				}
			}
		}

		if (empty($id)) {
			$saved = $table->insert($data);
			$this->setId(
				$table->getAdapter()->lastInsertId(
					$table->getTableName(),
					$table->getPrimary()
				)
			);
			return $saved;
		} else {
			//$data[$table->getPrimary()] = $id;
			$table->update($data, array($table->getPrimary() . ' = ?' => $id));
			return true;
		}
	}

	public function remove()
	{
		$id = $this->getId();
		if (empty($id)) {
			return false;
		}
		return $this->getDbTable()->removeById(intval($id));
	}
}
