<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

abstract class PTA_DB 
{
	/**
 	 * fetchAssoc - Fetching a Result Set as an Associative Array
	 *
	 * @method fetchAssoc
	 * @access public
	 * @param string $sql
	 * @param array $params
	 * @return array
	*/	
	public function fetchAssoc($sql, $params = null)
	{
		$db = PTA_App::getInstance()->getDb();
		
		return $db->fetchAssoc($sql, $params);
	}

	/**
 	 * fetchCol - Fetching a Single Column from a Result Set
	 *
	 * @method fetchCol
	 * @access public
	 * @param string $sql
	 * @param array $params
	 * @return array
	*/	
	public function fetchCol($sql, $params = null)
	{
		$db = PTA_App::getInstance()->getDb();
		
		return $db->fetchCol($sql, $params);
	}

	/**
 	 * fetchPairs - Fetching Key-Value Pairs from a Result Set
	 *
	 * @method fetchPairs
	 * @access public
	 * @param string $sql
	 * @param array $params
	 * @return array
	*/	
	public function fetchPairs($sql, $params = null)
	{
		$db = PTA_App::getInstance()->getDb();
		
		return $db->fetchPairs($sql, $params);
	}

	/**
 	 * fetchRow - Fetching a Single Row from a Result Set
	 *
	 * @method fetchRow
	 * @access public
	 * @param string $sql
	 * @param array $params
	 * @return array
	*/	
	public function fetchRow($sql, $params = null)
	{
		$db = PTA_App::getInstance()->getDb();
		
		return $db->fetchRow($sql, $params);
	}

	/**
 	 * fetchOne - Fetching a Single Scalar from a Result Set
	 *
	 * @method fetchOne
	 * @access public
	 * @param string $sql
	 * @param array $params
	 * @return array
	*/	
	public function fetchOne($sql, $params = null)
	{
		$db = PTA_App::getInstance()->getDb();
		
		return $db->fetchOne($sql, $params);
	}

	/**
 	 * insert - Inserting Data
	 *
	 * @method insert
	 * @access public
	 * @param string $sql
	 * @param array $params
	 * @return boolean
	*/	
	public function insert($sql, $params)
	{
		$db = PTA_App::getInstance()->getDb();
		
		return $db->insert($sql, $params);
	}

	/**
 	 * update - Updating Data
	 *
	 * @method update
	 * @access public
	 * @param string $sql
	 * @param array $params
	 * @param string $where
	 * @return boolean
	*/	
	public function update($sql, $params, $where)
	{
		$db = PTA_App::getInstance()->getDb();
		
		return $db->insert($sql, $params, $where);
	}

	/**
 	 * delete - Deleting Data
	 *
	 * @method delete
	 * @access public
	 * @param string $table
	 * @param mixed $where
	 * @return boolean
	*/	
	public function delete($table, $where)
	{
		$db = PTA_App::getInstance()->getDb();
		
		return $db->delete($table, $where);
	}

	/**
 	 * quote - returns the value with special characters escaped
	 *
	 * @method quote
	 * @access public
	 * @param string $value
	 * @param string $type
	 * @return mixed
	*/	
	public function quote($value, $type = '')
	{
		$db = PTA_App::getInstance()->getDb();
		
		return $db->quote($value, $type);
	}
}