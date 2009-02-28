<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Menu_Peer
{
	private $_db = null;
	private $_tableName = null;
	
	public function __construct()
	{
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
	
}