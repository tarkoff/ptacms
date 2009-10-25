<?php
require_once 'Zend/Config/Xml.php';
require_once 'Zend/Db.php';

class MixParser
{
	/**
	 * 
	 * @var Zend_Db_Adapter_Abstract
	 */
	protected $_db;
	protected $_url;
	protected $_xml;
	protected $_zipEnabled = false;
	protected $_loadMode = self::LOAD_LOCAL;
	protected $_config = array();

	protected static $_configFile = 'config.xml';

	const LOAD_LOCAL = 0;
	const LOAD_REMOTE = 1;

	public function __construct($url)
	{
		$this->_url = $url;
		if (strpos($url, 'http://') !== false) {
			$this->setLoadMode(self::LOAD_REMOTE);
		}
		
	}

	protected function _initDb()
	{
		$this->_db = Zend_Db::factory($this->_config->database);
		is_object($this->_db) || trigger_error('Database connection error.', E_USER_ERROR);
	}

	public function setLoadMode($mode = self::LOAD_LOCAL)
	{
		$this->_loadMode = ((self::LOAD_LOCAL == intval($mode)) ? self::LOAD_LOCAL : self::LOAD_REMOTE);
	}

	public function setCongigFile($file = '')
	{
		if (file_exists($file)) {
			self::$_configFile = $file;
		}
	}
	
	protected function _parseConfig()
	{
		$this->_config = new Zend_Config_Xml(self::$_configFile);
		!empty($this->_config) || trigger_error('Config file not found.', E_USER_ERROR);
	}
	
	public function parse()
	{
		$this->_parseConfig();
		$this->_initDb();

		$this->_parseXml();
//		$this->_parseXml($this->_getXml());
	}
	
	protected function _getXml($url = null)
	{
		!empty($url) || $url = $this->_url;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//		curl_setopt($ch, CURLOPT_TIMEOUT, 4);
		curl_setopt($ch, CURLOPT_HEADER, 0);

		$data = curl_exec($ch);
		if (curl_errno($ch)) {
			trigger_error(curl_error($ch));
			return false;
		} else {
			curl_close($ch);
		}

		return $data;
	}

	protected function _parseXml($xmlFile = null)
	{
		!empty($xmlFile) || $xmlFile = $this->_url;

		if (self::LOAD_LOCAL == $this->_loadMode && file_exists($xmlFile)) {
			$xml = simplexml_load_file($xmlFile);
		} elseif (self::LOAD_REMOTE == $this->_loadMode) {
			$xml = simplexml_load_string($this->_getXml($xmlFile));
		} else {
			trigger_error('Failed to open ' . $xmlFile, E_USER_ERROR);
		}

		if (empty($xml)) {
			trigger_error('Xml file is empty.', E_USER_ERROR);
		}

		$this->_parseAdvertizers($xml->advertizers);
		unset($xml->advertizers);
		$this->_parseRegionsDelivery($xml->regions_delivery);
		unset($xml->regions_delivery);
		$this->_parseAdvRegionDelivery($xml->adv_region_delivery);
		unset($xml->adv_region_delivery);
		$this->_parseRegionsGeotarget($xml->regions_geotarget);
		unset($xml->regions_geotarget);
		$this->_parseAdvRegionGeotarget($xml->adv_region_geotarget);
		unset($xml->adv_region_geotarget);
		$this->_parseCurrencies($xml->currencies);
		unset($xml->currencies);

		$this->_parseBrands($xml->brands);
		unset($xml->brands);

		$this->_parseCategories($xml->categories);
		unset($xml->categories);
		$this->_parseOffers($xml->offers);
		unset($xml);

		return true;
	}
	
	protected function _parseAdvertizers($advs)
	{
		$table = $this->_config->tables->advertizers;
		$values = array();
		$bulkPos = 1;
		foreach ($advs->children() as $adv) {
			$values[] = array((int)$adv['id'], (string)$adv);
			if ($bulkPos % 200 == 0) {
				$this->_insert(
					$values,
					$table->table,
					array($table->fields->id, $table->fields->title)
				);
				$values = array();
			}
			$bulkPos++;
		}
		return $this->_insert(
			$values,
			$table->table,
			array($table->fields->id, $table->fields->title)
		);
	}

	protected function _parseRegionsDelivery($rd)
	{
		$table = $this->_config->tables->regions_delivery;
		$values = array();
		$bulkPos = 1;
		foreach ($rd->children() as $child) {
			$values[] = array((int)$child['id'], (string)$child);
			if ($bulkPos % 200 == 0) {
				$this->_insert(
					$values,
					$table->table,
					array($table->fields->id, $table->fields->title)
				);
				$values = array();
			}
			$bulkPos++;
		}
		return $this->_insert(
			$values,
			$table->table,
			array($table->fields->id, $table->fields->title)
		);
	}

	protected function _parseAdvRegionDelivery($ard)
	{
		$table = $this->_config->tables->adv_region_delivery;
		$values = array();
		$bulkPos = 1;
		foreach ($ard->children() as $adv) {
			foreach ($adv->children() as $region) {
				foreach ($region->children() as $rd) {
					$values[] = array((int)$adv['id'], (int)$rd['id']);
					if ($bulkPos % 200 == 0) {
						$this->_insert(
							$values,
							$table->table,
							array($table->fields->advid, $table->fields->rdid)
						);
						$values = array();
					}
					$bulkPos++;
				}
			}
		}
		return $this->_insert(
			$values,
			$table->table,
			array($table->fields->advid, $table->fields->rdid)
		);
	}

	protected function _parseRegionsGeotarget($rgt)
	{
		$table = $this->_config->tables->regions_geotarget;
		$values = array();
		$bulkPos = 1;
		foreach ($rgt->children() as $child) {
			$values[] = array((int)$child['id'], (int)$child['pid'], (string)$child);
			if ($bulkPos % 200 == 0) {
				$this->_insert(
					$values,
					$table->table,
					array($table->fields->id, $table->fields->pid, $table->fields->title)
				);
				$values = array();
			}
			$bulkPos++;
		}
		return $this->_insert(
			$values,
			$table->table,
			array($table->fields->id, $table->fields->pid, $table->fields->title)
		);
	}

	protected function _parseAdvRegionGeotarget($argt)
	{
		$table = $this->_config->tables->adv_region_geotarget;
		$values = array();
		$bulkPos = 1;
		foreach ($argt->children() as $child) {
			$values[] = array((int)$child['advid'], (int)$child['rgtid']);
			if ($bulkPos % 200 == 0) {
				$this->_insert(
					$values,
					$table->table,
					array($table->fields->advid, $table->fields->rgtid)
				);
				$values = array();
			}
			$bulkPos++;
		}
		return $this->_insert(
			$values,
			$table->table,
			array($table->fields->advid, $table->fields->rgtid)
		);
	}

	protected function _parseCurrencies($currencies)
	{
		$table = $this->_config->tables->currencies;
		$values = array();
		$bulkPos = 1;
		foreach ($currencies->children() as $child) {
			$values[] = array((int)$child['id'], (float)$child['rate']);
			if ($bulkPos % 200 == 0) {
				$this->_insert(
					$values,
					$table->table,
					array($table->fields->id, $table->fields->rate)
				);
				$values = array();
			}
			$bulkPos++;
		}
		return $this->_insert(
			$values,
			$table->table,
			array($table->fields->id, $table->fields->rate)
		);
	}

	protected function _parseBrands($brands)
	{
		$table = $this->_config->tables->brands;
		$values = array();
		$bulkPos = 1;
		foreach ($brands->children() as $child) {
			$values[] = array((int)$child['id'], (string)$child);
			if ($bulkPos % 200 == 0) {
				$this->_insert(
					$values,
					$table->table,
					array($table->fields->id, $table->fields->title)
				);
				$values = array();
			}
			$bulkPos++;
		}
		return $this->_insert(
			$values,
			$table->table,
			array($table->fields->id, $table->fields->title)
		);
	}

	protected function _parseCategories($categories)
	{
		$table = $this->_config->tables->categories;
		$values = array();
		$bulkPos = 1;
		foreach ($categories->children() as $child) {
			$values[] = array((int)$child['id'], (int)$child['parentId'], (string)$child);
			if ($bulkPos % 200 == 0) {
				$this->_insert(
					$values,
					$table->table,
					array($table->fields->id, $table->fields->parentId, $table->fields->title)
				);
				$values = array();
			}
			$bulkPos++;
		}
		return $this->_insert(
			$values,
			$table->table,
			array($table->fields->id, $table->fields->parentId, $table->fields->title)
		);
	}

	protected function _parseOffers($offers)
	{
		$table = $this->_config->tables->offers;
		$values = array();
		$bulkPos = 1;
		foreach ($offers->children() as $child) {
			$values[] = array(
				(int)$child['id'],
				(int)$child['brandid'],
				(int)$child['advid'],
				(int)$child['cat'],
				(string)$child['src'],
				(int)$child['w'],
				(int)$child['h'],
				(string)$child->type,
				(string)$child->name,
				(string)$child->url,
				(string)$child->currencyId,
				(string)$child->desc,
				(float)str_replace('.', ',', (string)$child->price)
			);
			if ($bulkPos % 200 == 0) {
				$this->_insert(
					$values,
					$table->table,
					array(
						$table->fields->id,
						$table->fields->brandid,
						$table->fields->advid,
						$table->fields->cat,
						$table->fields->src,
						$table->fields->w,
						$table->fields->h,
						$table->fields->type,
						$table->fields->name,
						$table->fields->url,
						$table->fields->currencyId,
						$table->fields->desc,
						$table->fields->price
					)
				);
				$values = array();
			}
			$bulkPos++;
		}
//var_dump($values);
//exit(0);
		return $this->_insert(
			$values,
			$table->table,
			array(
				$table->fields->id,
				$table->fields->brandid,
				$table->fields->advid,
				$table->fields->cat,
				$table->fields->src,
				$table->fields->w,
				$table->fields->h,
				$table->fields->type,
				$table->fields->name,
				$table->fields->url,
				$table->fields->currencyId,
				$table->fields->desc,
				$table->fields->price
			)
		);
	}

	protected function _insert($data, $table, $sqlFields)
	{
		$sql = 'insert ignore into ' . $table 
			. ' (' . implode(', ', $sqlFields) . ') ' . ' values ';
		$valSql = array();
		$bulkPos = 1;
		$this->_db->beginTransaction();
		foreach ($data as $values) {
			$valSql[] = $this->_db->quoteInto(' (?)', $values);
			if (($bulkPos % 50 == 0) && !empty($valSql)) {
				$this->_db->query($sql . implode(',', $valSql));
				$valSql = array();
			}
			$bulkPos++;
		}
		if (!empty($valSql)) {
			$this->_db->query($sql . implode(',', $valSql));
		}
		return $this->_db->commit();
	}
}
