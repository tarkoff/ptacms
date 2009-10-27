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
	protected $_xmlFile;
	protected $_xml;
	protected $_zipEnabled = false;
	protected $_loadMode = self::LOAD_LOCAL;
	protected $_config = array();

	protected $_section;
	protected $_currentTag;
	protected $_currentTagAttrs;
	protected $_currentText;

	protected static $_configFile = 'config.xml';

	const LOAD_LOCAL = 0;
	const LOAD_REMOTE = 1;
	
	const SECTION_INIT = 0;
	const SECTION_RUN = 1;
	const SECTION_FLUSH = 2;

	public function __construct($file)
	{
		$this->_xmlFile = $file;
		if (strpos($file, 'http://') !== false) {
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

	public function setConfigFile($file = '')
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

		$parser = xml_parser_create('UTF-8');

		xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, 'UTF-8');
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); 

		xml_set_element_handler( $parser, array($this, 'startElement'), array($this, 'endElement'));
		xml_set_character_data_handler( $parser, array($this, 'textData'));

		$fp = fopen( $this->_xmlFile, 'r' );

		$data = null;
		while( !feof($fp) ) {
			$data = str_replace('&', '__-xxx-__', fgets($fp, 4096));
			if (!xml_parse( $parser, $data, feof($fp) )) {
				trigger_error(
					sprintf(
						'XML Error: %s in line %d',
						xml_error_string(xml_get_error_code($parser)),
						xml_get_current_line_number($parser)
					),
					E_USER_ERROR
				);
			}
		}

		xml_parser_free( $parser );
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

	protected function startElement($parser, $name, $attrs)
	{
		$this->_currentTag = $name;
		$this->_currentTagAttrs = $attrs;

		switch ($name) {
			case 'advertizers':
				$this->_section = 'advertizers';
				$this->_parseAdvertizers($attrs, self::SECTION_INIT);
			break;

			case 'adv':
				if ('adv_region_delivery' == $this->_section) {
					$this->_parseAdvRegionDelivery($attrs, self::SECTION_INIT);
				}
			break;

			case 'regions_delivery':
				$this->_section = 'regions_delivery';
				$this->_parseRegionsDelivery($attrs, self::SECTION_INIT);
			break;

			case 'adv_region_delivery':
				$this->_section = 'adv_region_delivery';
				$this->_parseAdvRegionDelivery($attrs, self::SECTION_INIT);
			break;

			case 'regions_geotarget':
				$this->_section = 'regions_geotarget';
				$this->_parseRegionsGeotarget($attrs, self::SECTION_INIT);
			break;

			case 'adv_region_geotarget':
				$this->_section = 'adv_region_geotarget';
				$this->_parseAdvRegionGeotarget($attrs, self::SECTION_INIT);
			break;

			case 'currencies':
				$this->_section = 'currencies';
				$this->_parseCurrencies($attrs, self::SECTION_INIT);
			break;

			case 'brands':
				$this->_section = 'brands';
				$this->_parseBrands($attrs, self::SECTION_INIT);
			break;

			case 'categories':
				$this->_section = 'categories';
				$this->_parseCategories($attrs, self::SECTION_INIT);
			break;

			case 'offers':
				$this->_section = 'offers';
				$this->_parseOffers($attrs, self::SECTION_INIT);
			break;

			case 'offer':
				$this->_parseOffers($attrs, self::SECTION_INIT);
			break;
		}
	}

	protected function endElement($parser, $name)
	{
		$this->_currentTag = $name;

		switch ($name) {
			case 'advertizers':
				$this->_parseAdvertizers(array(), self::SECTION_FLUSH);
				$this->_section = null;
			break;

			case 'adv':
				if ('advertizers' == $this->_section) {
					$this->_parseAdvertizers($this->_currentTagAttrs, self::SECTION_RUN);
				} elseif ('adv_region_delivery' == $this->_section) {
					$this->_parseAdvRegionDelivery($this->_currentTagAttrs, self::SECTION_RUN);
				}
			break;

			case 'regions_delivery':
				$this->_parseRegionsDelivery(array(), self::SECTION_FLUSH);
				$this->_section = null;
			break;

			case 'rd':
				if ('regions_delivery' == $this->_section) {
					$this->_parseRegionsDelivery($this->_currentTagAttrs, self::SECTION_RUN);
				} elseif ('adv_region_delivery' == $this->_section) {
					$this->_parseAdvRegionDelivery($this->_currentTagAttrs, self::SECTION_RUN);
				}
			break;

			case 'adv_region_delivery':
				$this->_parseAdvRegionDelivery(array(), self::SECTION_FLUSH);
				$this->_section = null;
			break;

			case 'regions_geotarget':
				$this->_parseRegionsGeotarget(array(), self::SECTION_FLUSH);
				$this->_section = null;
			break;

			case 'geo':
				$this->_parseRegionsGeotarget($this->_currentTagAttrs, self::SECTION_RUN);
			break;

			case 'adv_region_geotarget':
				$this->_parseAdvRegionGeotarget(array(), self::SECTION_FLUSH);
				$this->_section = null;
			break;

			case 'geotarget':
				$this->_parseAdvRegionGeotarget($this->_currentTagAttrs, self::SECTION_RUN);
			break;

			case 'currencies':
				$this->_parseCurrencies(array(), self::SECTION_FLUSH);
				$this->_section = null;
			break;

			case 'currency':
				$this->_parseCurrencies($this->_currentTagAttrs, self::SECTION_RUN);
			break;

			case 'brands':
				$this->_parseBrands(array(), self::SECTION_FLUSH);
				$this->_section = null;
			break;

			case 'brand':
				$this->_parseBrands($this->_currentTagAttrs, self::SECTION_RUN);
			break;

			case 'categories':
				$this->_parseCategories(array(), self::SECTION_FLUSH);
				$this->_section = null;
			break;

			case 'category':
				$this->_parseCategories($this->_currentTagAttrs, self::SECTION_RUN);
			break;

			case 'offers':
				$this->_parseOffers(array(), self::SECTION_FLUSH);
				$this->_section = null;
			break;

			case 'offer':
				$this->_parseOffers($this->_currentTagAttrs, self::SECTION_FLUSH);
			break;

			case 'type':
				$this->_parseOffers($this->_currentTagAttrs, self::SECTION_RUN);
			break;

			case 'name':
				$this->_parseOffers($this->_currentTagAttrs, self::SECTION_RUN);
			break;

			case 'url':
				$this->_parseOffers($this->_currentTagAttrs, self::SECTION_RUN);
			break;

			case 'currencyId':
				$this->_parseOffers($this->_currentTagAttrs, self::SECTION_RUN);
			break;

			case 'desc':
				$this->_parseOffers($this->_currentTagAttrs, self::SECTION_RUN);
			break;

			case 'price':
				$this->_parseOffers($this->_currentTagAttrs, self::SECTION_RUN);
			break;
		}

		$this->_currentText = null;
	}

	protected function textData($parser, $text)
	{

		$text = str_replace('__-xxx-__amp;', '&', $text);
		$this->_currentText .= $text;
		var_dump($this->_currentTag . ' == ' . $this->_currentText . "\n");
	}

	protected function _parseAdvertizers($attrs = array(), $mode = self::SECTION_INIT)
	{
		static $bulkPos = 1, $values = array();
		$table = $this->_config->tables->advertizers;

		switch ($this->_currentTag) {
			case 'advertizers':
				$bulkPos = 1;
			break;

			case 'adv':
				$bulkPos++;
				$values[] = array((int)$attrs['id'], $this->_currentText);
			break;
		}

		if (!empty($values) && ($bulkPos % 50 == 0 || self::SECTION_FLUSH == $mode)) {
			$this->_insert(
				$values,
				$table->name,
				array($table->fields->id, $table->fields->title)
			);
			$values = array();
		}
		$bulkPos++;
	}

	protected function _parseRegionsDelivery($attrs = array(), $mode = self::SECTION_INIT)
	{
		static $bulkPos = 1, $values = array();
		$table = $this->_config->tables->regions_delivery;

		switch ($this->_currentTag) {
			case 'regions_delivery':
				$bulkPos = 1;
			break;

			case 'rd':
				$bulkPos++;
				$values[] = array((int)$attrs['id'], $this->_currentText);
			break;
		}

		if (!empty($values) && ($bulkPos % 50 == 0 || self::SECTION_FLUSH == $mode)) {
			$this->_insert(
				$values,
				$table->name,
				array($table->fields->id, $table->fields->title)
			);
			$values = array();
		}
		$bulkPos++;
	}

	protected function _parseAdvRegionDelivery($attrs = array(), $mode = self::SECTION_INIT)
	{
		static $bulkPos = 1, $values = array(), $advid = null;
		$table = $this->_config->tables->adv_region_delivery;

		switch ($this->_currentTag) {
			case 'adv_region_delivery':
				$bulkPos = 1;
			break;

			case 'adv':
				$bulkPos++;
				if ($mode == self::SECTION_INIT) {
					$advid = (int)$attrs['id'];
				}
			break;

			case 'rd':
				$bulkPos++;
				$values[] = array($advid, (int)$attrs['id']);
			break;
		}

		if (!empty($values) && ($bulkPos % 50 == 0 || self::SECTION_FLUSH == $mode)) {
			$this->_insert(
				$values,
				$table->name,
				array($table->fields->advid, $table->fields->rdid)
			);
			$values = array();
		}
		$bulkPos++;
	}

	protected function _parseRegionsGeotarget($attrs = array(), $mode = self::SECTION_INIT)
	{
		static $bulkPos = 1, $values = array();
		$table = $this->_config->tables->regions_geotarget;

		switch ($this->_currentTag) {
			case 'regions_geotarget':
				$bulkPos = 1;
			break;

			case 'geo':
				$bulkPos++;
				$values[] = array((int)$attrs['id'], (int)$attrs['pid'], $this->_currentText);
			break;
		}

		if (!empty($values) && ($bulkPos % 50 == 0 || self::SECTION_FLUSH == $mode)) {
			$this->_insert(
				$values,
				$table->name,
				array($table->fields->id, $table->fields->pid, $table->fields->title)
			);
			$values = array();
		}
		$bulkPos++;
	}

	protected function _parseAdvRegionGeotarget($attrs = array(), $mode = self::SECTION_INIT)
	{
		static $bulkPos = 1, $values = array();
		$table = $this->_config->tables->adv_region_geotarget;

		switch ($this->_currentTag) {
			case 'adv_region_geotarget':
				$bulkPos = 1;
			break;

			case 'geotarget':
				$bulkPos++;
				$values[] = array((int)$attrs['advid'], (int)$attrs['id']);
			break;
		}

		if (!empty($values) && ($bulkPos % 50 == 0 || self::SECTION_FLUSH == $mode)) {
			$this->_insert(
				$values,
				$table->name,
				array($table->fields->advid, $table->fields->rgtid)
			);
			$values = array();
		}
		$bulkPos++;
	}

	protected function _parseCurrencies($attrs = array(), $mode = self::SECTION_INIT)
	{
		static $bulkPos = 1, $values = array();
		$table = $this->_config->tables->currencies;

		switch ($this->_currentTag) {
			case 'currencies':
				$bulkPos = 1;
			break;

			case 'currency':
				$bulkPos++;
				$values[] = array($attrs['id'], $attrs['rate']);
			break;
		}

		if (!empty($values) && ($bulkPos % 50 == 0 || self::SECTION_FLUSH == $mode)) {
			$this->_insert(
				$values,
				$table->name,
				array($table->fields->id, $table->fields->rate)
			);
			$values = array();
		}
		$bulkPos++;
	}

	protected function _parseBrands($attrs = array(), $mode = self::SECTION_INIT)
	{
		static $bulkPos = 1, $values = array();
		$table = $this->_config->tables->brands;

		switch ($this->_currentTag) {
			case 'brands':
				$bulkPos = 1;
			break;

			case 'brand':
				$bulkPos++;
				$values[] = array((int)$attrs['id'], $this->_currentText);
			break;
		}

		if (!empty($values) && ($bulkPos % 50 == 0 || self::SECTION_FLUSH == $mode)) {
			$this->_insert(
				$values,
				$table->name,
				array($table->fields->id, $table->fields->title)
			);
			$values = array();
		}
		$bulkPos++;
	}

	protected function _parseCategories($attrs = array(), $mode = self::SECTION_INIT)
	{
		static $bulkPos = 1, $values = array();
		$table = $this->_config->tables->categories;

		switch ($this->_currentTag) {
			case 'categories':
				$bulkPos = 1;
			break;

			case 'category':
				$bulkPos++;
				$values[] = array((int)$attrs['id'], (int)$attrs['parentId'], $this->_currentText);
			break;
		}

		if (!empty($values) && ($bulkPos % 50 == 0 || self::SECTION_FLUSH == $mode)) {
			$this->_insert(
				$values,
				$table->name,
				array($table->fields->id, $table->fields->parentId, $table->fields->title)
			);
			$values = array();
		}
		$bulkPos++;
	}

	protected function _parseOffers($attrs = array(), $mode = self::SECTION_INIT)
	{
		static $bulkPos = 1, $values = array(), $offer = array();
		$table = $this->_config->tables->offers;

		switch ($this->_currentTag) {
			case 'offers':
				$bulkPos = 1;
			break;

			case 'offer':
				if (self::SECTION_INIT == $mode) {
					$offer = array(
						(int)$attrs['id'],
						(int)$attrs['brandid'],
						(int)$attrs['advid'],
						(int)$attrs['cat'],
						(string)$attrs['src'],
						(int)$attrs['w'],
						(int)$attrs['h']
					);
				} elseif (self::SECTION_FLUSH == $mode) {
					$bulkPos++;
					$values[] = $offer;
					$offer = array();
				}
			break;

			case 'type':
				$offer[] = $this->_currentText;
			break;

			case 'name':
				$offer[] = $this->_currentText;
			break;

			case 'url':
				$offer[] = $this->_currentText;
			break;

			case 'currencyId':
				$offer[] = $this->_currentText;
			break;

			case 'desc':
				$offer[] = $this->_currentText;
			break;

			case 'price':
				$offer[] = $this->_currentText;
			break;
		}

		if (
			!empty($values)
			&& (
				$bulkPos % 50 == 0
				|| (self::SECTION_FLUSH == $mode && 'offers' == $this->_currentTag)
			)
		) {
			$this->_insert(
				$values,
				$table->name,
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
			$values = $offer = array();
		}
	}

	protected function _insert($data, $table, $sqlFields)
	{
		$fp = fopen('sqlLog.txt', "a");
		
		$sql = 'insert ignore into ' . $table 
			. ' (' . implode(', ', $sqlFields) . ') ' . ' values ';
		$valSql = array();
		$bulkPos = 1;
		$this->_db->beginTransaction();
		foreach ($data as $values) {
			$valSql[] = $this->_db->quoteInto(' (?)', $values);
			if (($bulkPos % 50 == 0) && !empty($valSql)) {
//var_dump($sql . implode(',', $valSql));
fwrite($fp, $sql . implode(',', $valSql) . "\n\n");
				$this->_db->query($sql . implode(',', $valSql));
				$valSql = array();
			}
			$bulkPos++;
		}
		if (!empty($valSql)) {
//var_dump($sql . implode(',', $valSql));
fwrite($fp, $sql . implode(',', $valSql) . "\n\n");
$this->_db->query($sql . implode(',', $valSql));
		}
fclose($fp);
		return $this->_db->commit();
	}
}
