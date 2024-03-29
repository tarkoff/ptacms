<?php
/**
 * Class for providing common functionality for
 *
 * @package    Tools
 * @copyright  Copyright (c) 2010 Taras Pavuk (tpavuk@gmail.com)
 * @license    BSD License
 */

require_once 'Zend/Config/Xml.php';
require_once 'Zend/Db.php';

abstract class Geo_Abstract
{
	/**
	 * Database adapter
	 *
	 * @var Zend_Db_Adapter_Abstract
	 */
	protected $_db;
	

	/**
	 * Database and other settings
	 *
	 * @var Zend_Config_Xml
	 */
	protected $_config;
	protected $_configFile = 'config.xml';

	public function init()
	{
		$this->_initConfig();
		$this->_initDb();
	}

	/**
	 * Connect to dtabase
	 *
	 * @return boolean
	 */
	protected function _initDb()
	{
		$this->_db = Zend_Db::factory($this->_config->database);
		is_object($this->_db) || trigger_error('Database connection error.', E_USER_ERROR);
		$this->_db->query('SET NAMES UTF8');
		return true;
	}

	public function disableKeys()
	{
		if (empty($this->_config->tables)) {
			return false;
		}

		$this->_db->beginTransaction();
		foreach ($this->_config->tables as $table) {
			if (!empty($table->name)) {
				$this->_db->query('ALTER TABLE ' . $table->name . ' DISABLE KEYS');
			}
		}
		return $this->_db->commit();
	}

	public function enableKeys()
	{
		if (empty($this->_config->tables)) {
			return false;
		}

		$this->_db->beginTransaction();
		foreach ($this->_config->tables as $table) {
			if (!empty($table->name)) {
				$this->_db->query('ALTER TABLE ' . $table->name . ' ENABLE KEYS');
			}
		}
		return $this->_db->commit();
	}

	/**
	 * Set config file
	 *
	 * @param string $file
	 * @return void
	 */
	public function setConfigFile($file = '')
	{
		if (file_exists($file)) {
			$this->_configFile = $file;
		}
	}

	/**
	 * Parse config xml file
	 *
	 * @return boolean
	 */
	protected function _initConfig()
	{
		$this->_config = new Zend_Config_Xml($this->_configFile);
		!empty($this->_config) || trigger_error('Config file not found.', E_USER_ERROR);
		return $this->_config;
	}

	/**
	 * Clear database tables
	 *
	 * @return boolean
	 */
	public function clearTables()
	{
		if (empty($this->_config->tables)) {
			return false;
		}

		$this->_db->beginTransaction();
		foreach ($this->_config->tables as $table) {
			if (!empty($table->name) && !empty($table->clear)) {
				$this->_db->query('truncate table ' . $table->name);
				$this->alert('Table ' . $table->name . ' truncated');
			}
		}
		return $this->_db->commit();
	}

	public function alert($message)
	{
		echo date(DATE_RFC822) . ' : ' . $message . "\n";
	}

}
