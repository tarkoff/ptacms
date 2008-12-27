<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id:$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Module_Peer extends PTA_Object 
{
	private $_db = null;
	private $_tableName = null;
	private $_dbFields =array();
	private $_filters = array();
	private $_tables = array();
	
	public function __construct($prefix)
	{
		$this->setPrefix($prefix);
		$this->setDb(PTA_App::getInstance()->getDb());
	}
	
	public function get($objectName)
	{
		$pearName = $objectName . '_Pear';
		$pear = null;
		
		try {
			$pear = new $pearName;
		} catch (Zend_Exception $e) {
			echo $e->getMessage();
		}
		
		return $pear;
	}
	
	public function getDb()
	{
		return $this->_db;
	}
	
	public function setDb($db)	
	{
		$this->_db = $db;
	}

	public function getTableName()
	{
		return $this->_tableName;
	}

	public function setTableName( $tableName)
	{
		$this->_tableName = $tableName;
	}
	
	public function getFileds()
	{
		return $this->_dbFields;
	}
	
	public function getFieldByKey($key)
	{
		return (isset($this->_dbFields[$key]) ? $this->_dbFields[$key] : false);
	}
	
	public function setFields($fields)
	{
		if (is_array($fields)) {
			$this->_dbFields = $fields;
		}
	}
	
	public function setPrimaryKey($primary)
	{
		$this->_dbFields['primaryKey'] = $primary;
	}
	
	public function getPrimaryKey()
	{
		return (isset($this->_dbFields['primaryKey']) ? $this->_dbFields['primaryKey'] : false);
	}
	
	public function addFilter($filter)
	{
		$this->_filters[] = $filter;
	}
	
	public function addTable($table)
	{
		$this->_tables[] = $table;
	}
	
	public function getByFieldkey($key)
	{
		
	}
	
	public function save()
	{
		
	}
}